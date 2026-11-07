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

/**
 * Parses statements and clauses of cphalcon's parser.php.lemon by recursive
 * descent, one method per group of rules (each quotes the rules it applies).
 *
 * Every rule application reports its right-hand-side length to the Cursor
 * in the order Lemon reduces, so the Cursor's count is Lemon's stack depth.
 * An absent optional clause is Lemon's empty rule for it (`where_clause ::=
 * .` and the like) and pushes an entry. Lists are loops: the grammar's lists
 * are left-recursive, so Lemon keeps them in one stack entry.
 */
final class StatementParser
{
    private readonly ExpressionParser $expressions;

    public function __construct(private readonly Cursor $cursor)
    {
        $this->expressions = new ExpressionParser($cursor);
    }

    /**
     * program ::= query_language
     * query_language ::= select_statement | insert_statement
     *                  | update_statement | delete_statement
     *
     * The statement must end the input: Lemon reduces and accepts on the
     * end-of-input token and never shifts it.
     *
     * @return array<string, mixed>
     *
     * @throws Exception
     *
     * @phpstan-impure
     */
    public function parseProgram(): array
    {
        $statement = match ($this->cursor->current()->opcode) {
            Opcode::SELECT->value => $this->parseSelectStatement(),
            Opcode::INSERT->value => $this->parseInsertStatement(),
            Opcode::UPDATE->value => $this->parseUpdateStatement(),
            Opcode::DELETE->value => $this->parseDeleteStatement(),
            default               => throw $this->cursor->syntaxError(),
        };

        if (!$this->cursor->is(Token::EOF)) {
            throw $this->cursor->syntaxError();
        }

        return $statement;
    }

    /**
     * select_statement ::= select_clause where_clause group_clause having_clause
     *                      order_clause select_limit_clause for_update_clause
     *
     * @return array<string, mixed>
     *
     * @throws Exception
     *
     * @phpstan-impure
     */
    public function parseSelectStatement(): array
    {
        $select  = $this->parseSelectClause();
        $where   = $this->parseOptionalExpression(Opcode::WHERE->value);
        $groupBy = $this->parseGroupClause();
        $having  = $this->parseOptionalExpression(Opcode::HAVING->value);
        $orderBy = $this->parseOrderClause();
        $limit   = $this->parseSelectLimitClause();

        $forUpdate = null;
        if ($this->cursor->is(Opcode::FOR->value)) {
            $this->cursor->shift();
            $this->cursor->expect(Opcode::UPDATE->value);
            $this->cursor->reduce(2);
            $forUpdate = true;
        } else {
            $this->cursor->reduce(0);
        }

        $this->cursor->reduce(7);

        return Ast::selectStatement($select, $where, $orderBy, $groupBy, $having, $limit, $forUpdate);
    }

    /**
     * associated_name ::= aliased_or_qualified_name
     *                   | aliased_or_qualified_name AS IDENTIFIER
     *                   | aliased_or_qualified_name IDENTIFIER
     * each optionally followed by WITH with_item or WITH ( with_list )
     *
     * @return array<string, mixed>
     *
     * @throws Exception
     */
    private function parseAssociatedName(): array
    {
        $qualifiedName = $this->parseQualifiedName();
        $length        = 1;

        $alias = null;
        if ($this->cursor->is(Opcode::AS->value)) {
            $this->cursor->shift();
            $alias  = (string) $this->cursor->expect(Opcode::IDENTIFIER->value)->value;
            $length = 3;
        } elseif ($this->cursor->is(Opcode::IDENTIFIER->value)) {
            $alias  = (string) $this->cursor->shift()->value;
            $length = 2;
        }

        $with = null;
        if ($this->cursor->is(Opcode::WITH->value)) {
            $this->cursor->shift();
            if ($this->cursor->is(Opcode::PARENTHESES_OPEN->value)) {
                $with   = $this->parseWithList();
                $length += 4;
            } else {
                $with   = $this->parseWithItem();
                $length += 2;
            }
        }

        $this->cursor->reduce($length);

        return Ast::assocName($qualifiedName, $alias, $with);
    }

    /**
     * associated_name_list ::= associated_name_list COMMA associated_name | associated_name
     *
     * @return array<array-key, mixed>
     *
     * @throws Exception
     */
    private function parseAssociatedNameList(): array
    {
        $names = [$this->parseAssociatedName()];

        while ($this->cursor->is(Opcode::COMMA->value)) {
            $this->cursor->shift();
            $names[] = $this->parseAssociatedName();
            $this->cursor->reduce(3);
        }

        return Ast::bareIfSingle($names);
    }

    /**
     * column_item ::= TIMES | IDENTIFIER DOT TIMES | expr AS IDENTIFIER
     *               | expr IDENTIFIER | expr
     *
     * @return array<string, mixed>
     *
     * @throws Exception
     */
    private function parseColumnItem(): array
    {
        if ($this->cursor->is(Opcode::MUL->value)) {
            $this->cursor->shift();

            return Ast::columnItem(Opcode::STARALL->value, null, null);
        }

        $expression = $this->expressions->parseColumn();
        if ($expression['type'] === Opcode::DOMAINALL->value) {
            return $expression;
        }

        if ($this->cursor->is(Opcode::AS->value)) {
            $this->cursor->shift();
            $alias = $this->cursor->expect(Opcode::IDENTIFIER->value);
            $this->cursor->reduce(3);

            return Ast::columnItem(Opcode::EXPR->value, $expression, (string) $alias->value);
        }

        if ($this->cursor->is(Opcode::IDENTIFIER->value)) {
            $alias = $this->cursor->shift();
            $this->cursor->reduce(2);

            return Ast::columnItem(Opcode::EXPR->value, $expression, (string) $alias->value);
        }

        return Ast::columnItem(Opcode::EXPR->value, $expression, null);
    }

    /**
     * delete_statement ::= delete_clause where_clause limit_clause
     * delete_clause ::= DELETE FROM associated_name
     *
     * @return array<string, mixed>
     *
     * @throws Exception
     */
    private function parseDeleteStatement(): array
    {
        $this->cursor->shift();
        $this->cursor->expect(Opcode::FROM->value);
        $tables = $this->parseAssociatedName();
        $this->cursor->reduce(3);

        $where = $this->parseOptionalExpression(Opcode::WHERE->value);
        $limit = $this->parseLimitClause();
        $this->cursor->reduce(3);

        return Ast::deleteStatement(Ast::deleteClause($tables), $where, $limit);
    }

    /**
     * field_item ::= IDENTIFIER
     *
     * @return array<string, mixed>
     *
     * @throws Exception
     */
    private function parseFieldItem(): array
    {
        return Ast::qualifiedName(null, (string) $this->cursor->expect(Opcode::IDENTIFIER->value)->value);
    }

    /**
     * group_clause ::= GROUP BY group_list | .
     * group_list ::= group_list COMMA group_item | group_item
     *
     * @return array<array-key, mixed>|null
     *
     * @throws Exception
     */
    private function parseGroupClause(): ?array
    {
        if (!$this->cursor->is(Opcode::GROUP->value)) {
            $this->cursor->reduce(0);

            return null;
        }

        $this->cursor->shift();
        $this->cursor->expect(Opcode::BY->value);

        $items = [$this->expressions->parse()];
        while ($this->cursor->is(Opcode::COMMA->value)) {
            $this->cursor->shift();
            $items[] = $this->expressions->parse();
            $this->cursor->reduce(3);
        }

        $this->cursor->reduce(3);

        return Ast::bareIfSingle($items);
    }

    /**
     * insert_statement ::= INSERT INTO aliased_or_qualified_name VALUES ( values_list )
     *                    | INSERT INTO aliased_or_qualified_name ( field_list ) VALUES ( values_list )
     * field_list ::= field_list COMMA field_item | field_item
     * field_item ::= IDENTIFIER
     * values_list ::= values_list COMMA value_item | value_item
     *
     * @return array<string, mixed>
     *
     * @throws Exception
     */
    private function parseInsertStatement(): array
    {
        $this->cursor->shift();
        $this->cursor->expect(Opcode::INTO->value);
        $qualifiedName = $this->parseQualifiedName();

        $fields = null;
        $length = 7;
        if ($this->cursor->is(Opcode::PARENTHESES_OPEN->value)) {
            $this->cursor->shift();
            $fields = [$this->parseFieldItem()];
            while ($this->cursor->is(Opcode::COMMA->value)) {
                $this->cursor->shift();
                $fields[] = $this->parseFieldItem();
                $this->cursor->reduce(3);
            }

            $this->cursor->expect(Opcode::PARENTHESES_CLOSE->value);
            $length = 10;
        }

        $this->cursor->expect(Opcode::VALUES->value);
        $this->cursor->expect(Opcode::PARENTHESES_OPEN->value);

        $values = [$this->expressions->parse()];
        while ($this->cursor->is(Opcode::COMMA->value)) {
            $this->cursor->shift();
            $values[] = $this->expressions->parse();
            $this->cursor->reduce(3);
        }

        $this->cursor->expect(Opcode::PARENTHESES_CLOSE->value);
        $this->cursor->reduce($length);

        return Ast::insertStatement($qualifiedName, $fields, $values);
    }

    /**
     * integer_or_placeholder ::= INTEGER | HINTEGER | NPLACEHOLDER
     *                          | SPLACEHOLDER | BPLACEHOLDER
     *
     * @return array<string, mixed>
     *
     * @throws Exception
     */
    private function parseIntegerOrPlaceholder(): array
    {
        $token = $this->cursor->current();

        return match ($token->opcode) {
            Opcode::INTEGER->value,
            Opcode::HINTEGER->value      => Ast::literal($this->cursor->shift()->opcode, $token->value),
            Opcode::NPLACEHOLDER->value,
            Opcode::SPLACEHOLDER->value,
            Opcode::BPLACEHOLDER->value  => Ast::placeholder($this->cursor->shift()->opcode, (string) $token->value),
            default                      => throw $this->cursor->syntaxError(),
        };
    }

    /**
     * join_clause ::= join_type aliased_or_qualified_name join_associated_name join_conditions
     * join_type ::= INNER JOIN | CROSS JOIN | LEFT OUTER JOIN | LEFT JOIN
     *             | RIGHT OUTER JOIN | RIGHT JOIN | FULL OUTER JOIN | FULL JOIN | JOIN
     * join_associated_name ::= AS IDENTIFIER | IDENTIFIER | .
     * join_conditions ::= ON expr | .
     *
     * @return array<string, mixed>
     *
     * @throws Exception
     *
     * @phpstan-impure
     */
    private function parseJoin(): array
    {
        $type = $this->parseJoinType();
        $name = $this->parseQualifiedName();

        $alias = null;
        if ($this->cursor->is(Opcode::AS->value)) {
            $this->cursor->shift();
            $alias = Ast::qualifiedName(null, (string) $this->cursor->expect(Opcode::IDENTIFIER->value)->value);
            $this->cursor->reduce(2);
        } elseif ($this->cursor->is(Opcode::IDENTIFIER->value)) {
            $alias = Ast::qualifiedName(null, (string) $this->cursor->shift()->value);
        } else {
            $this->cursor->reduce(0);
        }

        $conditions = $this->parseOptionalExpression(Opcode::ON->value);
        $this->cursor->reduce(4);

        return Ast::joinItem($type, $name, $alias, $conditions);
    }

    /**
     * join_list_or_null ::= join_list | .
     * join_list ::= join_list join_item | join_item
     *
     * @return array<array-key, mixed>|null
     *
     * @throws Exception
     */
    private function parseJoinListOrNull(): ?array
    {
        if (!$this->startsJoin()) {
            $this->cursor->reduce(0);

            return null;
        }

        $joins = [$this->parseJoin()];
        while ($this->startsJoin()) {
            $joins[] = $this->parseJoin();
            $this->cursor->reduce(2);
        }

        return Ast::bareIfSingle($joins);
    }

    /**
     * @throws Exception
     */
    private function parseJoinType(): int
    {
        $opcode = $this->cursor->shift()->opcode;
        if ($opcode === Opcode::JOIN->value) {
            return Opcode::INNERJOIN->value;
        }

        $length = 2;
        if (
            ($opcode === Opcode::LEFT->value || $opcode === Opcode::RIGHT->value || $opcode === Opcode::FULL->value)
            && $this->cursor->is(Opcode::OUTER->value)
        ) {
            $this->cursor->shift();
            $length = 3;
        }

        $this->cursor->expect(Opcode::JOIN->value);
        $this->cursor->reduce($length);

        return match ($opcode) {
            Opcode::INNER->value => Opcode::INNERJOIN->value,
            Opcode::CROSS->value => Opcode::CROSSJOIN->value,
            Opcode::LEFT->value  => Opcode::LEFTJOIN->value,
            Opcode::RIGHT->value => Opcode::RIGHTJOIN->value,
            default              => Opcode::FULLJOIN->value,
        };
    }

    /**
     * limit_clause ::= LIMIT integer_or_placeholder | .
     *
     * @return array<string, mixed>|null
     *
     * @throws Exception
     */
    private function parseLimitClause(): ?array
    {
        if (!$this->cursor->is(Opcode::LIMIT->value)) {
            $this->cursor->reduce(0);

            return null;
        }

        $this->cursor->shift();
        $number = $this->parseIntegerOrPlaceholder();
        $this->cursor->reduce(2);

        return Ast::limitClause($number, null);
    }

    /**
     * where_clause ::= WHERE expr | .  (and having_clause / join_conditions,
     * which have the same shape with HAVING / ON)
     *
     * @return array<string, mixed>|null
     *
     * @throws Exception
     */
    private function parseOptionalExpression(int $keyword): ?array
    {
        if (!$this->cursor->is($keyword)) {
            $this->cursor->reduce(0);

            return null;
        }

        $this->cursor->shift();
        $expression = $this->expressions->parse();
        $this->cursor->reduce(2);

        return $expression;
    }

    /**
     * order_clause ::= ORDER BY order_list | .
     * order_list ::= order_list COMMA order_item | order_item
     * order_item ::= expr | expr ASC | expr DESC
     *
     * @return array<array-key, mixed>|null
     *
     * @throws Exception
     */
    private function parseOrderClause(): ?array
    {
        if (!$this->cursor->is(Opcode::ORDER->value)) {
            $this->cursor->reduce(0);

            return null;
        }

        $this->cursor->shift();
        $this->cursor->expect(Opcode::BY->value);

        $items = [$this->parseOrderItem()];
        while ($this->cursor->is(Opcode::COMMA->value)) {
            $this->cursor->shift();
            $items[] = $this->parseOrderItem();
            $this->cursor->reduce(3);
        }

        $this->cursor->reduce(3);

        return Ast::bareIfSingle($items);
    }

    /**
     * @return array<string, mixed>
     *
     * @throws Exception
     */
    private function parseOrderItem(): array
    {
        $column = $this->expressions->parse();

        $sort = $this->cursor->current()->opcode;
        if ($sort !== Opcode::ASC->value && $sort !== Opcode::DESC->value) {
            return Ast::orderItem($column, 0);
        }

        $this->cursor->shift();
        $this->cursor->reduce(2);

        return Ast::orderItem($column, $sort);
    }

    /**
     * qualified_name ::= IDENTIFIER DOT IDENTIFIER | IDENTIFIER
     *
     * @return array<string, mixed>
     *
     * @throws Exception
     */
    private function parseQualifiedName(): array
    {
        $first = $this->cursor->expect(Opcode::IDENTIFIER->value);
        if (!$this->cursor->is(Opcode::DOT->value)) {
            return Ast::qualifiedName(null, (string) $first->value);
        }

        $this->cursor->shift();
        $name = $this->cursor->expect(Opcode::IDENTIFIER->value);
        $this->cursor->reduce(3);

        return Ast::qualifiedName((string) $first->value, (string) $name->value);
    }

    /**
     * select_clause ::= SELECT distinct_all column_list FROM associated_name_list join_list_or_null
     * distinct_all ::= DISTINCT | ALL | .
     * column_list ::= column_list COMMA column_item | column_item
     *
     * @return array<string, mixed>
     *
     * @throws Exception
     */
    private function parseSelectClause(): array
    {
        $this->cursor->expect(Opcode::SELECT->value);

        $distinct = null;
        if ($this->cursor->is(Opcode::DISTINCT->value)) {
            $this->cursor->shift();
            $distinct = 1;
        } elseif ($this->cursor->is(Opcode::ALL->value)) {
            $this->cursor->shift();
            $distinct = 0;
        } else {
            $this->cursor->reduce(0);
        }

        $columns = [$this->parseColumnItem()];
        while ($this->cursor->is(Opcode::COMMA->value)) {
            $this->cursor->shift();
            $columns[] = $this->parseColumnItem();
            $this->cursor->reduce(3);
        }

        $this->cursor->expect(Opcode::FROM->value);
        $tables = $this->parseAssociatedNameList();
        $joins  = $this->parseJoinListOrNull();
        $this->cursor->reduce(6);

        return Ast::selectClause($distinct, $columns, $tables, $joins);
    }

    /**
     * select_limit_clause ::= LIMIT integer_or_placeholder
     *                       | LIMIT integer_or_placeholder COMMA integer_or_placeholder
     *                       | LIMIT integer_or_placeholder OFFSET integer_or_placeholder
     *                       | .
     *
     * `LIMIT a, b` is offset a, number b.
     *
     * @return array<string, mixed>|null
     *
     * @throws Exception
     */
    private function parseSelectLimitClause(): ?array
    {
        if (!$this->cursor->is(Opcode::LIMIT->value)) {
            $this->cursor->reduce(0);

            return null;
        }

        $this->cursor->shift();
        $first = $this->parseIntegerOrPlaceholder();

        if ($this->cursor->is(Opcode::COMMA->value)) {
            $this->cursor->shift();
            $number = $this->parseIntegerOrPlaceholder();
            $this->cursor->reduce(4);

            return Ast::limitClause($number, $first);
        }

        if ($this->cursor->is(Opcode::OFFSET->value)) {
            $this->cursor->shift();
            $offset = $this->parseIntegerOrPlaceholder();
            $this->cursor->reduce(4);

            return Ast::limitClause($first, $offset);
        }

        $this->cursor->reduce(2);

        return Ast::limitClause($first, null);
    }

    /**
     * @return array<string, mixed>
     *
     * @throws Exception
     */
    private function parseUpdateItem(): array
    {
        $column = $this->parseQualifiedName();
        $this->cursor->expect(Opcode::EQUALS->value);
        $value = $this->expressions->parse();
        $this->cursor->reduce(3);

        return Ast::updateItem($column, $value);
    }

    /**
     * update_statement ::= update_clause where_clause limit_clause
     * update_clause ::= UPDATE associated_name join_list_or_null SET update_item_list
     * update_item_list ::= update_item_list COMMA update_item | update_item
     * update_item ::= qualified_name EQUALS new_value
     *
     * @return array<string, mixed>
     *
     * @throws Exception
     */
    private function parseUpdateStatement(): array
    {
        $this->cursor->shift();
        $tables = $this->parseAssociatedName();
        $joins  = $this->parseJoinListOrNull();
        $this->cursor->expect(Opcode::SET->value);

        $items = [$this->parseUpdateItem()];
        while ($this->cursor->is(Opcode::COMMA->value)) {
            $this->cursor->shift();
            $items[] = $this->parseUpdateItem();
            $this->cursor->reduce(3);
        }

        $this->cursor->reduce(5);

        $where = $this->parseOptionalExpression(Opcode::WHERE->value);
        $limit = $this->parseLimitClause();
        $this->cursor->reduce(3);

        return Ast::updateStatement(Ast::updateClause($tables, $joins, Ast::bareIfSingle($items)), $where, $limit);
    }

    /**
     * with_item ::= IDENTIFIER
     *
     * @return array<string, mixed>
     *
     * @throws Exception
     */
    private function parseWithItem(): array
    {
        return Ast::qualifiedName(null, (string) $this->cursor->expect(Opcode::IDENTIFIER->value)->value);
    }

    /**
     * PARENTHESES_OPEN with_list PARENTHESES_CLOSE
     * with_list ::= with_list COMMA with_item | with_item
     *
     * @return array<array-key, mixed>
     *
     * @throws Exception
     */
    private function parseWithList(): array
    {
        $this->cursor->shift();

        $items = [$this->parseWithItem()];
        while ($this->cursor->is(Opcode::COMMA->value)) {
            $this->cursor->shift();
            $items[] = $this->parseWithItem();
            $this->cursor->reduce(3);
        }

        $this->cursor->expect(Opcode::PARENTHESES_CLOSE->value);

        return Ast::bareIfSingle($items);
    }

    private function startsJoin(): bool
    {
        $opcode = $this->cursor->current()->opcode;

        return $opcode === Opcode::JOIN->value
            || $opcode === Opcode::INNER->value
            || $opcode === Opcode::CROSS->value
            || $opcode === Opcode::LEFT->value
            || $opcode === Opcode::RIGHT->value
            || $opcode === Opcode::FULL->value;
    }
}
