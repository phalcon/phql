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

final class DistinctTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-04-09
     */
    public function testMvcModelQueryPhqlSelectAll(): void
    {
        $source   = "SELECT ALL inv_status_flag "
            . "FROM Invoices";
        $expected = [
            'type'   => Opcode::SELECT->value,
            'select' => [
                'distinct' => 0,
                'columns'  => [
                    0 => [
                        'type'   => Opcode::EXPR->value,
                        'column' => [
                            'type' => Opcode::QUALIFIED->value,
                            'name' => 'inv_status_flag',
                        ],
                    ],
                ],
                'tables'   => [
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
    public function testMvcModelQueryPhqlSelectDistinct(): void
    {
        $source   = "SELECT DISTINCT inv_status_flag "
            . "FROM Invoices";
        $expected = [
            'type'   => Opcode::SELECT->value,
            'select' => [
                'distinct' => 1,
                'columns'  => [
                    0 => [
                        'type'   => Opcode::EXPR->value,
                        'column' => [
                            'type' => Opcode::QUALIFIED->value,
                            'name' => 'inv_status_flag',
                        ],
                    ],
                ],
                'tables'   => [
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
    public function testMvcModelQueryPhqlSelectDistinctInt(): void
    {
        $source   = "SELECT DISTINCT inv_cst_id, inv_status_flag "
            . "FROM Invoices";
        $expected = [
            'type'   => Opcode::SELECT->value,
            'select' => [
                'distinct' => 1,
                'columns'  => [
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
                ],
                'tables'   => [
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
