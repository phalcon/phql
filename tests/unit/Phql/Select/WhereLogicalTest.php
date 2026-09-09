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

final class WhereLogicalTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-04-09
     */
    public function testMvcModelQueryPhqlSelectWhereAnd(): void
    {
        $source   = "SELECT * FROM Invoices WHERE inv_status_flag = 1 AND inv_total > 0";
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
                'type'  => Opcode::GREATER->value,
                'left'  => [
                    'type'  => Opcode::EQUALS->value,
                    'left'  => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'inv_status_flag',
                    ],
                    'right' => [
                        'type'  => Opcode::AND->value,
                        'left'  => [
                            'type'  => Opcode::INTEGER->value,
                            'value' => '1',
                        ],
                        'right' => [
                            'type' => Opcode::QUALIFIED->value,
                            'name' => 'inv_total',
                        ],
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

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-04-09
     */
    public function testMvcModelQueryPhqlSelectWhereAndAnd(): void
    {
        $source   = "SELECT * FROM Invoices WHERE inv_cst_id = 1 AND inv_status_flag = 1 AND inv_total > 0";
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
                'type'  => Opcode::GREATER->value,
                'left'  => [
                    'type'  => Opcode::EQUALS->value,
                    'left'  => [
                        'type'  => Opcode::EQUALS->value,
                        'left'  => [
                            'type' => Opcode::QUALIFIED->value,
                            'name' => 'inv_cst_id',
                        ],
                        'right' => [
                            'type'  => Opcode::AND->value,
                            'left'  => [
                                'type'  => Opcode::INTEGER->value,
                                'value' => '1',
                            ],
                            'right' => [
                                'type' => Opcode::QUALIFIED->value,
                                'name' => 'inv_status_flag',
                            ],
                        ],
                    ],
                    'right' => [
                        'type'  => Opcode::AND->value,
                        'left'  => [
                            'type'  => Opcode::INTEGER->value,
                            'value' => '1',
                        ],
                        'right' => [
                            'type' => Opcode::QUALIFIED->value,
                            'name' => 'inv_total',
                        ],
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

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-04-09
     */
    public function testMvcModelQueryPhqlSelectWhereNot(): void
    {
        $source   = "SELECT * FROM Invoices WHERE NOT inv_status_flag = 0";
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
                    'type'  => Opcode::NOT->value,
                    'right' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'inv_status_flag',
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

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-04-09
     */
    public function testMvcModelQueryPhqlSelectWhereOr(): void
    {
        $source   = "SELECT * FROM Invoices WHERE inv_status_flag = 0 OR inv_status_flag = 1";
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
                    'type'  => Opcode::EQUALS->value,
                    'left'  => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'inv_status_flag',
                    ],
                    'right' => [
                        'type'  => Opcode::OR->value,
                        'left'  => [
                            'type'  => Opcode::INTEGER->value,
                            'value' => '0',
                        ],
                        'right' => [
                            'type' => Opcode::QUALIFIED->value,
                            'name' => 'inv_status_flag',
                        ],
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
    public function testMvcModelQueryPhqlSelectWhereParenthesesOrAnd(): void
    {
        $source   = "SELECT * FROM Invoices WHERE (inv_status_flag = 1 OR inv_status_flag = 2) AND inv_total > 0";
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
                'type'  => Opcode::GREATER->value,
                'left'  => [
                    'type'  => Opcode::AND->value,
                    'left'  => [
                        'type' => Opcode::ENCLOSED->value,
                        'left' => [
                            'type'  => Opcode::EQUALS->value,
                            'left'  => [
                                'type'  => Opcode::EQUALS->value,
                                'left'  => [
                                    'type' => Opcode::QUALIFIED->value,
                                    'name' => 'inv_status_flag',
                                ],
                                'right' => [
                                    'type'  => Opcode::OR->value,
                                    'left'  => [
                                        'type'  => Opcode::INTEGER->value,
                                        'value' => '1',
                                    ],
                                    'right' => [
                                        'type' => Opcode::QUALIFIED->value,
                                        'name' => 'inv_status_flag',
                                    ],
                                ],
                            ],
                            'right' => [
                                'type'  => Opcode::INTEGER->value,
                                'value' => '2',
                            ],
                        ],
                    ],
                    'right' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'inv_total',
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
