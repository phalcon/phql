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

namespace Phalcon\Phql\Tests\Unit\Phql\Delete;

use Phalcon\Phql\Parser;
use Phalcon\Phql\Scanner\Opcode;
use Phalcon\Phql\Tests\AbstractUnitTestCase;

final class CombinationTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-04-09
     */
    public function testMvcModelQueryPhqlDelete(): void
    {
        $source   = "DELETE FROM Invoices";
        $expected = [
            'type'   => Opcode::DELETE->value,
            'delete' => [
                'tables' => [
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
    public function testMvcModelQueryPhqlDeleteAliasWhereEqZero(): void
    {
        $source   = "DELETE FROM Invoices AS i WHERE i.inv_status_flag = 0";
        $expected = [
            'type'   => Opcode::DELETE->value,
            'delete' => [
                'tables' => [
                    'qualifiedName' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Invoices',
                    ],
                    'alias'         => 'i',
                ],
            ],
            'where'  => [
                'type'  => Opcode::EQUALS->value,
                'left'  => [
                    'type'   => Opcode::QUALIFIED->value,
                    'domain' => 'i',
                    'name'   => 'inv_status_flag',
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
    public function testMvcModelQueryPhqlDeleteLimit(): void
    {
        $source   = "DELETE FROM Invoices LIMIT 10";
        $expected = [
            'type'   => Opcode::DELETE->value,
            'delete' => [
                'tables' => [
                    'qualifiedName' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Invoices',
                    ],
                ],
            ],
            'limit'  => [
                'number' => [
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
    public function testMvcModelQueryPhqlDeleteWhereEqNum(): void
    {
        $source   = "DELETE FROM Invoices WHERE inv_id = 1";
        $expected = [
            'type'   => Opcode::DELETE->value,
            'delete' => [
                'tables' => [
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
                    'name' => 'inv_id',
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
    public function testMvcModelQueryPhqlDeleteWhereEqPlaceholder(): void
    {
        $source   = "DELETE FROM Invoices WHERE inv_id = :id:";
        $expected = [
            'type'   => Opcode::DELETE->value,
            'delete' => [
                'tables' => [
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
                    'name' => 'inv_id',
                ],
                'right' => [
                    'type'  => Opcode::SPLACEHOLDER->value,
                    'value' => 'id',
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
    public function testMvcModelQueryPhqlDeleteWhereEqPlaceholderNumeric(): void
    {
        $source   = "DELETE FROM Invoices WHERE inv_id = ?0";
        $expected = [
            'type'   => Opcode::DELETE->value,
            'delete' => [
                'tables' => [
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
                    'name' => 'inv_id',
                ],
                'right' => [
                    'type'  => Opcode::NPLACEHOLDER->value,
                    'value' => '?0',
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
    public function testMvcModelQueryPhqlDeleteWhereEqZero(): void
    {
        $source   = "DELETE FROM Invoices WHERE inv_status_flag = 0";
        $expected = [
            'type'   => Opcode::DELETE->value,
            'delete' => [
                'tables' => [
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
                    'name' => 'inv_status_flag',
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
    public function testMvcModelQueryPhqlDeleteWhereIn(): void
    {
        $source   = "DELETE FROM Invoices WHERE inv_cst_id IN (1, 2, 3)";
        $expected = [
            'type'   => Opcode::DELETE->value,
            'delete' => [
                'tables' => [
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
                    0 => [
                        'type'  => Opcode::INTEGER->value,
                        'value' => '1',
                    ],
                    1 => [
                        'type'  => Opcode::INTEGER->value,
                        'value' => '2',
                    ],
                    2 => [
                        'type'  => Opcode::INTEGER->value,
                        'value' => '3',
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
    public function testMvcModelQueryPhqlDeleteWhereIsNull(): void
    {
        $source   = "DELETE FROM Invoices WHERE inv_created_at IS NULL";
        $expected = [
            'type'   => Opcode::DELETE->value,
            'delete' => [
                'tables' => [
                    'qualifiedName' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Invoices',
                    ],
                ],
            ],
            'where'  => [
                'type' => Opcode::ISNULL->value,
                'left' => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'inv_created_at',
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
    public function testMvcModelQueryPhqlDeleteWhereLtNumLimit(): void
    {
        $source   = "DELETE FROM Invoices WHERE inv_total < 0 LIMIT 3";
        $expected = [
            'type'   => Opcode::DELETE->value,
            'delete' => [
                'tables' => [
                    'qualifiedName' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Invoices',
                    ],
                ],
            ],
            'where'  => [
                'type'  => Opcode::LESS->value,
                'left'  => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'inv_total',
                ],
                'right' => [
                    'type'  => Opcode::INTEGER->value,
                    'value' => '0',
                ],
            ],
            'limit'  => [
                'number' => [
                    'type'  => Opcode::INTEGER->value,
                    'value' => '3',
                ],
            ],
        ];
        $actual   = (new Parser())->parse($source);
        $this->assertSame($expected, $actual);
    }
}
