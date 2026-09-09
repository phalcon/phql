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

final class LimitTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-04-09
     */
    public function testMvcModelQueryPhqlSelectLimit(): void
    {
        $source   = "SELECT * FROM Invoices LIMIT 10";
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
     * @issue  1
     * @since  2026-04-11
     */
    public function testMvcModelQueryPhqlSelectLimitAliasedDomainAll(): void
    {
        $source   = "SELECT r.* FROM Robots r LIMIT 10";
        $expected = [
            'type'   => Opcode::SELECT->value,
            'select' => [
                'columns' => [
                    0 => [
                        'type'   => Opcode::DOMAINALL->value,
                        'column' => 'r',
                    ],
                ],
                'tables'  => [
                    'qualifiedName' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Robots',
                    ],
                    'alias'         => 'r',
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
    public function testMvcModelQueryPhqlSelectLimitBoth(): void
    {
        $source   = "SELECT * FROM Invoices LIMIT 20, 10";
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
            'limit'  => [
                'number' => [
                    'type'  => Opcode::INTEGER->value,
                    'value' => '10',
                ],
                'offset' => [
                    'type'  => Opcode::INTEGER->value,
                    'value' => '20',
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
    public function testMvcModelQueryPhqlSelectLimitBracePlaceholder(): void
    {
        $source   = "SELECT * FROM Invoices LIMIT {limit}";
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
            'limit'  => [
                'number' => [
                    'type'  => Opcode::BPLACEHOLDER->value,
                    'value' => 'limit',
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
    public function testMvcModelQueryPhqlSelectLimitBracePlaceholderOffset(): void
    {
        $source   = "SELECT * FROM Invoices LIMIT {limit} OFFSET {offset}";
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
            'limit'  => [
                'number' => [
                    'type'  => Opcode::BPLACEHOLDER->value,
                    'value' => 'limit',
                ],
                'offset' => [
                    'type'  => Opcode::BPLACEHOLDER->value,
                    'value' => 'offset',
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
    public function testMvcModelQueryPhqlSelectLimitOffset(): void
    {
        $source   = "SELECT * FROM Invoices LIMIT 10 OFFSET 20";
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
            'limit'  => [
                'number' => [
                    'type'  => Opcode::INTEGER->value,
                    'value' => '10',
                ],
                'offset' => [
                    'type'  => Opcode::INTEGER->value,
                    'value' => '20',
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
    public function testMvcModelQueryPhqlSelectLimitOffsetPlaceholders(): void
    {
        $source   = "SELECT * FROM Invoices LIMIT :limit: OFFSET :offset:";
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
            'limit'  => [
                'number' => [
                    'type'  => Opcode::SPLACEHOLDER->value,
                    'value' => 'limit',
                ],
                'offset' => [
                    'type'  => Opcode::SPLACEHOLDER->value,
                    'value' => 'offset',
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
    public function testMvcModelQueryPhqlSelectLimitPlaceholderNum(): void
    {
        $source   = "SELECT * FROM Invoices LIMIT ?0";
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
            'limit'  => [
                'number' => [
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
    public function testMvcModelQueryPhqlSelectWhereOrderLimit(): void
    {
        $source   = "SELECT * "
            . "FROM Invoices "
            . "WHERE inv_status_flag = 1 "
            . "ORDER BY inv_id DESC "
            . "LIMIT 5";
        $expected = [
            'type'    => Opcode::SELECT->value,
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
                'type'  => Opcode::EQUALS->value,
                'left'  => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'inv_status_flag',
                ],
                'right' => [
                    'type'  => Opcode::INTEGER->value,
                    'value' => '1',
                ],
            ],
            'orderBy' => [
                'column' => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'inv_id',
                ],
                'sort'   => 328,
            ],
            'limit'   => [
                'number' => [
                    'type'  => Opcode::INTEGER->value,
                    'value' => '5',
                ],
            ],
        ];
        $actual   = (new Parser())->parse($source);
        $this->assertSame($expected, $actual);
    }
}
