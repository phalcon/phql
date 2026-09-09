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

final class SubqueriesTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-04-09
     */
    public function testMvcModelQueryPhqlSelectFieldSubquery(): void
    {
        $source   = "SELECT i.inv_id, "
            . "(SELECT COUNT(*) "
            . "FROM Invoices "
            . "WHERE inv_cst_id = i.inv_cst_id) AS cst_count "
            . "FROM Invoices i";
        $expected = [
            'type'   => Opcode::SELECT->value,
            'select' => [
                'columns' => [
                    0 => [
                        'type'   => Opcode::EXPR->value,
                        'column' => [
                            'type'   => Opcode::QUALIFIED->value,
                            'domain' => 'i',
                            'name'   => 'inv_id',
                        ],
                    ],
                    1 => [
                        'type'   => Opcode::EXPR->value,
                        'column' => [
                            'type' => Opcode::SUBQUERY->value,
                            'left' => [
                                'type'   => Opcode::SELECT->value,
                                'select' => [
                                    'columns' => [
                                        0 => [
                                            'type'   => Opcode::EXPR->value,
                                            'column' => [
                                                'type'      => Opcode::FCALL->value,
                                                'name'      => 'COUNT',
                                                'arguments' => [
                                                    0 => [
                                                        'type' => Opcode::STARALL->value,
                                                    ],
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
                                'where'  => [
                                    'type'  => Opcode::EQUALS->value,
                                    'left'  => [
                                        'type' => Opcode::QUALIFIED->value,
                                        'name' => 'inv_cst_id',
                                    ],
                                    'right' => [
                                        'type'   => Opcode::QUALIFIED->value,
                                        'domain' => 'i',
                                        'name'   => 'inv_cst_id',
                                    ],
                                ],
                            ],
                        ],
                        'alias'  => 'cst_count',
                    ],
                ],
                'tables'  => [
                    'qualifiedName' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Invoices',
                    ],
                    'alias'         => 'i',
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
    public function testMvcModelQueryPhqlSelectWhereEqualsSubquery(): void
    {
        $source   = "SELECT * "
            . "FROM Invoices "
            . "WHERE inv_total = (SELECT MAX(inv_total) FROM Invoices)";
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
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'inv_total',
                ],
                'right' => [
                    'type' => Opcode::SUBQUERY->value,
                    'left' => [
                        'type'   => Opcode::SELECT->value,
                        'select' => [
                            'columns' => [
                                0 => [
                                    'type'   => Opcode::EXPR->value,
                                    'column' => [
                                        'type'      => Opcode::FCALL->value,
                                        'name'      => 'MAX',
                                        'arguments' => [
                                            0 => [
                                                'type' => Opcode::QUALIFIED->value,
                                                'name' => 'inv_total',
                                            ],
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
    public function testMvcModelQueryPhqlSelectWhereExistsSubquery(): void
    {
        $source   = "SELECT * "
            . "FROM Invoices "
            . "WHERE EXISTS "
            . "(SELECT id FROM Customers WHERE id = Invoices.inv_cst_id)";
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
                'type'  => Opcode::EXISTS->value,
                'right' => [
                    'type'   => Opcode::SELECT->value,
                    'select' => [
                        'columns' => [
                            0 => [
                                'type'   => Opcode::EXPR->value,
                                'column' => [
                                    'type' => Opcode::QUALIFIED->value,
                                    'name' => 'id',
                                ],
                            ],
                        ],
                        'tables'  => [
                            'qualifiedName' => [
                                'type' => Opcode::QUALIFIED->value,
                                'name' => 'Customers',
                            ],
                        ],
                    ],
                    'where'  => [
                        'type'  => Opcode::EQUALS->value,
                        'left'  => [
                            'type' => Opcode::QUALIFIED->value,
                            'name' => 'id',
                        ],
                        'right' => [
                            'type'   => Opcode::QUALIFIED->value,
                            'domain' => 'Invoices',
                            'name'   => 'inv_cst_id',
                        ],
                    ],
                ],
            ],
        ];
        $actual   = (new Parser())->parse($source);
        $this->assertSame($expected, $actual);
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-04-10
     */
    public function testMvcModelQueryPhqlSelectWhereInNestedSubquery(): void
    {
        $source   = "SELECT * FROM Invoices "
            . "WHERE inv_cst_id IN "
            . "(SELECT id FROM Customers "
            . "WHERE id IN (SELECT cst_id FROM Orders WHERE status = 1))";
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
                'type'  => Opcode::IN->value,
                'left'  => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'inv_cst_id',
                ],
                'right' => [
                    'type'   => Opcode::SELECT->value,
                    'select' => [
                        'columns' => [
                            0 => [
                                'type'   => Opcode::EXPR->value,
                                'column' => [
                                    'type' => Opcode::QUALIFIED->value,
                                    'name' => 'id',
                                ],
                            ],
                        ],
                        'tables'  => [
                            'qualifiedName' => [
                                'type' => Opcode::QUALIFIED->value,
                                'name' => 'Customers',
                            ],
                        ],
                    ],
                    'where'  => [
                        'type'  => Opcode::IN->value,
                        'left'  => [
                            'type' => Opcode::QUALIFIED->value,
                            'name' => 'id',
                        ],
                        'right' => [
                            'type'   => Opcode::SELECT->value,
                            'select' => [
                                'columns' => [
                                    0 => [
                                        'type'   => Opcode::EXPR->value,
                                        'column' => [
                                            'type' => Opcode::QUALIFIED->value,
                                            'name' => 'cst_id',
                                        ],
                                    ],
                                ],
                                'tables'  => [
                                    'qualifiedName' => [
                                        'type' => Opcode::QUALIFIED->value,
                                        'name' => 'Orders',
                                    ],
                                ],
                            ],
                            'where'  => [
                                'type'  => Opcode::EQUALS->value,
                                'left'  => [
                                    'type' => Opcode::QUALIFIED->value,
                                    'name' => 'status',
                                ],
                                'right' => [
                                    'type'  => Opcode::INTEGER->value,
                                    'value' => '1',
                                ],
                            ],
                        ],
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
    public function testMvcModelQueryPhqlSelectWhereInSubquery(): void
    {
        $source   = "SELECT * "
            . "FROM Invoices "
            . "WHERE inv_cst_id IN "
            . "(SELECT id FROM Customers WHERE status = 1)";
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
                'type'  => Opcode::IN->value,
                'left'  => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'inv_cst_id',
                ],
                'right' => [
                    'type'   => Opcode::SELECT->value,
                    'select' => [
                        'columns' => [
                            0 => [
                                'type'   => Opcode::EXPR->value,
                                'column' => [
                                    'type' => Opcode::QUALIFIED->value,
                                    'name' => 'id',
                                ],
                            ],
                        ],
                        'tables'  => [
                            'qualifiedName' => [
                                'type' => Opcode::QUALIFIED->value,
                                'name' => 'Customers',
                            ],
                        ],
                    ],
                    'where'  => [
                        'type'  => Opcode::EQUALS->value,
                        'left'  => [
                            'type' => Opcode::QUALIFIED->value,
                            'name' => 'status',
                        ],
                        'right' => [
                            'type'  => Opcode::INTEGER->value,
                            'value' => '1',
                        ],
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
    public function testMvcModelQueryPhqlSelectWhereNotInSubquery(): void
    {
        $source   = "SELECT * "
            . "FROM Invoices "
            . "WHERE inv_cst_id NOT IN "
            . "(SELECT id FROM Customers WHERE status = 0)";
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
                'type'  => Opcode::NOTIN->value,
                'left'  => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'inv_cst_id',
                ],
                'right' => [
                    'type'   => Opcode::SELECT->value,
                    'select' => [
                        'columns' => [
                            0 => [
                                'type'   => Opcode::EXPR->value,
                                'column' => [
                                    'type' => Opcode::QUALIFIED->value,
                                    'name' => 'id',
                                ],
                            ],
                        ],
                        'tables'  => [
                            'qualifiedName' => [
                                'type' => Opcode::QUALIFIED->value,
                                'name' => 'Customers',
                            ],
                        ],
                    ],
                    'where'  => [
                        'type'  => Opcode::EQUALS->value,
                        'left'  => [
                            'type' => Opcode::QUALIFIED->value,
                            'name' => 'status',
                        ],
                        'right' => [
                            'type'  => Opcode::INTEGER->value,
                            'value' => '0',
                        ],
                    ],
                ],
            ],
        ];
        $actual   = (new Parser())->parse($source);
        $this->assertSame($expected, $actual);
    }
}
