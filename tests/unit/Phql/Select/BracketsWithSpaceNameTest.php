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

final class BracketsWithSpaceNameTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-04-11
     */
    public function testMvcModelQueryPhqlSelectBracketsColumnAliasSpaced(): void
    {
        $source   = "SELECT People.firstName AS [First Name], "
            . "People.lastName AS [Last Name] "
            . "FROM People";
        $expected = [
            'type'   => Opcode::SELECT->value,
            'select' => [
                'columns' => [
                    0 => [
                        'type'   => Opcode::EXPR->value,
                        'column' => [
                            'type'   => Opcode::QUALIFIED->value,
                            'domain' => 'People',
                            'name'   => 'firstName',
                        ],
                        'alias'  => 'First Name',
                    ],
                    1 => [
                        'type'   => Opcode::EXPR->value,
                        'column' => [
                            'type'   => Opcode::QUALIFIED->value,
                            'domain' => 'People',
                            'name'   => 'lastName',
                        ],
                        'alias'  => 'Last Name',
                    ],
                ],
                'tables'  => [
                    'qualifiedName' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'People',
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
    public function testMvcModelQueryPhqlSelectBracketsFieldSpaces(): void
    {
        $source   = "SELECT [First Name], [Last Name] FROM Contacts";
        $expected = [
            'type'   => Opcode::SELECT->value,
            'select' => [
                'columns' => [
                    0 => [
                        'type'   => Opcode::EXPR->value,
                        'column' => [
                            'type' => Opcode::QUALIFIED->value,
                            'name' => 'First Name',
                        ],
                    ],
                    1 => [
                        'type'   => Opcode::EXPR->value,
                        'column' => [
                            'type' => Opcode::QUALIFIED->value,
                            'name' => 'Last Name',
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
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-04-09
     */
    public function testMvcModelQueryPhqlSelectBracketsFieldSpacesAlias(): void
    {
        $source   = "SELECT c.[First Name], c.[Last Name] " . "FROM Contacts AS c";
        $expected = [
            'type'   => Opcode::SELECT->value,
            'select' => [
                'columns' => [
                    0 => [
                        'type'   => Opcode::EXPR->value,
                        'column' => [
                            'type'   => Opcode::QUALIFIED->value,
                            'domain' => 'c',
                            'name'   => 'First Name',
                        ],
                    ],
                    1 => [
                        'type'   => Opcode::EXPR->value,
                        'column' => [
                            'type'   => Opcode::QUALIFIED->value,
                            'domain' => 'c',
                            'name'   => 'Last Name',
                        ],
                    ],
                ],
                'tables'  => [
                    'qualifiedName' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Contacts',
                    ],
                    'alias'         => 'c',
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
    public function testMvcModelQueryPhqlSelectBracketsFieldSpacesAliasWhereString(): void
    {
        $source   = "SELECT c.[First Name] " . "FROM Contacts AS c " . "WHERE c.[Last Name] = 'Smith'";
        $expected = [
            'type'   => Opcode::SELECT->value,
            'select' => [
                'columns' => [
                    0 => [
                        'type'   => Opcode::EXPR->value,
                        'column' => [
                            'type'   => Opcode::QUALIFIED->value,
                            'domain' => 'c',
                            'name'   => 'First Name',
                        ],
                    ],
                ],
                'tables'  => [
                    'qualifiedName' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Contacts',
                    ],
                    'alias'         => 'c',
                ],
            ],
            'where'  => [
                'type'  => Opcode::EQUALS->value,
                'left'  => [
                    'type'   => Opcode::QUALIFIED->value,
                    'domain' => 'c',
                    'name'   => 'Last Name',
                ],
                'right' => [
                    'type'  => Opcode::STRING->value,
                    'value' => 'Smith',
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
    public function testMvcModelQueryPhqlSelectBracketsFieldSpacesOrderBy(): void
    {
        $source   = "SELECT [First Name] AS firstName, [Last Name] AS lastName "
                    . "FROM Contacts "
                    . "ORDER BY [Last Name] ASC";
        $expected = [
            'type'    => Opcode::SELECT->value,
            'select'  => [
                'columns' => [
                    0 => [
                        'type'   => Opcode::EXPR->value,
                        'column' => [
                            'type' => Opcode::QUALIFIED->value,
                            'name' => 'First Name',
                        ],
                        'alias'  => 'firstName',
                    ],
                    1 => [
                        'type'   => Opcode::EXPR->value,
                        'column' => [
                            'type' => Opcode::QUALIFIED->value,
                            'name' => 'Last Name',
                        ],
                        'alias'  => 'lastName',
                    ],
                ],
                'tables'  => [
                    'qualifiedName' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Contacts',
                    ],
                ],
            ],
            'orderBy' => [
                'column' => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'Last Name',
                ],
                'sort'   => 327,
            ],
        ];
        $actual   = (new Parser())->parse($source);
        $this->assertSame($expected, $actual);
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-04-09
     */
    public function testMvcModelQueryPhqlSelectBracketsTableSpaces(): void
    {
        $source   = "SELECT * FROM [My Table]";
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
                        'name' => 'My Table',
                    ],
                ],
            ],
        ];
        $actual   = (new Parser())->parse($source);
        $this->assertSame($expected, $actual);
    }
}
