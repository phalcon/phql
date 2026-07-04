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

final class KeywordPrefixNameTest extends AbstractUnitTestCase
{
    /**
     * @return void
     *
     * @issue  https://github.com/phalcon/cphalcon/issues/16831
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-06-05
     */
    public function testMvcModelQueryPhqlSelectJoinPrefixNotColumn(): void
    {
        $source   = "SELECT * FROM Robots r "
            . "LEFT JOIN RobotsParts rp ON r.notes_id = rp.robots_id";
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
                        'name' => 'Robots',
                    ],
                    'alias'         => 'r',
                ],
                'joins'   => [
                    'type'       => Opcode::LEFTJOIN->value,
                    'qualified'  => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'RobotsParts',
                    ],
                    'alias'      => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'rp',
                    ],
                    'conditions' => [
                        'type'  => Opcode::EQUALS->value,
                        'left'  => [
                            'type'   => Opcode::QUALIFIED->value,
                            'domain' => 'r',
                            'name'   => 'notes_id',
                        ],
                        'right' => [
                            'type'   => Opcode::QUALIFIED->value,
                            'domain' => 'rp',
                            'name'   => 'robots_id',
                        ],
                    ],
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
    public function testMvcModelQueryPhqlSelectPrefixGroups(): void
    {
        $source   = "SELECT Groups FROM Settings";
        $expected = [
            'type' => Opcode::SELECT->value,
            'select' => [
                'columns' => [
                    0 => [
                        'type' => Opcode::EXPR->value,
                        'column' => [
                            'type' => Opcode::QUALIFIED->value,
                            'name' => 'Groups',
                        ],
                    ],
                ],
                'tables'  => [
                    'qualifiedName' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Settings',
                    ],
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
    public function testMvcModelQueryPhqlSelectPrefixGroupsBrackets(): void
    {
        $source   = "SELECT [Groups] FROM Settings";
        $expected = [
            'type' => Opcode::SELECT->value,
            'select' => [
                'columns' => [
                    0 => [
                        'type' => Opcode::EXPR->value,
                        'column' => [
                            'type' => Opcode::QUALIFIED->value,
                            'name' => 'Groups',
                        ],
                    ],
                ],
                'tables'  => [
                    'qualifiedName' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Settings',
                    ],
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
    public function testMvcModelQueryPhqlSelectPrefixNotes(): void
    {
        $source   = "SELECT Notes FROM Contacts";
        $expected = [
            'type' => Opcode::SELECT->value,
            'select' => [
                'columns' => [
                    0 => [
                        'type' => Opcode::EXPR->value,
                        'column' => [
                            'type' => Opcode::QUALIFIED->value,
                            'name' => 'Notes',
                        ],
                    ],
                ],
                'tables'  => [
                    'qualifiedName' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Contacts',
                    ],
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
    public function testMvcModelQueryPhqlSelectPrefixNotesBrackets(): void
    {
        $source   = "SELECT [Notes] FROM Contacts";
        $expected = [
            'type' => Opcode::SELECT->value,
            'select' => [
                'columns' => [
                    0 => [
                        'type' => Opcode::EXPR->value,
                        'column' => [
                            'type' => Opcode::QUALIFIED->value,
                            'name' => 'Notes',
                        ],
                    ],
                ],
                'tables'  => [
                    'qualifiedName' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Contacts',
                    ],
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
    public function testMvcModelQueryPhqlSelectPrefixOrders(): void
    {
        $source   = "SELECT Orders FROM Customers";
        $expected = [
            'type' => Opcode::SELECT->value,
            'select' => [
                'columns' => [
                    0 => [
                        'type' => Opcode::EXPR->value,
                        'column' => [
                            'type' => Opcode::QUALIFIED->value,
                            'name' => 'Orders',
                        ],
                    ],
                ],
                'tables'  => [
                    'qualifiedName' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Customers',
                    ],
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
    public function testMvcModelQueryPhqlSelectPrefixOrdersBrackets(): void
    {
        $source   = "SELECT [Orders] FROM Customers";
        $expected = [
            'type' => Opcode::SELECT->value,
            'select' => [
                'columns' => [
                    0 => [
                        'type' => Opcode::EXPR->value,
                        'column' => [
                            'type' => Opcode::QUALIFIED->value,
                            'name' => 'Orders',
                        ],
                    ],
                ],
                'tables'  => [
                    'qualifiedName' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Customers',
                    ],
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
    public function testMvcModelQueryPhqlSelectPrefixWhereNotesIsNotNull(): void
    {
        $source   = "SELECT * FROM Contacts WHERE [Notes] IS NOT NULL";
        $expected = [
            'type' => Opcode::SELECT->value,
            'select' => [
                'columns' => [
                    0 => [
                        'type' => Opcode::STARALL->value,
                    ],
                ],
                'tables'  => [
                    'qualifiedName' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Contacts',
                    ],
                ],
            ],
            'where'  => [
                'type' => Opcode::ISNOTNULL->value,
                'left' => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'Notes',
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
    public function testMvcModelQueryPhqlSelectPrefixWhereNotesLike(): void
    {
        $source   = "SELECT * FROM Contacts WHERE [Notes] LIKE '%important%'";
        $expected = [
            'type' => Opcode::SELECT->value,
            'select' => [
                'columns' => [
                    0 => [
                        'type' => Opcode::STARALL->value,
                    ],
                ],
                'tables'  => [
                    'qualifiedName' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Contacts',
                    ],
                ],
            ],
            'where'  => [
                'type' => Opcode::LIKE->value,
                'left'  => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'Notes',
                ],
                'right' => [
                    'type' => Opcode::STRING->value,
                    'value' => '%important%',
                ],
            ],
        ];
        $actual   = (new Parser())->parse($source);
        $this->assertSame($expected, $actual);
    }
}
