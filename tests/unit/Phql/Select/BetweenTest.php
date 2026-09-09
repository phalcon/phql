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

final class BetweenTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-04-09
     */
    public function testMvcModelQueryPhqlSelectBetweenFloat(): void
    {
        $source   = "SELECT * "
            . "FROM Invoices "
            . "WHERE inv_total BETWEEN 10.00 AND 500.00";
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
                'type'  => Opcode::BETWEEN->value,
                'left'  => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'inv_total',
                ],
                'right' => [
                    'type'  => Opcode::AND->value,
                    'left'  => [
                        'type'  => Opcode::DOUBLE->value,
                        'value' => '10.00',
                    ],
                    'right' => [
                        'type'  => Opcode::DOUBLE->value,
                        'value' => '500.00',
                    ],
                ],
            ],
        ];
        $actual   = (new Parser())->parse($source);
        $this->assertSame($expected, $actual);
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @issue  2
     * @since  2026-04-11
     */
    public function testMvcModelQueryPhqlSelectBetweenIdentifiers(): void
    {
        $source   = "SELECT column_name "
            . "FROM table_name "
            . "WHERE column_name BETWEEN value1 AND value2";
        $expected = [
            'type'   => Opcode::SELECT->value,
            'select' => [
                'columns' => [
                    0 => [
                        'type'   => Opcode::EXPR->value,
                        'column' => [
                            'type' => Opcode::QUALIFIED->value,
                            'name' => 'column_name',
                        ],
                    ],
                ],
                'tables'  => [
                    'qualifiedName' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'table_name',
                    ],
                ],
            ],
            'where'  => [
                'type'  => Opcode::BETWEEN->value,
                'left'  => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'column_name',
                ],
                'right' => [
                    'type'  => Opcode::AND->value,
                    'left'  => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'value1',
                    ],
                    'right' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'value2',
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
    public function testMvcModelQueryPhqlSelectBetweenInt(): void
    {
        $source   = "SELECT * "
            . "FROM Invoices "
            . "WHERE inv_id BETWEEN 1 AND 100";
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
                'type'  => Opcode::BETWEEN->value,
                'left'  => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'inv_id',
                ],
                'right' => [
                    'type'  => Opcode::AND->value,
                    'left'  => [
                        'type'  => Opcode::INTEGER->value,
                        'value' => '1',
                    ],
                    'right' => [
                        'type'  => Opcode::INTEGER->value,
                        'value' => '100',
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
    public function testMvcModelQueryPhqlSelectNotBetweenFloat(): void
    {
        $source   = "SELECT * "
            . "FROM Invoices "
            . "WHERE inv_total NOT BETWEEN 10.00 AND 500.00";
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
                'type'  => Opcode::BETWEEN_NOT->value,
                'left'  => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'inv_total',
                ],
                'right' => [
                    'type'  => Opcode::AND->value,
                    'left'  => [
                        'type'  => Opcode::DOUBLE->value,
                        'value' => '10.00',
                    ],
                    'right' => [
                        'type'  => Opcode::DOUBLE->value,
                        'value' => '500.00',
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
    public function testMvcModelQueryPhqlSelectNotBetweenInt(): void
    {
        $source   = "SELECT * "
            . "FROM Invoices "
            . "WHERE inv_id NOT BETWEEN 1 AND 100";
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
                'type'  => Opcode::BETWEEN_NOT->value,
                'left'  => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'inv_id',
                ],
                'right' => [
                    'type'  => Opcode::AND->value,
                    'left'  => [
                        'type'  => Opcode::INTEGER->value,
                        'value' => '1',
                    ],
                    'right' => [
                        'type'  => Opcode::INTEGER->value,
                        'value' => '100',
                    ],
                ],
            ],
        ];
        $actual   = (new Parser())->parse($source);
        $this->assertSame($expected, $actual);
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-04-11
     */
    public function testMvcModelQueryPhqlSelectNotBetweenIntMultipleColumns(): void
    {
        $source   = "SELECT Id, ProductName, UnitPrice "
            . "FROM Product "
            . "WHERE UnitPrice NOT BETWEEN 5 AND 100";
        $expected = [
            'type'   => Opcode::SELECT->value,
            'select' => [
                'columns' => [
                    0 => [
                        'type'   => Opcode::EXPR->value,
                        'column' => [
                            'type' => Opcode::QUALIFIED->value,
                            'name' => 'Id',
                        ],
                    ],
                    1 => [
                        'type'   => Opcode::EXPR->value,
                        'column' => [
                            'type' => Opcode::QUALIFIED->value,
                            'name' => 'ProductName',
                        ],
                    ],
                    2 => [
                        'type'   => Opcode::EXPR->value,
                        'column' => [
                            'type' => Opcode::QUALIFIED->value,
                            'name' => 'UnitPrice',
                        ],
                    ],
                ],
                'tables'  => [
                    'qualifiedName' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Product',
                    ],
                ],
            ],
            'where'  => [
                'type'  => Opcode::BETWEEN_NOT->value,
                'left'  => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'UnitPrice',
                ],
                'right' => [
                    'type'  => Opcode::AND->value,
                    'left'  => [
                        'type'  => Opcode::INTEGER->value,
                        'value' => '5',
                    ],
                    'right' => [
                        'type'  => Opcode::INTEGER->value,
                        'value' => '100',
                    ],
                ],
            ],
        ];
        $actual   = (new Parser())->parse($source);
        $this->assertSame($expected, $actual);
    }
}
