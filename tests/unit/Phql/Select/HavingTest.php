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

final class HavingTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-04-09
     */
    public function testMvcModelQueryPhqlSelectHavingCountAll(): void
    {
        $source   = "SELECT inv_status_flag, COUNT(*) AS cnt "
            . "FROM Invoices "
            . "GROUP BY inv_status_flag "
            . "HAVING COUNT(*) > 5";
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
                        'alias'  => 'cnt',
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
            'having'  => [
                'type'  => Opcode::GREATER->value,
                'left'  => [
                    'type'      => Opcode::FCALL->value,
                    'name'      => 'COUNT',
                    'arguments' => [
                        0 => [
                            'type' => Opcode::STARALL->value,
                        ],
                    ],
                ],
                'right' => [
                    'type'  => Opcode::INTEGER->value,
                    'value' => '5',
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
    public function testMvcModelQueryPhqlSelectHavingCountField(): void
    {
        $source   = "SELECT inv_cst_id, COUNT(*) AS cnt "
            . "FROM Invoices "
            . "GROUP BY inv_cst_id "
            . "HAVING cnt > 10";
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
                        'alias'  => 'cnt',
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
            'having'  => [
                'type'  => Opcode::GREATER->value,
                'left'  => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'cnt',
                ],
                'right' => [
                    'type'  => Opcode::INTEGER->value,
                    'value' => '10',
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
    public function testMvcModelQueryPhqlSelectHavingSum(): void
    {
        $source   = "SELECT inv_cst_id, SUM(inv_total) AS total "
            . "FROM Invoices "
            . "GROUP BY inv_cst_id "
            . "HAVING SUM(inv_total) > 1000";
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
                        'alias'  => 'total',
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
            'having'  => [
                'type'  => Opcode::GREATER->value,
                'left'  => [
                    'type'      => Opcode::FCALL->value,
                    'name'      => 'SUM',
                    'arguments' => [
                        0 => [
                            'type' => Opcode::QUALIFIED->value,
                            'name' => 'inv_total',
                        ],
                    ],
                ],
                'right' => [
                    'type'  => Opcode::INTEGER->value,
                    'value' => '1000',
                ],
            ],
        ];
        $actual   = (new Parser())->parse($source);
        $this->assertSame($expected, $actual);
    }
}
