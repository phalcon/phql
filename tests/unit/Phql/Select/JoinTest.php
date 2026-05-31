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

final class JoinTest extends AbstractUnitTestCase
{
    /**
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-04-09
     */
    public function testMvcModelQueryPhqlSelectCrossJoin(): void
    {
        $source   = "SELECT i.inv_id, c.name "
            . "FROM Invoices AS i "
            . "CROSS JOIN Customers AS c";
        $expected = [
            'type' => Opcode::SELECT->value,
            'select' => [
                'columns' => [
                    0 => [
                        'type' => Opcode::EXPR->value,
                        'column' => [
                            'type' => Opcode::QUALIFIED->value,
                            'domain' => 'i',
                            'name'   => 'inv_id',
                        ],
                    ],
                    1 => [
                        'type' => Opcode::EXPR->value,
                        'column' => [
                            'type' => Opcode::QUALIFIED->value,
                            'domain' => 'c',
                            'name'   => 'name',
                        ],
                    ],
                ],
                'tables'  => [
                    'qualifiedName' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Invoices',
                    ],
                    'alias'         => 'i',
                ],
                'joins'   => [
                    'type' => Opcode::CROSSJOIN->value,
                    'qualified' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Customers',
                    ],
                    'alias'     => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'c',
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
    public function testMvcModelQueryPhqlSelectFullJoin(): void
    {
        $source   = "SELECT i.inv_id, c.name "
                    . "FROM Invoices AS i "
                    . "FULL JOIN Customers AS c ON i.inv_cst_id = c.id";
        $expected = [
            'type' => Opcode::SELECT->value,
            'select' => [
                'columns' => [
                    0 => [
                        'type' => Opcode::EXPR->value,
                        'column' => [
                            'type' => Opcode::QUALIFIED->value,
                            'domain' => 'i',
                            'name'   => 'inv_id',
                        ],
                    ],
                    1 => [
                        'type' => Opcode::EXPR->value,
                        'column' => [
                            'type' => Opcode::QUALIFIED->value,
                            'domain' => 'c',
                            'name'   => 'name',
                        ],
                    ],
                ],
                'tables'  => [
                    'qualifiedName' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Invoices',
                    ],
                    'alias'         => 'i',
                ],
                'joins'   => [
                    'type' => Opcode::FULLJOIN->value,
                    'qualified'  => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Customers',
                    ],
                    'alias'      => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'c',
                    ],
                    'conditions' => [
                        'type' => Opcode::EQUALS->value,
                        'left'  => [
                            'type' => Opcode::QUALIFIED->value,
                            'domain' => 'i',
                            'name'   => 'inv_cst_id',
                        ],
                        'right' => [
                            'type' => Opcode::QUALIFIED->value,
                            'domain' => 'c',
                            'name'   => 'id',
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
    public function testMvcModelQueryPhqlSelectFullOuterJoin(): void
    {
        $source   = "SELECT i.inv_id, c.name "
                    . "FROM Invoices AS i "
                    . "FULL OUTER JOIN Customers AS c ON i.inv_cst_id = c.id";
        $expected = [
            'type' => Opcode::SELECT->value,
            'select' => [
                'columns' => [
                    0 => [
                        'type' => Opcode::EXPR->value,
                        'column' => [
                            'type' => Opcode::QUALIFIED->value,
                            'domain' => 'i',
                            'name'   => 'inv_id',
                        ],
                    ],
                    1 => [
                        'type' => Opcode::EXPR->value,
                        'column' => [
                            'type' => Opcode::QUALIFIED->value,
                            'domain' => 'c',
                            'name'   => 'name',
                        ],
                    ],
                ],
                'tables'  => [
                    'qualifiedName' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Invoices',
                    ],
                    'alias'         => 'i',
                ],
                'joins'   => [
                    'type' => Opcode::FULLJOIN->value,
                    'qualified'  => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Customers',
                    ],
                    'alias'      => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'c',
                    ],
                    'conditions' => [
                        'type' => Opcode::EQUALS->value,
                        'left'  => [
                            'type' => Opcode::QUALIFIED->value,
                            'domain' => 'i',
                            'name'   => 'inv_cst_id',
                        ],
                        'right' => [
                            'type' => Opcode::QUALIFIED->value,
                            'domain' => 'c',
                            'name'   => 'id',
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
    public function testMvcModelQueryPhqlSelectInnerJoin(): void
    {
        $source   = "SELECT i.inv_id, c.name "
                    . "FROM Invoices AS i "
                    . "INNER JOIN Customers AS c ON i.inv_cst_id = c.id";
        $expected = [
            'type' => Opcode::SELECT->value,
            'select' => [
                'columns' => [
                    0 => [
                        'type' => Opcode::EXPR->value,
                        'column' => [
                            'type' => Opcode::QUALIFIED->value,
                            'domain' => 'i',
                            'name'   => 'inv_id',
                        ],
                    ],
                    1 => [
                        'type' => Opcode::EXPR->value,
                        'column' => [
                            'type' => Opcode::QUALIFIED->value,
                            'domain' => 'c',
                            'name'   => 'name',
                        ],
                    ],
                ],
                'tables'  => [
                    'qualifiedName' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Invoices',
                    ],
                    'alias'         => 'i',
                ],
                'joins'   => [
                    'type' => Opcode::INNERJOIN->value,
                    'qualified'  => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Customers',
                    ],
                    'alias'      => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'c',
                    ],
                    'conditions' => [
                        'type' => Opcode::EQUALS->value,
                        'left'  => [
                            'type' => Opcode::QUALIFIED->value,
                            'domain' => 'i',
                            'name'   => 'inv_cst_id',
                        ],
                        'right' => [
                            'type' => Opcode::QUALIFIED->value,
                            'domain' => 'c',
                            'name'   => 'id',
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
     * @since  2026-04-10
     */
    public function testMvcModelQueryPhqlSelectInnerJoinOnComplexCondition(): void
    {
        $source   = "SELECT i.inv_id, c.name FROM Invoices AS i "
            . "INNER JOIN Customers AS c "
            . "ON (i.inv_cst_id = c.id AND i.inv_status_flag = 1)";
        $expected = [
            'type' => Opcode::SELECT->value,
            'select' => [
                'columns' => [
                    0 => [
                        'type' => Opcode::EXPR->value,
                        'column' => [
                            'type' => Opcode::QUALIFIED->value,
                            'domain' => 'i',
                            'name'   => 'inv_id',
                        ],
                    ],
                    1 => [
                        'type' => Opcode::EXPR->value,
                        'column' => [
                            'type' => Opcode::QUALIFIED->value,
                            'domain' => 'c',
                            'name'   => 'name',
                        ],
                    ],
                ],
                'tables'  => [
                    'qualifiedName' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Invoices',
                    ],
                    'alias'         => 'i',
                ],
                'joins'   => [
                    'type' => Opcode::INNERJOIN->value,
                    'qualified'  => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Customers',
                    ],
                    'alias'      => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'c',
                    ],
                    'conditions' => [
                        'type' => Opcode::ENCLOSED->value,
                        'left' => [
                            'type' => Opcode::EQUALS->value,
                            'left'  => [
                                'type' => Opcode::EQUALS->value,
                                'left'  => [
                                    'type' => Opcode::QUALIFIED->value,
                                    'domain' => 'i',
                                    'name'   => 'inv_cst_id',
                                ],
                                'right' => [
                                    'type' => Opcode::AND->value,
                                    'left'  => [
                                        'type' => Opcode::QUALIFIED->value,
                                        'domain' => 'c',
                                        'name'   => 'id',
                                    ],
                                    'right' => [
                                        'type' => Opcode::QUALIFIED->value,
                                        'domain' => 'i',
                                        'name'   => 'inv_status_flag',
                                    ],
                                ],
                            ],
                            'right' => [
                                'type' => Opcode::INTEGER->value,
                                'value' => '1',
                            ],
                        ],
                    ],
                ],
            ],
        ];
        $actual   = (new Parser())->parse($source);
        $this->assertSame($expected, $actual);
    }

    /**
     * A JOIN without an alias must not emit an 'alias' key in the AST. The
     * empty `join_associated_name` production maps to cphalcon's ZVAL_UNDEF,
     * i.e. PHP null, so phql_ret_join_item() skips the key. A stray empty
     * 'alias' makes the consumer (Query::getJoins) treat the join as aliased,
     * register it under an empty alias and fail to resolve the model.
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-05-30
     */
    public function testMvcModelQueryPhqlSelectInnerJoinWithoutAlias(): void
    {
        $source   = "SELECT i.inv_id, Customers.name "
            . "FROM Invoices AS i "
            . "INNER JOIN Customers ON i.inv_cst_id = Customers.id";
        $expected = [
            'type' => Opcode::SELECT->value,
            'select' => [
                'columns' => [
                    0 => [
                        'type' => Opcode::EXPR->value,
                        'column' => [
                            'type' => Opcode::QUALIFIED->value,
                            'domain' => 'i',
                            'name'   => 'inv_id',
                        ],
                    ],
                    1 => [
                        'type' => Opcode::EXPR->value,
                        'column' => [
                            'type' => Opcode::QUALIFIED->value,
                            'domain' => 'Customers',
                            'name'   => 'name',
                        ],
                    ],
                ],
                'tables'  => [
                    'qualifiedName' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Invoices',
                    ],
                    'alias'         => 'i',
                ],
                'joins'   => [
                    'type' => Opcode::INNERJOIN->value,
                    'qualified'  => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Customers',
                    ],
                    'conditions' => [
                        'type' => Opcode::EQUALS->value,
                        'left'  => [
                            'type' => Opcode::QUALIFIED->value,
                            'domain' => 'i',
                            'name'   => 'inv_cst_id',
                        ],
                        'right' => [
                            'type' => Opcode::QUALIFIED->value,
                            'domain' => 'Customers',
                            'name'   => 'id',
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
    public function testMvcModelQueryPhqlSelectInnerLeftJoin(): void
    {
        $source   = "SELECT i.inv_id, c.name, p.description "
            . "FROM Invoices AS i "
            . "INNER JOIN Customers AS c ON i.inv_cst_id = c.id "
            . "LEFT JOIN Products AS p ON i.inv_id = p.inv_id";
        $expected = [
            'type' => Opcode::SELECT->value,
            'select' => [
                'columns' => [
                    0 => [
                        'type' => Opcode::EXPR->value,
                        'column' => [
                            'type' => Opcode::QUALIFIED->value,
                            'domain' => 'i',
                            'name'   => 'inv_id',
                        ],
                    ],
                    1 => [
                        'type' => Opcode::EXPR->value,
                        'column' => [
                            'type' => Opcode::QUALIFIED->value,
                            'domain' => 'c',
                            'name'   => 'name',
                        ],
                    ],
                    2 => [
                        'type' => Opcode::EXPR->value,
                        'column' => [
                            'type' => Opcode::QUALIFIED->value,
                            'domain' => 'p',
                            'name'   => 'description',
                        ],
                    ],
                ],
                'tables'  => [
                    'qualifiedName' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Invoices',
                    ],
                    'alias'         => 'i',
                ],
                'joins'   => [
                    0 => [
                        'type' => Opcode::INNERJOIN->value,
                        'qualified'  => [
                            'type' => Opcode::QUALIFIED->value,
                            'name' => 'Customers',
                        ],
                        'alias'      => [
                            'type' => Opcode::QUALIFIED->value,
                            'name' => 'c',
                        ],
                        'conditions' => [
                            'type' => Opcode::EQUALS->value,
                            'left'  => [
                                'type' => Opcode::QUALIFIED->value,
                                'domain' => 'i',
                                'name'   => 'inv_cst_id',
                            ],
                            'right' => [
                                'type' => Opcode::QUALIFIED->value,
                                'domain' => 'c',
                                'name'   => 'id',
                            ],
                        ],
                    ],
                    1 => [
                        'type' => Opcode::LEFTJOIN->value,
                        'qualified'  => [
                            'type' => Opcode::QUALIFIED->value,
                            'name' => 'Products',
                        ],
                        'alias'      => [
                            'type' => Opcode::QUALIFIED->value,
                            'name' => 'p',
                        ],
                        'conditions' => [
                            'type' => Opcode::EQUALS->value,
                            'left'  => [
                                'type' => Opcode::QUALIFIED->value,
                                'domain' => 'i',
                                'name'   => 'inv_id',
                            ],
                            'right' => [
                                'type' => Opcode::QUALIFIED->value,
                                'domain' => 'p',
                                'name'   => 'inv_id',
                            ],
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
    public function testMvcModelQueryPhqlSelectJoin(): void
    {
        $source   = "SELECT i.inv_id, c.name "
                    . "FROM Invoices AS i "
                    . "JOIN Customers AS c ON i.inv_cst_id = c.id";
        $expected = [
            'type' => Opcode::SELECT->value,
            'select' => [
                'columns' => [
                    0 => [
                        'type' => Opcode::EXPR->value,
                        'column' => [
                            'type' => Opcode::QUALIFIED->value,
                            'domain' => 'i',
                            'name'   => 'inv_id',
                        ],
                    ],
                    1 => [
                        'type' => Opcode::EXPR->value,
                        'column' => [
                            'type' => Opcode::QUALIFIED->value,
                            'domain' => 'c',
                            'name'   => 'name',
                        ],
                    ],
                ],
                'tables'  => [
                    'qualifiedName' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Invoices',
                    ],
                    'alias'         => 'i',
                ],
                'joins'   => [
                    'type' => Opcode::INNERJOIN->value,
                    'qualified'  => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Customers',
                    ],
                    'alias'      => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'c',
                    ],
                    'conditions' => [
                        'type' => Opcode::EQUALS->value,
                        'left'  => [
                            'type' => Opcode::QUALIFIED->value,
                            'domain' => 'i',
                            'name'   => 'inv_cst_id',
                        ],
                        'right' => [
                            'type' => Opcode::QUALIFIED->value,
                            'domain' => 'c',
                            'name'   => 'id',
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
    public function testMvcModelQueryPhqlSelectLeftJoin(): void
    {
        $source   = "SELECT i.inv_id, c.name "
            . "FROM Invoices AS i "
            . "LEFT JOIN Customers AS c ON i.inv_cst_id = c.id";
        $expected = [
            'type' => Opcode::SELECT->value,
            'select' => [
                'columns' => [
                    0 => [
                        'type' => Opcode::EXPR->value,
                        'column' => [
                            'type' => Opcode::QUALIFIED->value,
                            'domain' => 'i',
                            'name'   => 'inv_id',
                        ],
                    ],
                    1 => [
                        'type' => Opcode::EXPR->value,
                        'column' => [
                            'type' => Opcode::QUALIFIED->value,
                            'domain' => 'c',
                            'name'   => 'name',
                        ],
                    ],
                ],
                'tables'  => [
                    'qualifiedName' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Invoices',
                    ],
                    'alias'         => 'i',
                ],
                'joins'   => [
                    'type' => Opcode::LEFTJOIN->value,
                    'qualified'  => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Customers',
                    ],
                    'alias'      => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'c',
                    ],
                    'conditions' => [
                        'type' => Opcode::EQUALS->value,
                        'left'  => [
                            'type' => Opcode::QUALIFIED->value,
                            'domain' => 'i',
                            'name'   => 'inv_cst_id',
                        ],
                        'right' => [
                            'type' => Opcode::QUALIFIED->value,
                            'domain' => 'c',
                            'name'   => 'id',
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
    public function testMvcModelQueryPhqlSelectLeftOuterJoin(): void
    {
        $source   = "SELECT i.inv_id, c.name "
                    . "FROM Invoices AS i "
                    . "LEFT OUTER JOIN Customers AS c ON i.inv_cst_id = c.id";
        $expected = [
            'type' => Opcode::SELECT->value,
            'select' => [
                'columns' => [
                    0 => [
                        'type' => Opcode::EXPR->value,
                        'column' => [
                            'type' => Opcode::QUALIFIED->value,
                            'domain' => 'i',
                            'name'   => 'inv_id',
                        ],
                    ],
                    1 => [
                        'type' => Opcode::EXPR->value,
                        'column' => [
                            'type' => Opcode::QUALIFIED->value,
                            'domain' => 'c',
                            'name'   => 'name',
                        ],
                    ],
                ],
                'tables'  => [
                    'qualifiedName' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Invoices',
                    ],
                    'alias'         => 'i',
                ],
                'joins'   => [
                    'type' => Opcode::LEFTJOIN->value,
                    'qualified'  => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Customers',
                    ],
                    'alias'      => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'c',
                    ],
                    'conditions' => [
                        'type' => Opcode::EQUALS->value,
                        'left'  => [
                            'type' => Opcode::QUALIFIED->value,
                            'domain' => 'i',
                            'name'   => 'inv_cst_id',
                        ],
                        'right' => [
                            'type' => Opcode::QUALIFIED->value,
                            'domain' => 'c',
                            'name'   => 'id',
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
    public function testMvcModelQueryPhqlSelectRightJoin(): void
    {
        $source   = "SELECT i.inv_id, c.name "
                    . "FROM Invoices AS i "
                    . "RIGHT JOIN Customers AS c ON i.inv_cst_id = c.id";
        $expected = [
            'type' => Opcode::SELECT->value,
            'select' => [
                'columns' => [
                    0 => [
                        'type' => Opcode::EXPR->value,
                        'column' => [
                            'type' => Opcode::QUALIFIED->value,
                            'domain' => 'i',
                            'name'   => 'inv_id',
                        ],
                    ],
                    1 => [
                        'type' => Opcode::EXPR->value,
                        'column' => [
                            'type' => Opcode::QUALIFIED->value,
                            'domain' => 'c',
                            'name'   => 'name',
                        ],
                    ],
                ],
                'tables'  => [
                    'qualifiedName' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Invoices',
                    ],
                    'alias'         => 'i',
                ],
                'joins'   => [
                    'type' => Opcode::RIGHTJOIN->value,
                    'qualified'  => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Customers',
                    ],
                    'alias'      => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'c',
                    ],
                    'conditions' => [
                        'type' => Opcode::EQUALS->value,
                        'left'  => [
                            'type' => Opcode::QUALIFIED->value,
                            'domain' => 'i',
                            'name'   => 'inv_cst_id',
                        ],
                        'right' => [
                            'type' => Opcode::QUALIFIED->value,
                            'domain' => 'c',
                            'name'   => 'id',
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
    public function testMvcModelQueryPhqlSelectRightOuterJoin(): void
    {
        $source   = "SELECT i.inv_id, c.name "
                    . "FROM Invoices AS i "
                    . "RIGHT OUTER JOIN Customers AS c ON i.inv_cst_id = c.id";
        $expected = [
            'type' => Opcode::SELECT->value,
            'select' => [
                'columns' => [
                    0 => [
                        'type' => Opcode::EXPR->value,
                        'column' => [
                            'type' => Opcode::QUALIFIED->value,
                            'domain' => 'i',
                            'name'   => 'inv_id',
                        ],
                    ],
                    1 => [
                        'type' => Opcode::EXPR->value,
                        'column' => [
                            'type' => Opcode::QUALIFIED->value,
                            'domain' => 'c',
                            'name'   => 'name',
                        ],
                    ],
                ],
                'tables'  => [
                    'qualifiedName' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Invoices',
                    ],
                    'alias'         => 'i',
                ],
                'joins'   => [
                    'type' => Opcode::RIGHTJOIN->value,
                    'qualified'  => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Customers',
                    ],
                    'alias'      => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'c',
                    ],
                    'conditions' => [
                        'type' => Opcode::EQUALS->value,
                        'left'  => [
                            'type' => Opcode::QUALIFIED->value,
                            'domain' => 'i',
                            'name'   => 'inv_cst_id',
                        ],
                        'right' => [
                            'type' => Opcode::QUALIFIED->value,
                            'domain' => 'c',
                            'name'   => 'id',
                        ],
                    ],
                ],
            ],
        ];
        $actual   = (new Parser())->parse($source);
        $this->assertSame($expected, $actual);
    }
}
