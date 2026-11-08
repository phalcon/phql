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

namespace Phalcon\Phql\Tests\Unit\Parser;

use Phalcon\Phql\Parser;
use Phalcon\Phql\Scanner\Opcode;
use Phalcon\Phql\Tests\AbstractUnitTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

use function array_is_list;
use function array_map;
use function implode;

/**
 * Operator precedence as cphalcon's Lemon grammar resolves it, which is not
 * always what SQL readers expect: AND/OR bind tighter than `=`, `* / %` bind
 * looser than `+ -`, NOT LIKE / NOT ILIKE / NOT IN take NOT's (high,
 * right-associative) level, and unary minus takes binary minus's level.
 * Each expected tree was rendered from ext-phalcon 5.23.0's AST; the
 * precedence goldens pin the full ASTs.
 */
final class PrecedenceTest extends AbstractUnitTestCase
{
    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function expressionProvider(): array
    {
        $cases = [
            'a = 1 AND b > 0'                              => '(GREATER (EQUALS a (AND 1 b)) 0)',
            'a + b * c'                                    => '(MUL (ADD a b) c)',
            'a * b + c'                                    => '(MUL a (ADD b c))',
            'a + b IN (1)'                                 => '(ADD a (IN b (1)))',
            'a + b NOT LIKE c'                             => '(ADD a (NLIKE b c))',
            '- a + b'                                      => '(ADD (MINUS a) b)',
            '- a * b'                                      => '(MUL (MINUS a) b)',
            'NOT a = b'                                    => '(EQUALS (NOT a) b)',
            'a BETWEEN 1 AND 2'                            => '(BETWEEN a (AND 1 2))',
            'a IS NOT NULL = b'                            => '(EQUALS (ISNOTNULL a) b)',
            'a = b IS NULL'                                => '(EQUALS a (ISNULL b))',
            'a -> b IS NULL'                               => '(ISNULL (OP_JSON_GET a b))',
            'a OR b AND c'                                 => '(AND (OR a b) c)',
            'a AND b OR c'                                 => '(OR (AND a b) c)',
            'a & b | c'                                    => '(BITWISE_OR (BITWISE_AND a b) c)',
            'a || b + c'                                   => '(ADD (OP_CONCAT a b) c)',
            'a -> b ->> c'                                 => '(OP_JSON_GET_TEXT (OP_JSON_GET a b) c)',
            'a NOT LIKE b NOT LIKE c'                      => '(NLIKE a (NLIKE b c))',
            'a AGAINST b AGAINST c'                        => '(AGAINST a (AGAINST b c))',
            'a LIKE b LIKE c'                              => '(LIKE (LIKE a b) c)',
            'NOT a NOT LIKE b'                             => '(NOT (NLIKE a b))',
            '~ a + b'                                      => '(ADD (BITWISE_NOT a) b)',
            'a * - b + c'                                  => '(MUL a (ADD (MINUS b) c))',
            'a IN (1) IN (2)'                              => '(IN (IN a (1)) (2))',
            'a = b NOT IN (1)'                             => '(EQUALS a (NOTIN b (1)))',
            'a - - b'                                      => '(SUB a (MINUS b))',
            '1 - 2 - 3'                                    => '(SUB (SUB 1 2) 3)',
            '8 / 4 / 2'                                    => '(DIV (DIV 8 4) 2)',
            'a BETWEEN 1 + 2 AND 3 * 4'                    => '(BETWEEN a (AND (ADD 1 2) (MUL 3 4)))',
            'a NOT BETWEEN 1 AND 2 AND b'                  => '(BETWEEN_NOT a (AND (AND 1 2) b))',
            'a = b = c'                                    => '(EQUALS (EQUALS a b) c)',
            'a < b > c'                                    => '(GREATER (LESS a b) c)',
            'a AGAINST b = c'                              => '(AGAINST a (EQUALS b c))',
            'a @@ b AND c'                                 => '(OP_MATCHES a (AND b c))',
            'a % b * c'                                    => '(MUL (MOD a b) c)',
            'a / b ^ c'                                    => '(BITWISE_XOR (DIV a b) c)',
            'NOT NOT a'                                    => '(NOT (NOT a))',
            '- - a'                                        => '(MINUS (MINUS a))',
            'a IS NULL IS NOT NULL'                        => '(ISNOTNULL (ISNULL a))',
            'CASE a WHEN b + 1 THEN c * 2 ELSE d END + 5'  => '(ADD (CASE a ((WHEN (ADD b 1) (MUL c 2)) (ELSE d))) 5)',
            'NOT a IS NULL'                                => '(ISNULL (NOT a))',
            'a ILIKE b NOT ILIKE c'                        => '(ILIKE a (NILIKE b c))',
            '(a + b) * c'                                  => '(MUL (ENCLOSED (ADD a b)) c)',
            'a IN (SELECT x FROM s) = 1'                   => '(EQUALS (IN a (SELECT)) 1)',
        ];

        $provider = [];
        foreach ($cases as $expression => $tree) {
            $provider[$expression] = [$expression, $tree];
        }

        return $provider;
    }

    #[DataProvider('expressionProvider')]
    public function testPrecedence(string $expression, string $tree): void
    {
        $ast = (new Parser())->parse('SELECT ' . $expression . ' FROM r');

        $this->assertSame($tree, $this->sexpr($ast['select']['columns'][0]['column'] ?? []));
    }

    /**
     * @param array<array-key, mixed> $node
     */
    private function sexpr(array $node): string
    {
        if (array_is_list($node)) {
            return '(' . implode(' ', array_map(fn (array $item): string => $this->sexpr($item), $node)) . ')';
        }

        $type = Opcode::from($node['type']);

        return match ($type) {
            Opcode::QUALIFIED                                               => $node['name'],
            Opcode::INTEGER, Opcode::DOUBLE, Opcode::HINTEGER               => $node['value'],
            Opcode::NULL, Opcode::TRUE, Opcode::FALSE                       => $type->name,
            Opcode::SELECT                                                  => '(SELECT)',
            default                                                         => '(' . $type->name
                . (isset($node['left']) ? ' ' . $this->sexpr($node['left']) : '')
                . (isset($node['right']) ? ' ' . $this->sexpr($node['right']) : '') . ')',
        };
    }
}
