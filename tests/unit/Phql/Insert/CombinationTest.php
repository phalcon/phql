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

namespace Phalcon\Phql\Tests\Unit\Phql\Insert;

use Phalcon\Phql\Parser;
use Phalcon\Phql\Scanner\Opcode;
use Phalcon\Phql\Tests\AbstractUnitTestCase;

final class CombinationTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-04-09
     */
    public function testMvcModelQueryPhqInsertFields(): void
    {
        $source   = "INSERT INTO Invoices " . "(inv_cst_id, inv_status_flag, inv_title, inv_total, inv_created_at) " .
                    "VALUES (1, 0, 'Test Invoice', 150.50, '2025-01-01 00:00:00')";
        $expected = [
            'type'          => Opcode::INSERT->value,
            'qualifiedName' => [
                'type' => Opcode::QUALIFIED->value,
                'name' => 'Invoices',
            ],
            'fields'        => [
                0 => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'inv_cst_id',
                ],
                1 => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'inv_status_flag',
                ],
                2 => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'inv_title',
                ],
                3 => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'inv_total',
                ],
                4 => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'inv_created_at',
                ],
            ],
            'values'        => [
                0 => [
                    'type'  => Opcode::INTEGER->value,
                    'value' => '1',
                ],
                1 => [
                    'type'  => Opcode::INTEGER->value,
                    'value' => '0',
                ],
                2 => [
                    'type'  => Opcode::STRING->value,
                    'value' => 'Test Invoice',
                ],
                3 => [
                    'type'  => Opcode::DOUBLE->value,
                    'value' => '150.50',
                ],
                4 => [
                    'type'  => Opcode::STRING->value,
                    'value' => '2025-01-01 00:00:00',
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
    public function testMvcModelQueryPhqlInsert(): void
    {
        $source   = "INSERT INTO Invoices " . "VALUES (1, 1, 1, 'Test Invoice', 100.00, '2025-01-01 00:00:00')";
        $expected = [
            'type'          => Opcode::INSERT->value,
            'qualifiedName' => [
                'type' => Opcode::QUALIFIED->value,
                'name' => 'Invoices',
            ],
            'values'        => [
                0 => [
                    'type'  => Opcode::INTEGER->value,
                    'value' => '1',
                ],
                1 => [
                    'type'  => Opcode::INTEGER->value,
                    'value' => '1',
                ],
                2 => [
                    'type'  => Opcode::INTEGER->value,
                    'value' => '1',
                ],
                3 => [
                    'type'  => Opcode::STRING->value,
                    'value' => 'Test Invoice',
                ],
                4 => [
                    'type'  => Opcode::DOUBLE->value,
                    'value' => '100.00',
                ],
                5 => [
                    'type'  => Opcode::STRING->value,
                    'value' => '2025-01-01 00:00:00',
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
    public function testMvcModelQueryPhqlInsertArithmeticInValues(): void
    {
        $source   = "INSERT INTO Invoices (inv_total) VALUES (100 + 50)";
        $expected = [
            'type'          => Opcode::INSERT->value,
            'qualifiedName' => [
                'type' => Opcode::QUALIFIED->value,
                'name' => 'Invoices',
            ],
            'fields'        => [
                0 => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'inv_total',
                ],
            ],
            'values'        => [
                0 => [
                    'type'  => Opcode::ADD->value,
                    'left'  => [
                        'type'  => Opcode::INTEGER->value,
                        'value' => '100',
                    ],
                    'right' => [
                        'type'  => Opcode::INTEGER->value,
                        'value' => '50',
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
    public function testMvcModelQueryPhqlInsertFieldsNull(): void
    {
        $source   = "INSERT INTO Invoices " . "(inv_title, inv_total) " . "VALUES ('Null Test', NULL)";
        $expected = [
            'type'          => Opcode::INSERT->value,
            'qualifiedName' => [
                'type' => Opcode::QUALIFIED->value,
                'name' => 'Invoices',
            ],
            'fields'        => [
                0 => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'inv_title',
                ],
                1 => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'inv_total',
                ],
            ],
            'values'        => [
                0 => [
                    'type'  => Opcode::STRING->value,
                    'value' => 'Null Test',
                ],
                1 => [
                    'type' => Opcode::NULL->value,
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
    public function testMvcModelQueryPhqlInsertFieldsPartial(): void
    {
        $source   = "INSERT INTO Invoices (inv_title, inv_total) " . "VALUES ('Invoice A', 200.00)";
        $expected = [
            'type'          => Opcode::INSERT->value,
            'qualifiedName' => [
                'type' => Opcode::QUALIFIED->value,
                'name' => 'Invoices',
            ],
            'fields'        => [
                0 => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'inv_title',
                ],
                1 => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'inv_total',
                ],
            ],
            'values'        => [
                0 => [
                    'type'  => Opcode::STRING->value,
                    'value' => 'Invoice A',
                ],
                1 => [
                    'type'  => Opcode::DOUBLE->value,
                    'value' => '200.00',
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
    public function testMvcModelQueryPhqlInsertFieldsPlaceholders(): void
    {
        $source   = "INSERT INTO Invoices " . "(inv_cst_id, inv_status_flag, inv_title, inv_total) " .
                    "VALUES (:cstId:, :status:, :title:, :total:)";
        $expected = [
            'type'          => Opcode::INSERT->value,
            'qualifiedName' => [
                'type' => Opcode::QUALIFIED->value,
                'name' => 'Invoices',
            ],
            'fields'        => [
                0 => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'inv_cst_id',
                ],
                1 => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'inv_status_flag',
                ],
                2 => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'inv_title',
                ],
                3 => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'inv_total',
                ],
            ],
            'values'        => [
                0 => [
                    'type'  => Opcode::SPLACEHOLDER->value,
                    'value' => 'cstId',
                ],
                1 => [
                    'type'  => Opcode::SPLACEHOLDER->value,
                    'value' => 'status',
                ],
                2 => [
                    'type'  => Opcode::SPLACEHOLDER->value,
                    'value' => 'title',
                ],
                3 => [
                    'type'  => Opcode::SPLACEHOLDER->value,
                    'value' => 'total',
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
    public function testMvcModelQueryPhqlInsertFieldsPlaceholdersBrackets(): void
    {
        $source   = "INSERT INTO Invoices " . "(inv_id, inv_cst_id, inv_status_flag, inv_title, inv_total) " .
                    "VALUES ({id}, {cstId}, {status}, {title}, {total})";
        $expected = [
            'type'          => Opcode::INSERT->value,
            'qualifiedName' => [
                'type' => Opcode::QUALIFIED->value,
                'name' => 'Invoices',
            ],
            'fields'        => [
                0 => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'inv_id',
                ],
                1 => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'inv_cst_id',
                ],
                2 => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'inv_status_flag',
                ],
                3 => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'inv_title',
                ],
                4 => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'inv_total',
                ],
            ],
            'values'        => [
                0 => [
                    'type'  => Opcode::BPLACEHOLDER->value,
                    'value' => 'id',
                ],
                1 => [
                    'type'  => Opcode::BPLACEHOLDER->value,
                    'value' => 'cstId',
                ],
                2 => [
                    'type'  => Opcode::BPLACEHOLDER->value,
                    'value' => 'status',
                ],
                3 => [
                    'type'  => Opcode::BPLACEHOLDER->value,
                    'value' => 'title',
                ],
                4 => [
                    'type'  => Opcode::BPLACEHOLDER->value,
                    'value' => 'total',
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
    public function testMvcModelQueryPhqlInsertFieldsPlaceholdersNum(): void
    {
        $source   = "INSERT INTO Invoices " . "(inv_cst_id, inv_title, inv_total) " . "VALUES (?0, ?1, ?2)";
        $expected = [
            'type'          => Opcode::INSERT->value,
            'qualifiedName' => [
                'type' => Opcode::QUALIFIED->value,
                'name' => 'Invoices',
            ],
            'fields'        => [
                0 => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'inv_cst_id',
                ],
                1 => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'inv_title',
                ],
                2 => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'inv_total',
                ],
            ],
            'values'        => [
                0 => [
                    'type'  => Opcode::NPLACEHOLDER->value,
                    'value' => '?0',
                ],
                1 => [
                    'type'  => Opcode::NPLACEHOLDER->value,
                    'value' => '?1',
                ],
                2 => [
                    'type'  => Opcode::NPLACEHOLDER->value,
                    'value' => '?2',
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
    public function testMvcModelQueryPhqlInsertFieldsTrue(): void
    {
        $source   = "INSERT INTO Invoices (inv_title, inv_status_flag) VALUES ('New Invoice', TRUE)";
        $expected = [
            'type'          => Opcode::INSERT->value,
            'qualifiedName' => [
                'type' => Opcode::QUALIFIED->value,
                'name' => 'Invoices',
            ],
            'fields'        => [
                0 => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'inv_title',
                ],
                1 => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'inv_status_flag',
                ],
            ],
            'values'        => [
                0 => [
                    'type'  => Opcode::STRING->value,
                    'value' => 'New Invoice',
                ],
                1 => [
                    'type' => Opcode::TRUE->value,
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
    public function testMvcModelQueryPhqlInsertFuncInValues(): void
    {
        $source   = "INSERT INTO Invoices (inv_title, inv_total) "
            . "VALUES (UPPER('test invoice'), 100.00)";
        $expected = [
            'type'          => Opcode::INSERT->value,
            'qualifiedName' => [
                'type' => Opcode::QUALIFIED->value,
                'name' => 'Invoices',
            ],
            'fields'        => [
                0 => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'inv_title',
                ],
                1 => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'inv_total',
                ],
            ],
            'values'        => [
                0 => [
                    'type'      => Opcode::FCALL->value,
                    'name'      => 'UPPER',
                    'arguments' => [
                        0 => [
                            'type'  => Opcode::STRING->value,
                            'value' => 'test invoice',
                        ],
                    ],
                ],
                1 => [
                    'type'  => Opcode::DOUBLE->value,
                    'value' => '100.00',
                ],
            ],
        ];
        $actual   = (new Parser())->parse($source);
        $this->assertSame($expected, $actual);
    }
}
