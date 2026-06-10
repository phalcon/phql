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

namespace Phalcon\Phql\Tests\Unit\Phql\Select;

use Phalcon\Phql\Parser;
use Phalcon\Phql\Scanner\Opcode;
use Phalcon\Phql\Tests\AbstractUnitTestCase;

final class CustomOperatorsTest extends AbstractUnitTestCase
{
    /**
     * @return array<array{string, Opcode}>
     */
    public static function getOperators(): array
    {
        return [
            ['@@', Opcode::OP_MATCHES],
            ['@>', Opcode::OP_CONTAINS],
            ['<@', Opcode::OP_CONTAINED],
            ['&&', Opcode::OP_OVERLAPS],
            ['||', Opcode::OP_CONCAT],
            ['->', Opcode::OP_JSON_GET],
            ['->>', Opcode::OP_JSON_GET_TEXT],
            ['#>', Opcode::OP_JSON_PATH],
            ['#>>', Opcode::OP_JSON_PATH_TEXT],
        ];
    }

    /**
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2026-06-10
     *
     * @dataProvider getOperators
     */
    public function testMvcModelQueryPhqlSelectCustomOperator(
        string $operator,
        Opcode $opcode
    ): void {
        $source   = "SELECT * "
            . "FROM Invoices "
            . "WHERE inv_title " . $operator . " 'x'";
        $expected = [
            'type' => Opcode::SELECT->value,
            'select' => [
                'columns' => [
                    0 => [
                        'type' => Opcode::STARALL->value,
                    ],
                ],
                'tables'  => [
                    'qualifiedName' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Invoices',
                    ],
                ],
            ],
            'where'  => [
                'type' => $opcode->value,
                'left'  => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'inv_title',
                ],
                'right' => [
                    'type' => Opcode::STRING->value,
                    'value' => 'x',
                ],
            ],
        ];
        $actual   = (new Parser())->parse($source);
        $this->assertSame($expected, $actual);
    }

    /**
     * The json accessors must bind tighter than '=':
     * inv_title ->> 'a' = 'b' parses as (inv_title ->> 'a') = 'b'
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-06-10
     */
    public function testMvcModelQueryPhqlSelectJsonGetTextBindsTighterThanEquals(): void
    {
        $source   = "SELECT * "
            . "FROM Invoices "
            . "WHERE inv_title ->> 'a' = 'b'";
        $expected = [
            'type' => Opcode::SELECT->value,
            'select' => [
                'columns' => [
                    0 => [
                        'type' => Opcode::STARALL->value,
                    ],
                ],
                'tables'  => [
                    'qualifiedName' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Invoices',
                    ],
                ],
            ],
            'where'  => [
                'type' => Opcode::EQUALS->value,
                'left'  => [
                    'type' => Opcode::OP_JSON_GET_TEXT->value,
                    'left'  => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'inv_title',
                    ],
                    'right' => [
                        'type' => Opcode::STRING->value,
                        'value' => 'a',
                    ],
                ],
                'right' => [
                    'type' => Opcode::STRING->value,
                    'value' => 'b',
                ],
            ],
        ];
        $actual   = (new Parser())->parse($source);
        $this->assertSame($expected, $actual);
    }

    /**
     * OP_CONCAT has PLUS/MINUS precedence, so it binds tighter than '='
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-06-10
     */
    public function testMvcModelQueryPhqlSelectConcatInsideComparison(): void
    {
        $source   = "SELECT * "
            . "FROM Invoices "
            . "WHERE inv_title || 'a' = 'b'";
        $expected = [
            'type' => Opcode::SELECT->value,
            'select' => [
                'columns' => [
                    0 => [
                        'type' => Opcode::STARALL->value,
                    ],
                ],
                'tables'  => [
                    'qualifiedName' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Invoices',
                    ],
                ],
            ],
            'where'  => [
                'type' => Opcode::EQUALS->value,
                'left'  => [
                    'type' => Opcode::OP_CONCAT->value,
                    'left'  => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'inv_title',
                    ],
                    'right' => [
                        'type' => Opcode::STRING->value,
                        'value' => 'a',
                    ],
                ],
                'right' => [
                    'type' => Opcode::STRING->value,
                    'value' => 'b',
                ],
            ],
        ];
        $actual   = (new Parser())->parse($source);
        $this->assertSame($expected, $actual);
    }

    /**
     * Custom operators are usable in the column list with an alias
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-06-10
     */
    public function testMvcModelQueryPhqlSelectJsonGetInColumnList(): void
    {
        $source   = "SELECT inv_meta -> 'currency' AS currency "
            . "FROM Invoices";
        $expected = [
            'type' => Opcode::SELECT->value,
            'select' => [
                'columns' => [
                    0 => [
                        'type' => Opcode::EXPR->value,
                        'column' => [
                            'type' => Opcode::OP_JSON_GET->value,
                            'left'  => [
                                'type' => Opcode::QUALIFIED->value,
                                'name' => 'inv_meta',
                            ],
                            'right' => [
                                'type' => Opcode::STRING->value,
                                'value' => 'currency',
                            ],
                        ],
                        'alias'  => 'currency',
                    ],
                ],
                'tables'  => [
                    'qualifiedName' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Invoices',
                    ],
                ],
            ],
        ];
        $actual   = (new Parser())->parse($source);
        $this->assertSame($expected, $actual);
    }
}
