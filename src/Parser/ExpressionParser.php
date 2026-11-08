<?php

/**
 * This file is part of the Phalcon Framework.
 *
 * (c) Phalcon Team <team@phalcon.io>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Phalcon\Phql\Parser;

use Phalcon\Phql\Exception;
use Phalcon\Phql\Scanner\Opcode;
use Phalcon\Phql\Scanner\Token;

use function count;

/**
 * Parses `expr` of cphalcon's parser.php.lemon by precedence climbing over
 * the grammar's own precedence declarations (lines 17-29), lowest first:
 *
 *      1  %right AGAINST
 *      2  %left  BETWEEN BETWEEN_NOT
 *      3  %left  EQUALS NOTEQUALS LESS GREATER GREATEREQUAL LESSEQUAL
 *                OP_MATCHES OP_CONTAINS OP_CONTAINED OP_OVERLAPS
 *      4  %left  AND OR
 *      5  %left  LIKE ILIKE
 *      6  %left  BITWISE_AND BITWISE_OR BITWISE_XOR
 *      7  %left  DIVIDE TIMES MOD
 *      8  %left  PLUS MINUS OP_CONCAT
 *      9  %left  IS
 *     10  %right IN
 *     11  %right NOT BITWISE_NOT
 *     12  %left  OP_JSON_GET OP_JSON_GET_TEXT OP_JSON_PATH OP_JSON_PATH_TEXT
 *
 * Lemon gives a rule the level of its leftmost terminal that has one, so
 * `expr NOT LIKE expr`, `NOT ILIKE` and `NOT IN` take NOT's level, unary
 * minus takes MINUS's, and rules whose terminals have no level (CASE ... THEN
 * expr, ELSE expr) never end an operand early. On an operator after an
 * operand Lemon shifts when the operator's level is higher than the pending
 * rule's, or equal and right-associative, and reduces otherwise - see
 * shifts(). This yields cphalcon's trees, including the surprising ones:
 * `a = 1 AND b` is `a = (1 AND b)` and `a + b * c` is `(a + b) * c`.
 *
 * Left-associative chains are loops and right-hand operands are recursion,
 * so recursion depth follows Lemon's stack depth, which Cursor bounds.
 *
 * Subqueries are parsed by a StatementParser of their own rather than by the
 * one that owns this parser: a reference back to it would make every parse
 * a reference cycle, left for PHP's cycle collector instead of freed.
 */
final class ExpressionParser
{
    public function __construct(private readonly Cursor $cursor)
    {
    }

    /**
     * Precedence level of a token that can follow an operand, or null when
     * the token ends the expression.
     */
    private static function level(int $opcode): ?int
    {
        return match ($opcode) {
            Opcode::AGAINST->value          => 1,
            Opcode::BETWEEN->value,
            Opcode::BETWEEN_NOT->value      => 2,
            Opcode::EQUALS->value,
            Opcode::NOTEQUALS->value,
            Opcode::LESS->value,
            Opcode::GREATER->value,
            Opcode::GREATEREQUAL->value,
            Opcode::LESSEQUAL->value,
            Opcode::OP_MATCHES->value,
            Opcode::OP_CONTAINS->value,
            Opcode::OP_CONTAINED->value,
            Opcode::OP_OVERLAPS->value      => 3,
            Opcode::AND->value,
            Opcode::OR->value               => 4,
            Opcode::LIKE->value,
            Opcode::ILIKE->value            => 5,
            Opcode::BITWISE_AND->value,
            Opcode::BITWISE_OR->value,
            Opcode::BITWISE_XOR->value      => 6,
            Opcode::DIV->value,
            Opcode::MUL->value,
            Opcode::MOD->value              => 7,
            Opcode::ADD->value,
            Opcode::SUB->value,
            Opcode::OP_CONCAT->value        => 8,
            Opcode::IS->value               => 9,
            Opcode::IN->value               => 10,
            Opcode::NOT->value              => 11,
            Opcode::OP_JSON_GET->value,
            Opcode::OP_JSON_GET_TEXT->value,
            Opcode::OP_JSON_PATH->value,
            Opcode::OP_JSON_PATH_TEXT->value => 12,
            default                          => null,
        };
    }

    /**
     * Lemon's shift/reduce decision for an operator of level $level after a
     * finished operand of a rule of level $pending: AGAINST (1), IN (10)
     * and NOT / BITWISE_NOT (11) are the right-associative levels.
     */
    private static function shifts(int $level, ?int $pending): bool
    {
        return $pending === null
            || $level > $pending
            || ($level === $pending && ($level === 1 || $level === 10 || $level === 11));
    }

    /**
     * expr, as the operand of a rule of precedence level $pending (null when
     * no operator rule is waiting for it).
     *
     * @return array<string, mixed>
     *
     * @throws Exception
     */
    public function parse(?int $pending = null): array
    {
        return $this->continue($this->parsePrefix(), $pending);
    }

    /**
     * argument_list ::= argument_list COMMA argument_item | argument_item
     * argument_item ::= TIMES | expr
     *
     * Always a list, even of one item.
     *
     * @return list<array<string, mixed>>
     *
     * @throws Exception
     */
    public function parseArgumentList(): array
    {
        $arguments = [$this->parseArgumentItem()];

        while ($this->cursor->is(Opcode::COMMA->value)) {
            $this->cursor->shift();
            $arguments[] = $this->parseArgumentItem();
            $this->cursor->reduce(3);
        }

        return $arguments;
    }

    /**
     * The start of a column_item: either the whole of
     *     column_item ::= IDENTIFIER DOT TIMES
     * returned as its finished DOMAINALL column item, or an expression.
     *
     * @return array<string, mixed>
     *
     * @throws Exception
     */
    public function parseColumn(): array
    {
        if (!$this->cursor->is(Opcode::IDENTIFIER->value)) {
            return $this->parse();
        }

        $name = $this->cursor->shift();
        if (!$this->cursor->is(Opcode::DOT->value)) {
            return $this->continue($this->identifierTail($name), null);
        }

        $this->cursor->shift();
        if ($this->cursor->is(Opcode::MUL->value)) {
            $this->cursor->shift();
            $this->cursor->reduce(3);

            return Ast::columnItem(Opcode::DOMAINALL->value, (string) $name->value, null);
        }

        return $this->continue($this->qualifiedTail($name), null);
    }

    /**
     * Applies the operators that bind the operand $left before the pending
     * rule completes.
     *
     * @param array<string, mixed> $left
     *
     * @return array<string, mixed>
     *
     * @throws Exception
     */
    private function continue(array $left, ?int $pending): array
    {
        while (true) {
            $opcode = $this->cursor->current()->opcode;
            $level  = self::level($opcode);
            if ($level === null || !self::shifts($level, $pending)) {
                return $left;
            }

            $left = match ($opcode) {
                Opcode::IS->value  => $this->parseIs($left),
                Opcode::IN->value  => $this->parseIn($left, Opcode::IN->value, 5),
                Opcode::NOT->value => $this->parseNotInfix($left),
                default            => $this->parseBinary($left, $opcode, $level),
            };
        }
    }

    /**
     * function_call ::= IDENTIFIER PARENTHESES_OPEN distinct_or_null argument_list_or_null PARENTHESES_CLOSE
     * distinct_or_null ::= DISTINCT | .
     * argument_list_or_null ::= argument_list | .
     *
     * @return array<string, mixed>
     *
     * @throws Exception
     */
    private function functionCall(Token $name): array
    {
        $this->cursor->shift();

        $distinct = $this->cursor->is(Opcode::DISTINCT->value);
        if ($distinct) {
            $this->cursor->shift();
        } else {
            $this->cursor->reduce(0);
        }

        $arguments = null;
        if ($this->cursor->is(Opcode::PARENTHESES_CLOSE->value)) {
            $this->cursor->reduce(0);
        } else {
            $arguments = $this->parseArgumentList();
        }

        $this->cursor->expect(Opcode::PARENTHESES_CLOSE->value);
        $this->cursor->reduce(5);

        return Ast::funcCall((string) $name->value, $arguments, $distinct);
    }

    /**
     * After an IDENTIFIER (already shifted) in an expression:
     *     function_call ::= IDENTIFIER PARENTHESES_OPEN ...
     *     qualified_name ::= IDENTIFIER DOT IDENTIFIER | IDENTIFIER
     *
     * @return array<string, mixed>
     *
     * @throws Exception
     */
    private function identifierTail(Token $name): array
    {
        if ($this->cursor->is(Opcode::PARENTHESES_OPEN->value)) {
            return $this->functionCall($name);
        }

        if (!$this->cursor->is(Opcode::DOT->value)) {
            return Ast::qualifiedName(null, (string) $name->value);
        }

        $this->cursor->shift();

        return $this->qualifiedTail($name);
    }

    /**
     * argument_item ::= TIMES | expr
     *
     * @return array<string, mixed>
     *
     * @throws Exception
     */
    private function parseArgumentItem(): array
    {
        if (!$this->cursor->is(Opcode::MUL->value)) {
            return $this->parse();
        }

        $this->cursor->shift();

        return Ast::columnItem(Opcode::STARALL->value, null, null);
    }

    /**
     * expr ::= expr OP expr, for every binary operator; the node type is the
     * operator's token code.
     *
     * @param array<string, mixed> $left
     *
     * @return array<string, mixed>
     *
     * @throws Exception
     */
    private function parseBinary(array $left, int $opcode, int $level): array
    {
        $this->cursor->shift();
        $right = $this->parse($level);
        $this->cursor->reduce(3);

        return Ast::expr($opcode, $left, $right);
    }

    /**
     * expr ::= CASE expr when_clauses END
     * when_clauses ::= when_clauses when_clause | when_clause
     * when_clause ::= WHEN expr THEN expr | ELSE expr
     *
     * @return array<string, mixed>
     *
     * @throws Exception
     */
    private function parseCase(): array
    {
        $this->cursor->shift();
        $subject = $this->parse();
        $clauses = [];

        do {
            if ($this->cursor->is(Opcode::WHEN->value)) {
                $this->cursor->shift();
                $condition = $this->parse();
                $this->cursor->expect(Opcode::THEN->value);
                $result = $this->parse();
                $this->cursor->reduce(4);
                $clauses[] = Ast::expr(Opcode::WHEN->value, $condition, $result);
            } elseif ($this->cursor->is(Opcode::ELSE->value)) {
                $this->cursor->shift();
                $result = $this->parse();
                $this->cursor->reduce(2);
                $clauses[] = Ast::expr(Opcode::ELSE->value, $result, null);
            } else {
                throw $this->cursor->syntaxError();
            }

            if (count($clauses) > 1) {
                $this->cursor->reduce(2);
            }
        } while ($this->cursor->is(Opcode::WHEN->value) || $this->cursor->is(Opcode::ELSE->value));

        $this->cursor->expect(Opcode::END->value);
        $this->cursor->reduce(4);

        return Ast::expr(Opcode::CASE->value, $subject, $clauses);
    }

    /**
     * expr ::= CAST PARENTHESES_OPEN expr AS IDENTIFIER PARENTHESES_CLOSE
     * expr ::= CONVERT PARENTHESES_OPEN expr USING IDENTIFIER PARENTHESES_CLOSE
     *
     * @return array<string, mixed>
     *
     * @throws Exception
     */
    private function parseConversion(int $type, int $keyword): array
    {
        $this->cursor->shift();
        $this->cursor->expect(Opcode::PARENTHESES_OPEN->value);
        $value = $this->parse();
        $this->cursor->expect($keyword);
        $target = $this->cursor->expect(Opcode::IDENTIFIER->value);
        $this->cursor->expect(Opcode::PARENTHESES_CLOSE->value);
        $this->cursor->reduce(6);

        return Ast::expr($type, $value, Ast::rawQualifiedName((string) $target->value));
    }

    /**
     * expr ::= EXISTS PARENTHESES_OPEN select_statement PARENTHESES_CLOSE
     *
     * @return array<string, mixed>
     *
     * @throws Exception
     */
    private function parseExists(): array
    {
        $this->cursor->shift();
        $this->cursor->expect(Opcode::PARENTHESES_OPEN->value);
        $select = $this->parseSubquery();
        $this->cursor->expect(Opcode::PARENTHESES_CLOSE->value);
        $this->cursor->reduce(4);

        return Ast::expr(Opcode::EXISTS->value, null, $select);
    }

    /**
     * expr ::= expr IN PARENTHESES_OPEN argument_list PARENTHESES_CLOSE
     * expr ::= expr IN PARENTHESES_OPEN select_statement PARENTHESES_CLOSE
     * and the NOT IN forms (one symbol longer), with IN / NOT IN shifted
     * up to the IN.
     *
     * @param array<string, mixed> $left
     *
     * @return array<string, mixed>
     *
     * @throws Exception
     */
    private function parseIn(array $left, int $type, int $length): array
    {
        $this->cursor->shift();
        $this->cursor->expect(Opcode::PARENTHESES_OPEN->value);

        $right = $this->cursor->is(Opcode::SELECT->value)
            ? $this->parseSubquery()
            : $this->parseArgumentList();

        $this->cursor->expect(Opcode::PARENTHESES_CLOSE->value);
        $this->cursor->reduce($length);

        return Ast::expr($type, $left, $right);
    }

    /**
     * expr ::= expr IS NULL | expr IS NOT NULL
     *
     * @param array<string, mixed> $left
     *
     * @return array<string, mixed>
     *
     * @throws Exception
     */
    private function parseIs(array $left): array
    {
        $this->cursor->shift();

        if ($this->cursor->is(Opcode::NULL->value)) {
            $this->cursor->shift();
            $this->cursor->reduce(3);

            return Ast::expr(Opcode::ISNULL->value, $left, null);
        }

        $this->cursor->expect(Opcode::NOT->value);
        $this->cursor->expect(Opcode::NULL->value);
        $this->cursor->reduce(4);

        return Ast::expr(Opcode::ISNOTNULL->value, $left, null);
    }

    /**
     * expr ::= expr NOT LIKE expr | expr NOT ILIKE expr | expr NOT IN (...)
     *
     * NOT is shifted on NOT's level before the parser knows which follows.
     *
     * @param array<string, mixed> $left
     *
     * @return array<string, mixed>
     *
     * @throws Exception
     */
    private function parseNotInfix(array $left): array
    {
        $this->cursor->shift();

        $type = match ($this->cursor->current()->opcode) {
            Opcode::LIKE->value  => Opcode::NLIKE->value,
            Opcode::ILIKE->value => Opcode::NILIKE->value,
            Opcode::IN->value    => Opcode::NOTIN->value,
            default              => throw $this->cursor->syntaxError(),
        };

        if ($type === Opcode::NOTIN->value) {
            return $this->parseIn($left, $type, 6);
        }

        $this->cursor->shift();
        $right = $this->parse(11);
        $this->cursor->reduce(4);

        return Ast::expr($type, $left, $right);
    }

    /**
     * expr ::= PARENTHESES_OPEN select_statement PARENTHESES_CLOSE
     * expr ::= PARENTHESES_OPEN expr PARENTHESES_CLOSE
     *
     * @return array<string, mixed>
     *
     * @throws Exception
     */
    private function parseParentheses(): array
    {
        $this->cursor->shift();

        if ($this->cursor->is(Opcode::SELECT->value)) {
            $select = $this->parseSubquery();
            $this->cursor->expect(Opcode::PARENTHESES_CLOSE->value);
            $this->cursor->reduce(3);

            return Ast::expr(Opcode::SUBQUERY->value, $select, null);
        }

        $inner = $this->parse();
        $this->cursor->expect(Opcode::PARENTHESES_CLOSE->value);
        $this->cursor->reduce(3);

        return Ast::expr(Opcode::ENCLOSED->value, $inner, null);
    }

    /**
     * An operand: a prefix operator with its operand, a parenthesised or
     * keyword-led expression, a name, a function call, a literal or a
     * placeholder.
     *
     * @return array<string, mixed>
     *
     * @throws Exception
     */
    private function parsePrefix(): array
    {
        $token = $this->cursor->current();

        switch ($token->opcode) {
            case Opcode::IDENTIFIER->value:
                $this->cursor->shift();

                return $this->identifierTail($token);

            case Opcode::INTEGER->value:
            case Opcode::HINTEGER->value:
            case Opcode::STRING->value:
            case Opcode::DOUBLE->value:
            case Opcode::NULL->value:
            case Opcode::TRUE->value:
            case Opcode::FALSE->value:
                $this->cursor->shift();

                return Ast::literal($token->opcode, $token->value);

            case Opcode::NPLACEHOLDER->value:
            case Opcode::SPLACEHOLDER->value:
            case Opcode::BPLACEHOLDER->value:
                $this->cursor->shift();

                return Ast::placeholder($token->opcode, (string) $token->value);

            case Opcode::PARENTHESES_OPEN->value:
                return $this->parseParentheses();

            case Opcode::SUB->value:
                return $this->parseUnary(Opcode::MINUS->value, 8);

            case Opcode::NOT->value:
                return $this->parseUnary(Opcode::NOT->value, 11);

            case Opcode::BITWISE_NOT->value:
                return $this->parseUnary(Opcode::BITWISE_NOT->value, 11);

            case Opcode::CASE->value:
                return $this->parseCase();

            case Opcode::CAST->value:
                return $this->parseConversion(Opcode::CAST->value, Opcode::AS->value);

            case Opcode::CONVERT->value:
                return $this->parseConversion(Opcode::CONVERT->value, Opcode::USING->value);

            case Opcode::EXISTS->value:
                return $this->parseExists();
        }

        throw $this->cursor->syntaxError();
    }

    /**
     * select_statement, inside an expression.
     *
     * @return array<string, mixed>
     *
     * @throws Exception
     */
    private function parseSubquery(): array
    {
        return (new StatementParser($this->cursor))->parseSelectStatement();
    }

    /**
     * expr ::= MINUS expr | NOT expr | BITWISE_NOT expr
     *
     * @return array<string, mixed>
     *
     * @throws Exception
     */
    private function parseUnary(int $type, int $level): array
    {
        $this->cursor->shift();
        $operand = $this->parse($level);
        $this->cursor->reduce(2);

        return Ast::expr($type, null, $operand);
    }

    /**
     * qualified_name ::= IDENTIFIER DOT IDENTIFIER, with IDENTIFIER DOT shifted.
     *
     * @return array<string, mixed>
     *
     * @throws Exception
     */
    private function qualifiedTail(Token $domain): array
    {
        $name = $this->cursor->expect(Opcode::IDENTIFIER->value);
        $this->cursor->reduce(3);

        return Ast::qualifiedName((string) $domain->value, (string) $name->value);
    }
}
