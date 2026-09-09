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

final class FromTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-04-09
     */
    public function testMvcModelQueryPhqlSelectFromAliases(): void
    {
        $source   = "SELECT i.inv_id, c.name "
            . "FROM Invoices AS i, Customers AS c "
            . "WHERE i.inv_cst_id = c.id";
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
                            'type'   => Opcode::QUALIFIED->value,
                            'domain' => 'c',
                            'name'   => 'name',
                        ],
                    ],
                ],
                'tables'  => [
                    0 => [
                        'qualifiedName' => [
                            'type' => Opcode::QUALIFIED->value,
                            'name' => 'Invoices',
                        ],
                        'alias'         => 'i',
                    ],
                    1 => [
                        'qualifiedName' => [
                            'type' => Opcode::QUALIFIED->value,
                            'name' => 'Customers',
                        ],
                        'alias'         => 'c',
                    ],
                ],
            ],
            'where'  => [
                'type'  => Opcode::EQUALS->value,
                'left'  => [
                    'type'   => Opcode::QUALIFIED->value,
                    'domain' => 'i',
                    'name'   => 'inv_cst_id',
                ],
                'right' => [
                    'type'   => Opcode::QUALIFIED->value,
                    'domain' => 'c',
                    'name'   => 'id',
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
    public function testMvcModelQueryPhqlSelectFromMultipleTables(): void
    {
        $source   = "SELECT * FROM Invoices, Customers";
        $expected = [
            'type'   => Opcode::SELECT->value,
            'select' => [
                'columns' => [
                    0 => [
                        'type' => Opcode::STARALL->value,
                    ],
                ],
                'tables'  => [
                    0 => [
                        'qualifiedName' => [
                            'type' => Opcode::QUALIFIED->value,
                            'name' => 'Invoices',
                        ],
                    ],
                    1 => [
                        'qualifiedName' => [
                            'type' => Opcode::QUALIFIED->value,
                            'name' => 'Customers',
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
    public function testMvcModelQueryPhqlSelectFromMultipleTablesWhere(): void
    {
        $source   = "SELECT * FROM Invoices, Customers "
            . "WHERE Invoices.inv_cst_id = Customers.id";
        $expected = [
            'type'   => Opcode::SELECT->value,
            'select' => [
                'columns' => [
                    0 => [
                        'type' => Opcode::STARALL->value,
                    ],
                ],
                'tables'  => [
                    0 => [
                        'qualifiedName' => [
                            'type' => Opcode::QUALIFIED->value,
                            'name' => 'Invoices',
                        ],
                    ],
                    1 => [
                        'qualifiedName' => [
                            'type' => Opcode::QUALIFIED->value,
                            'name' => 'Customers',
                        ],
                    ],
                ],
            ],
            'where'  => [
                'type'  => Opcode::EQUALS->value,
                'left'  => [
                    'type'   => Opcode::QUALIFIED->value,
                    'domain' => 'Invoices',
                    'name'   => 'inv_cst_id',
                ],
                'right' => [
                    'type'   => Opcode::QUALIFIED->value,
                    'domain' => 'Customers',
                    'name'   => 'id',
                ],
            ],
        ];
        $actual   = (new Parser())->parse($source);
        $this->assertSame($expected, $actual);
    }
}
