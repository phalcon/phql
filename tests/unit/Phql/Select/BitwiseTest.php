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

final class BitwiseTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-04-09
     */
    public function testMvcModelQueryPhqlSelectBitwiseAnd(): void
    {
        $source   = "SELECT * "
            . "FROM Invoices "
            . "WHERE inv_status_flag & 1 = 1";
        $expected = [
            'type'   => Opcode::SELECT->value,
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
                'type'  => Opcode::EQUALS->value,
                'left'  => [
                    'type'  => Opcode::BITWISE_AND->value,
                    'left'  => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'inv_status_flag',
                    ],
                    'right' => [
                        'type'  => Opcode::INTEGER->value,
                        'value' => '1',
                    ],
                ],
                'right' => [
                    'type'  => Opcode::INTEGER->value,
                    'value' => '1',
                ],
            ],
        ];
        $actual   = (new Parser())->parse($source);
        $this->assertSame($expected, $actual);
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-04-09
     */
    public function testMvcModelQueryPhqlSelectBitwiseInField(): void
    {
        $source   = "SELECT inv_status_flag & 3 AS masked "
            . "FROM Invoices";
        $expected = [
            'type'   => Opcode::SELECT->value,
            'select' => [
                'columns' => [
                    0 => [
                        'type'   => Opcode::EXPR->value,
                        'column' => [
                            'type'  => Opcode::BITWISE_AND->value,
                            'left'  => [
                                'type' => Opcode::QUALIFIED->value,
                                'name' => 'inv_status_flag',
                            ],
                            'right' => [
                                'type'  => Opcode::INTEGER->value,
                                'value' => '3',
                            ],
                        ],
                        'alias'  => 'masked',
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

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-04-09
     */
    public function testMvcModelQueryPhqlSelectBitwiseNotField(): void
    {
        $source   = "SELECT ~inv_status_flag "
            . "FROM Invoices";
        $expected = [
            'type'   => Opcode::SELECT->value,
            'select' => [
                'columns' => [
                    0 => [
                        'type'   => Opcode::EXPR->value,
                        'column' => [
                            'type'  => Opcode::BITWISE_NOT->value,
                            'right' => [
                                'type' => Opcode::QUALIFIED->value,
                                'name' => 'inv_status_flag',
                            ],
                        ],
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

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-04-09
     */
    public function testMvcModelQueryPhqlSelectBitwiseOr(): void
    {
        $source   = "SELECT * "
            . "FROM Invoices "
            . "WHERE inv_status_flag | 2 = 3";
        $expected = [
            'type'   => Opcode::SELECT->value,
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
                'type'  => Opcode::EQUALS->value,
                'left'  => [
                    'type'  => Opcode::BITWISE_OR->value,
                    'left'  => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'inv_status_flag',
                    ],
                    'right' => [
                        'type'  => Opcode::INTEGER->value,
                        'value' => '2',
                    ],
                ],
                'right' => [
                    'type'  => Opcode::INTEGER->value,
                    'value' => '3',
                ],
            ],
        ];
        $actual   = (new Parser())->parse($source);
        $this->assertSame($expected, $actual);
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-04-09
     */
    public function testMvcModelQueryPhqlSelectBitwiseXor(): void
    {
        $source   = "SELECT * "
            . "FROM Invoices "
            . "WHERE inv_status_flag ^ 1 = 0";
        $expected = [
            'type'   => Opcode::SELECT->value,
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
                'type'  => Opcode::EQUALS->value,
                'left'  => [
                    'type'  => Opcode::BITWISE_XOR->value,
                    'left'  => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'inv_status_flag',
                    ],
                    'right' => [
                        'type'  => Opcode::INTEGER->value,
                        'value' => '1',
                    ],
                ],
                'right' => [
                    'type'  => Opcode::INTEGER->value,
                    'value' => '0',
                ],
            ],
        ];
        $actual   = (new Parser())->parse($source);
        $this->assertSame($expected, $actual);
    }
}
