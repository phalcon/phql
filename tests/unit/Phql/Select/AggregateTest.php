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

final class AggregateTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-04-09
     */
    public function testMvcModelQueryPhqlSelectAvgField(): void
    {
        $source   = "SELECT AVG(inv_total) FROM Invoices";
        $expected = [
            'type'   => Opcode::SELECT->value,
            'select' => [
                'columns' => [
                    0 => [
                        'type'   => Opcode::EXPR->value,
                        'column' => [
                            'type'      => Opcode::FCALL->value,
                            'name'      => 'AVG',
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
        ];
        $actual   = (new Parser())->parse($source);
        $this->assertSame($expected, $actual);
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @issue  3
     * @since  2026-04-11
     */
    public function testMvcModelQueryPhqlSelectAvgFieldAliasFqcn(): void
    {
        $source   = "SELECT AVG(inv_total) AS average "
            . "FROM [Phalcon\\Tests\\Models\\Invoices]";
        $expected = [
            'type'   => Opcode::SELECT->value,
            'select' => [
                'columns' => [
                    0 => [
                        'type'   => Opcode::EXPR->value,
                        'column' => [
                            'type'      => Opcode::FCALL->value,
                            'name'      => 'AVG',
                            'arguments' => [
                                0 => [
                                    'type' => Opcode::QUALIFIED->value,
                                    'name' => 'inv_total',
                                ],
                            ],
                        ],
                        'alias'  => 'average',
                    ],
                ],
                'tables'  => [
                    'qualifiedName' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Phalcon\\Tests\\Models\\Invoices',
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
    public function testMvcModelQueryPhqlSelectCount(): void
    {
        $source   = "SELECT COUNT(*) FROM Invoices";
        $expected = [
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
        ];
        $actual   = (new Parser())->parse($source);
        $this->assertSame($expected, $actual);
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-04-09
     */
    public function testMvcModelQueryPhqlSelectCountDistinctField(): void
    {
        $source   = "SELECT COUNT(DISTINCT inv_cst_id) FROM Invoices";
        $expected = [
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
                                    'type' => Opcode::QUALIFIED->value,
                                    'name' => 'inv_cst_id',
                                ],
                            ],
                            'distinct'  => true,
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
    public function testMvcModelQueryPhqlSelectCountField(): void
    {
        $source   = "SELECT COUNT(inv_id) FROM Invoices";
        $expected = [
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
                                    'type' => Opcode::QUALIFIED->value,
                                    'name' => 'inv_id',
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
        ];
        $actual   = (new Parser())->parse($source);
        $this->assertSame($expected, $actual);
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-04-09
     */
    public function testMvcModelQueryPhqlSelectCountSumAvgMinMax(): void
    {
        $source   = "SELECT COUNT(*), SUM(inv_total), AVG(inv_total), MIN(inv_total), MAX(inv_total) FROM Invoices";
        $expected = [
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
                    2 => [
                        'type'   => Opcode::EXPR->value,
                        'column' => [
                            'type'      => Opcode::FCALL->value,
                            'name'      => 'AVG',
                            'arguments' => [
                                0 => [
                                    'type' => Opcode::QUALIFIED->value,
                                    'name' => 'inv_total',
                                ],
                            ],
                        ],
                    ],
                    3 => [
                        'type'   => Opcode::EXPR->value,
                        'column' => [
                            'type'      => Opcode::FCALL->value,
                            'name'      => 'MIN',
                            'arguments' => [
                                0 => [
                                    'type' => Opcode::QUALIFIED->value,
                                    'name' => 'inv_total',
                                ],
                            ],
                        ],
                    ],
                    4 => [
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
        ];
        $actual   = (new Parser())->parse($source);
        $this->assertSame($expected, $actual);
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-04-09
     */
    public function testMvcModelQueryPhqlSelectMaxDate(): void
    {
        $source   = "SELECT MAX(inv_created_at) FROM Invoices";
        $expected = [
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
                                    'name' => 'inv_created_at',
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
        ];
        $actual   = (new Parser())->parse($source);
        $this->assertSame($expected, $actual);
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-04-09
     */
    public function testMvcModelQueryPhqlSelectMaxField(): void
    {
        $source   = "SELECT MAX(inv_total) FROM Invoices";
        $expected = [
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
        ];
        $actual   = (new Parser())->parse($source);
        $this->assertSame($expected, $actual);
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-04-09
     */
    public function testMvcModelQueryPhqlSelectMinDate(): void
    {
        $source   = "SELECT MIN(inv_created_at) FROM Invoices";
        $expected = [
            'type'   => Opcode::SELECT->value,
            'select' => [
                'columns' => [
                    0 => [
                        'type'   => Opcode::EXPR->value,
                        'column' => [
                            'type'      => Opcode::FCALL->value,
                            'name'      => 'MIN',
                            'arguments' => [
                                0 => [
                                    'type' => Opcode::QUALIFIED->value,
                                    'name' => 'inv_created_at',
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
        ];
        $actual   = (new Parser())->parse($source);
        $this->assertSame($expected, $actual);
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-04-09
     */
    public function testMvcModelQueryPhqlSelectMinField(): void
    {
        $source   = "SELECT MIN(inv_total) FROM Invoices";
        $expected = [
            'type'   => Opcode::SELECT->value,
            'select' => [
                'columns' => [
                    0 => [
                        'type'   => Opcode::EXPR->value,
                        'column' => [
                            'type'      => Opcode::FCALL->value,
                            'name'      => 'MIN',
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
        ];
        $actual   = (new Parser())->parse($source);
        $this->assertSame($expected, $actual);
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-04-09
     */
    public function testMvcModelQueryPhqlSelectSumField(): void
    {
        $source   = "SELECT SUM(inv_total) FROM Invoices";
        $expected = [
            'type'   => Opcode::SELECT->value,
            'select' => [
                'columns' => [
                    0 => [
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
        ];
        $actual   = (new Parser())->parse($source);
        $this->assertSame($expected, $actual);
    }
}
