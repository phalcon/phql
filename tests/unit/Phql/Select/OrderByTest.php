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

final class OrderByTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-04-10
     */
    public function testMvcModelQueryPhqlSelectOrderByAggregate(): void
    {
        $source   = "SELECT inv_cst_id, COUNT(*) FROM Invoices "
            . "GROUP BY inv_cst_id ORDER BY COUNT(*) DESC";
        $expected = [
            'type'    => Opcode::SELECT->value,
            'select'  => [
                'columns' => [
                    0 => [
                        'type'   => Opcode::EXPR->value,
                        'column' => [
                            'type' => Opcode::QUALIFIED->value,
                            'name' => 'inv_cst_id',
                        ],
                    ],
                    1 => [
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
            'orderBy' => [
                'column' => [
                    'type'      => Opcode::FCALL->value,
                    'name'      => 'COUNT',
                    'arguments' => [
                        0 => [
                            'type' => Opcode::STARALL->value,
                        ],
                    ],
                ],
                'sort'   => 328,
            ],
            'groupBy' => [
                'type' => Opcode::QUALIFIED->value,
                'name' => 'inv_cst_id',
            ],
        ];
        $actual   = (new Parser())->parse($source);
        $this->assertSame($expected, $actual);
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-04-09
     */
    public function testMvcModelQueryPhqlSelectOrderByDateDescIntAsc(): void
    {
        $source   = "SELECT * FROM Invoices ORDER BY inv_created_at DESC, inv_id ASC";
        $expected = [
            'type'    => Opcode::SELECT->value,
            'select'  => [
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
            'orderBy' => [
                0 => [
                    'column' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'inv_created_at',
                    ],
                    'sort'   => 328,
                ],
                1 => [
                    'column' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'inv_id',
                    ],
                    'sort'   => 327,
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
    public function testMvcModelQueryPhqlSelectOrderByFloatDescStringAscIntAsc(): void
    {
        $source   = "SELECT * FROM Invoices "
            . "ORDER BY inv_total DESC, inv_title ASC, inv_id ASC";
        $expected = [
            'type'    => Opcode::SELECT->value,
            'select'  => [
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
            'orderBy' => [
                0 => [
                    'column' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'inv_total',
                    ],
                    'sort'   => 328,
                ],
                1 => [
                    'column' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'inv_title',
                    ],
                    'sort'   => 327,
                ],
                2 => [
                    'column' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'inv_id',
                    ],
                    'sort'   => 327,
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
    public function testMvcModelQueryPhqlSelectOrderByInt(): void
    {
        $source   = "SELECT * FROM Invoices ORDER BY inv_id";
        $expected = [
            'type'    => Opcode::SELECT->value,
            'select'  => [
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
            'orderBy' => [
                'column' => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'inv_id',
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
    public function testMvcModelQueryPhqlSelectOrderByIntAsc(): void
    {
        $source   = "SELECT * FROM Invoices ORDER BY inv_id ASC";
        $expected = [
            'type'    => Opcode::SELECT->value,
            'select'  => [
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
            'orderBy' => [
                'column' => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'inv_id',
                ],
                'sort'   => 327,
            ],
        ];
        $actual   = (new Parser())->parse($source);
        $this->assertSame($expected, $actual);
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-04-09
     */
    public function testMvcModelQueryPhqlSelectOrderByIntDesc(): void
    {
        $source   = "SELECT * FROM Invoices ORDER BY inv_id DESC";
        $expected = [
            'type'    => Opcode::SELECT->value,
            'select'  => [
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
            'orderBy' => [
                'column' => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'inv_id',
                ],
                'sort'   => 328,
            ],
        ];
        $actual   = (new Parser())->parse($source);
        $this->assertSame($expected, $actual);
    }
}
