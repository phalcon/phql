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

final class OperatorsTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-04-09
     */
    public function testMvcModelQueryPhqlSelectAddition(): void
    {
        $source   = "SELECT inv_total + 10 FROM Invoices";
        $expected = [
            'type'   => Opcode::SELECT->value,
            'select' => [
                'columns' => [
                    0 => [
                        'type'   => Opcode::EXPR->value,
                        'column' => [
                            'type'  => Opcode::ADD->value,
                            'left'  => [
                                'type' => Opcode::QUALIFIED->value,
                                'name' => 'inv_total',
                            ],
                            'right' => [
                                'type'  => Opcode::INTEGER->value,
                                'value' => '10',
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
    public function testMvcModelQueryPhqlSelectDivision(): void
    {
        $source   = "SELECT inv_total / 2 FROM Invoices";
        $expected = [
            'type'   => Opcode::SELECT->value,
            'select' => [
                'columns' => [
                    0 => [
                        'type'   => Opcode::EXPR->value,
                        'column' => [
                            'type'  => Opcode::DIV->value,
                            'left'  => [
                                'type' => Opcode::QUALIFIED->value,
                                'name' => 'inv_total',
                            ],
                            'right' => [
                                'type'  => Opcode::INTEGER->value,
                                'value' => '2',
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
    public function testMvcModelQueryPhqlSelectModulo(): void
    {
        $source   = "SELECT inv_total % 3 FROM Invoices";
        $expected = [
            'type'   => Opcode::SELECT->value,
            'select' => [
                'columns' => [
                    0 => [
                        'type'   => Opcode::EXPR->value,
                        'column' => [
                            'type'  => Opcode::MOD->value,
                            'left'  => [
                                'type' => Opcode::QUALIFIED->value,
                                'name' => 'inv_total',
                            ],
                            'right' => [
                                'type'  => Opcode::INTEGER->value,
                                'value' => '3',
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
    public function testMvcModelQueryPhqlSelectMultiplication(): void
    {
        $source   = "SELECT inv_total * 1.1 FROM Invoices";
        $expected = [
            'type'   => Opcode::SELECT->value,
            'select' => [
                'columns' => [
                    0 => [
                        'type'   => Opcode::EXPR->value,
                        'column' => [
                            'type'  => Opcode::MUL->value,
                            'left'  => [
                                'type' => Opcode::QUALIFIED->value,
                                'name' => 'inv_total',
                            ],
                            'right' => [
                                'type'  => Opcode::DOUBLE->value,
                                'value' => '1.1',
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
    public function testMvcModelQueryPhqlSelectMultiplicationAliasAs(): void
    {
        $source   = "SELECT inv_id, inv_total * 1.1 AS total_with_tax FROM Invoices";
        $expected = [
            'type'   => Opcode::SELECT->value,
            'select' => [
                'columns' => [
                    0 => [
                        'type'   => Opcode::EXPR->value,
                        'column' => [
                            'type' => Opcode::QUALIFIED->value,
                            'name' => 'inv_id',
                        ],
                    ],
                    1 => [
                        'type'   => Opcode::EXPR->value,
                        'column' => [
                            'type'  => Opcode::MUL->value,
                            'left'  => [
                                'type' => Opcode::QUALIFIED->value,
                                'name' => 'inv_total',
                            ],
                            'right' => [
                                'type'  => Opcode::DOUBLE->value,
                                'value' => '1.1',
                            ],
                        ],
                        'alias'  => 'total_with_tax',
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
    public function testMvcModelQueryPhqlSelectSelectAdditionMultiplicationAliasAs(): void
    {
        $source   = "SELECT inv_id, (inv_total + 5) * 2 AS adjusted FROM Invoices";
        $expected = [
            'type'   => Opcode::SELECT->value,
            'select' => [
                'columns' => [
                    0 => [
                        'type'   => Opcode::EXPR->value,
                        'column' => [
                            'type' => Opcode::QUALIFIED->value,
                            'name' => 'inv_id',
                        ],
                    ],
                    1 => [
                        'type'   => Opcode::EXPR->value,
                        'column' => [
                            'type'  => Opcode::MUL->value,
                            'left'  => [
                                'type' => Opcode::ENCLOSED->value,
                                'left' => [
                                    'type'  => Opcode::ADD->value,
                                    'left'  => [
                                        'type' => Opcode::QUALIFIED->value,
                                        'name' => 'inv_total',
                                    ],
                                    'right' => [
                                        'type'  => Opcode::INTEGER->value,
                                        'value' => '5',
                                    ],
                                ],
                            ],
                            'right' => [
                                'type'  => Opcode::INTEGER->value,
                                'value' => '2',
                            ],
                        ],
                        'alias'  => 'adjusted',
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
    public function testMvcModelQueryPhqlSelectSubtraction(): void
    {
        $source   = "SELECT inv_total - 5 FROM Invoices";
        $expected = [
            'type'   => Opcode::SELECT->value,
            'select' => [
                'columns' => [
                    0 => [
                        'type'   => Opcode::EXPR->value,
                        'column' => [
                            'type'  => Opcode::SUB->value,
                            'left'  => [
                                'type' => Opcode::QUALIFIED->value,
                                'name' => 'inv_total',
                            ],
                            'right' => [
                                'type'  => Opcode::INTEGER->value,
                                'value' => '5',
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
}
