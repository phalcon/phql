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

final class ComplexTest extends AbstractUnitTestCase
{
    /**
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-04-09
     */
    public function testMvcModelQueryPhqlSelectAllWhereAndInAndBetweenOrderByLimitOffset(): void
    {
        $source   = "SELECT * "
            . "FROM Invoices "
            . "WHERE inv_cst_id = :cstId: "
            . "AND inv_status_flag IN (0, 1) "
            . "AND inv_total BETWEEN :min: AND :max: "
            . "ORDER BY inv_created_at DESC "
            . "LIMIT :limit: "
            . "OFFSET :offset:";
        $expected = [
            'type' => Opcode::SELECT->value,
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
            'where'   => [
                'type' => Opcode::BETWEEN->value,
                'left'  => [
                    'type' => Opcode::EQUALS->value,
                    'left'  => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'inv_cst_id',
                    ],
                    'right' => [
                        'type' => Opcode::AND->value,
                        'left'  => [
                            'type' => Opcode::AND->value,
                            'left'  => [
                                'type' => Opcode::SPLACEHOLDER->value,
                                'value' => 'cstId',
                            ],
                            'right' => [
                                'type' => Opcode::IN->value,
                                'left'  => [
                                    'type' => Opcode::QUALIFIED->value,
                                    'name' => 'inv_status_flag',
                                ],
                                'right' => [
                                    0 => [
                                        'type' => Opcode::INTEGER->value,
                                        'value' => '0',
                                    ],
                                    1 => [
                                        'type' => Opcode::INTEGER->value,
                                        'value' => '1',
                                    ],
                                ],
                            ],
                        ],
                        'right' => [
                            'type' => Opcode::QUALIFIED->value,
                            'name' => 'inv_total',
                        ],
                    ],
                ],
                'right' => [
                    'type' => Opcode::AND->value,
                    'left'  => [
                        'type' => Opcode::SPLACEHOLDER->value,
                        'value' => 'min',
                    ],
                    'right' => [
                        'type' => Opcode::SPLACEHOLDER->value,
                        'value' => 'max',
                    ],
                ],
            ],
            'orderBy' => [
                'column' => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'inv_created_at',
                ],
                'sort'   => 328,
            ],
            'limit'   => [
                'number' => [
                    'type' => Opcode::SPLACEHOLDER->value,
                    'value' => 'limit',
                ],
                'offset' => [
                    'type' => Opcode::SPLACEHOLDER->value,
                    'value' => 'offset',
                ],
            ],
        ];
        $actual   = (new Parser())->parse($source);
        $this->assertSame($expected, $actual);
    }

    /**
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-04-09
     */
    public function testMvcModelQueryPhqlSelectCountFieldWhereGroupByOrderBy(): void
    {
        $source   = "SELECT COUNT(*) AS cnt, inv_status_flag "
            . "FROM Invoices "
            . "WHERE inv_created_at IS NOT NULL "
            . "GROUP BY inv_status_flag "
            . "ORDER BY cnt DESC";
        $expected = [
            'type' => Opcode::SELECT->value,
            'select'  => [
                'columns' => [
                    0 => [
                        'type' => Opcode::EXPR->value,
                        'column' => [
                            'type' => Opcode::FCALL->value,
                            'name'      => 'COUNT',
                            'arguments' => [
                                0 => [
                                    'type' => Opcode::STARALL->value,
                                ],
                            ],
                        ],
                        'alias'  => 'cnt',
                    ],
                    1 => [
                        'type' => Opcode::EXPR->value,
                        'column' => [
                            'type' => Opcode::QUALIFIED->value,
                            'name' => 'inv_status_flag',
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
            'where'   => [
                'type' => Opcode::ISNOTNULL->value,
                'left' => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'inv_created_at',
                ],
            ],
            'orderBy' => [
                'column' => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'cnt',
                ],
                'sort'   => 328,
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
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-04-09
     */
    public function testMvcModelQueryPhqlSelectSumFieldWhereGroupByHavingOrderByLimit(): void
    {
        $source   = "SELECT i.inv_id, i.inv_title, SUM(i.inv_total) AS total "
            . "FROM Invoices AS i "
            . "WHERE i.inv_status_flag = 1 "
            . "GROUP BY i.inv_cst_id "
            . "HAVING SUM(i.inv_total) > 500 "
            . "ORDER BY total DESC "
            . "LIMIT 10";
        $expected = [
            'type' => Opcode::SELECT->value,
            'select'  => [
                'columns' => [
                    0 => [
                        'type' => Opcode::EXPR->value,
                        'column' => [
                            'type' => Opcode::QUALIFIED->value,
                            'domain' => 'i',
                            'name'   => 'inv_id',
                        ],
                    ],
                    1 => [
                        'type' => Opcode::EXPR->value,
                        'column' => [
                            'type' => Opcode::QUALIFIED->value,
                            'domain' => 'i',
                            'name'   => 'inv_title',
                        ],
                    ],
                    2 => [
                        'type' => Opcode::EXPR->value,
                        'column' => [
                            'type' => Opcode::FCALL->value,
                            'name'      => 'SUM',
                            'arguments' => [
                                0 => [
                                    'type' => Opcode::QUALIFIED->value,
                                    'domain' => 'i',
                                    'name'   => 'inv_total',
                                ],
                            ],
                        ],
                        'alias'  => 'total',
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
            'where'   => [
                'type' => Opcode::EQUALS->value,
                'left'  => [
                    'type' => Opcode::QUALIFIED->value,
                    'domain' => 'i',
                    'name'   => 'inv_status_flag',
                ],
                'right' => [
                    'type' => Opcode::INTEGER->value,
                    'value' => '1',
                ],
            ],
            'orderBy' => [
                'column' => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'total',
                ],
                'sort'   => 328,
            ],
            'groupBy' => [
                'type' => Opcode::QUALIFIED->value,
                'domain' => 'i',
                'name'   => 'inv_cst_id',
            ],
            'having'  => [
                'type' => Opcode::GREATER->value,
                'left'  => [
                    'type' => Opcode::FCALL->value,
                    'name'      => 'SUM',
                    'arguments' => [
                        0 => [
                            'type' => Opcode::QUALIFIED->value,
                            'domain' => 'i',
                            'name'   => 'inv_total',
                        ],
                    ],
                ],
                'right' => [
                    'type' => Opcode::INTEGER->value,
                    'value' => '500',
                ],
            ],
            'limit'   => [
                'number' => [
                    'type' => Opcode::INTEGER->value,
                    'value' => '10',
                ],
            ],
        ];
        $actual   = (new Parser())->parse($source);
        $this->assertSame($expected, $actual);
    }
}
