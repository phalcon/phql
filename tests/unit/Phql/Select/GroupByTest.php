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

final class GroupByTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-04-09
     */
    public function testMvcModelQueryPhqlSelectGroupBy(): void
    {
        $source   = "SELECT inv_status_flag, COUNT(*) "
            . "FROM Invoices "
            . "GROUP BY inv_status_flag";
        $expected = [
            'type'    => Opcode::SELECT->value,
            'select'  => [
                'columns' => [
                    0 => [
                        'type'   => Opcode::EXPR->value,
                        'column' => [
                            'type' => Opcode::QUALIFIED->value,
                            'name' => 'inv_status_flag',
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
            'groupBy' => [
                'type' => Opcode::QUALIFIED->value,
                'name' => 'inv_status_flag',
            ],
        ];
        $actual   = (new Parser())->parse($source);
        $this->assertSame($expected, $actual);
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-04-09
     */
    public function testMvcModelQueryPhqlSelectGroupByCountField(): void
    {
        $source   = "SELECT inv_cst_id, inv_status_flag, COUNT(*) "
            . "FROM Invoices " .
                    "GROUP BY inv_cst_id, inv_status_flag";
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
                            'type' => Opcode::QUALIFIED->value,
                            'name' => 'inv_status_flag',
                        ],
                    ],
                    2 => [
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
            'groupBy' => [
                0 => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'inv_cst_id',
                ],
                1 => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'inv_status_flag',
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
    public function testMvcModelQueryPhqlSelectGroupBySumField(): void
    {
        $source   = "SELECT inv_cst_id, SUM(inv_total) "
            . "FROM Invoices "
            . "GROUP BY inv_cst_id";
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
                            'name'      => 'SUM',
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
            'groupBy' => [
                'type' => Opcode::QUALIFIED->value,
                'name' => 'inv_cst_id',
            ],
        ];
        $actual   = (new Parser())->parse($source);
        $this->assertSame($expected, $actual);
    }
}
