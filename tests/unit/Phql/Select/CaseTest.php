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

final class CaseTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-04-10
     */
    public function testMvcModelQueryPhqlSelectCaseSimple(): void
    {
        $source   = "SELECT CASE inv_status_flag "
            . "WHEN 0 THEN 'pending' "
            . "WHEN 1 THEN 'paid' "
            . "ELSE 'unknown' END "
            . "FROM Invoices";
        $expected = [
            'type'   => Opcode::SELECT->value,
            'select' => [
                'columns' => [
                    0 => [
                        'type'   => Opcode::EXPR->value,
                        'column' => [
                            'type'  => Opcode::CASE->value,
                            'left'  => [
                                'type' => Opcode::QUALIFIED->value,
                                'name' => 'inv_status_flag',
                            ],
                            'right' => [
                                0 => [
                                    'type'  => Opcode::WHEN->value,
                                    'left'  => [
                                        'type'  => Opcode::INTEGER->value,
                                        'value' => '0',
                                    ],
                                    'right' => [
                                        'type'  => Opcode::STRING->value,
                                        'value' => 'pending',
                                    ],
                                ],
                                1 => [
                                    'type'  => Opcode::WHEN->value,
                                    'left'  => [
                                        'type'  => Opcode::INTEGER->value,
                                        'value' => '1',
                                    ],
                                    'right' => [
                                        'type'  => Opcode::STRING->value,
                                        'value' => 'paid',
                                    ],
                                ],
                                2 => [
                                    'type' => Opcode::ELSE->value,
                                    'left' => [
                                        'type'  => Opcode::STRING->value,
                                        'value' => 'unknown',
                                    ],
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
     * @since  2026-04-10
     */
    public function testMvcModelQueryPhqlSelectCaseSimpleAlias(): void
    {
        $source   = "SELECT CASE inv_status_flag "
            . "WHEN 0 THEN 'pending' "
            . "WHEN 1 THEN 'paid' "
            . "ELSE 'unknown' END AS status "
            . "FROM Invoices";
        $expected = [
            'type'   => Opcode::SELECT->value,
            'select' => [
                'columns' => [
                    0 => [
                        'type'   => Opcode::EXPR->value,
                        'column' => [
                            'type'  => Opcode::CASE->value,
                            'left'  => [
                                'type' => Opcode::QUALIFIED->value,
                                'name' => 'inv_status_flag',
                            ],
                            'right' => [
                                0 => [
                                    'type'  => Opcode::WHEN->value,
                                    'left'  => [
                                        'type'  => Opcode::INTEGER->value,
                                        'value' => '0',
                                    ],
                                    'right' => [
                                        'type'  => Opcode::STRING->value,
                                        'value' => 'pending',
                                    ],
                                ],
                                1 => [
                                    'type'  => Opcode::WHEN->value,
                                    'left'  => [
                                        'type'  => Opcode::INTEGER->value,
                                        'value' => '1',
                                    ],
                                    'right' => [
                                        'type'  => Opcode::STRING->value,
                                        'value' => 'paid',
                                    ],
                                ],
                                2 => [
                                    'type' => Opcode::ELSE->value,
                                    'left' => [
                                        'type'  => Opcode::STRING->value,
                                        'value' => 'unknown',
                                    ],
                                ],
                            ],
                        ],
                        'alias'  => 'status',
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
     * @since  2026-04-10
     */
    public function testMvcModelQueryPhqlSelectCaseSimpleInWhere(): void
    {
        $source   = "SELECT * FROM Invoices "
            . "WHERE CASE inv_status_flag WHEN 1 THEN 1 ELSE 0 END = 1";
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
                'type'  => Opcode::EQUALS->value,
                'left'  => [
                    'type'  => Opcode::CASE->value,
                    'left'  => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'inv_status_flag',
                    ],
                    'right' => [
                        0 => [
                            'type'  => Opcode::WHEN->value,
                            'left'  => [
                                'type'  => Opcode::INTEGER->value,
                                'value' => '1',
                            ],
                            'right' => [
                                'type'  => Opcode::INTEGER->value,
                                'value' => '1',
                            ],
                        ],
                        1 => [
                            'type' => Opcode::ELSE->value,
                            'left' => [
                                'type'  => Opcode::INTEGER->value,
                                'value' => '0',
                            ],
                        ],
                    ],
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
}
