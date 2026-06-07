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

namespace Phalcon\Phql\Tests\Unit\Phql\Update;

use Phalcon\Phql\Parser;
use Phalcon\Phql\Scanner\Opcode;
use Phalcon\Phql\Tests\AbstractUnitTestCase;

final class CombinationTest extends AbstractUnitTestCase
{
    /**
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-04-09
     */
    public function testMvcModelQueryPhqlUpdate(): void
    {
        $source   = "UPDATE Invoices " . "SET inv_status_flag = 1";
        $expected = [
            'type' => Opcode::UPDATE->value,
            'update' => [
                'tables' => [
                    'qualifiedName' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Invoices',
                    ],
                ],
                'values' => [
                    'column' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'inv_status_flag',
                    ],
                    'expr'   => [
                        'type' => Opcode::INTEGER->value,
                        'value' => '1',
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
    public function testMvcModelQueryPhqlUpdateAliasNumWhereNum(): void
    {
        $source   = "UPDATE Invoices AS i " . "SET i.inv_status_flag = 1 " . "WHERE i.inv_cst_id = 1";
        $expected = [
            'type' => Opcode::UPDATE->value,
            'update' => [
                'tables' => [
                    'qualifiedName' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Invoices',
                    ],
                    'alias'         => 'i',
                ],
                'values' => [
                    'column' => [
                        'type' => Opcode::QUALIFIED->value,
                        'domain' => 'i',
                        'name'   => 'inv_status_flag',
                    ],
                    'expr'   => [
                        'type' => Opcode::INTEGER->value,
                        'value' => '1',
                    ],
                ],
            ],
            'where'  => [
                'type' => Opcode::EQUALS->value,
                'left'  => [
                    'type' => Opcode::QUALIFIED->value,
                    'domain' => 'i',
                    'name'   => 'inv_cst_id',
                ],
                'right' => [
                    'type' => Opcode::INTEGER->value,
                    'value' => '1',
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
    public function testMvcModelQueryPhqlUpdateCalculatedWhereNumZero(): void
    {
        $source   = "UPDATE Invoices " . "SET inv_total = inv_total * 1.1 " . "WHERE inv_status_flag = 0";
        $expected = [
            'type' => Opcode::UPDATE->value,
            'update' => [
                'tables' => [
                    'qualifiedName' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Invoices',
                    ],
                ],
                'values' => [
                    'column' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'inv_total',
                    ],
                    'expr'   => [
                        'type' => Opcode::MUL->value,
                        'left'  => [
                            'type' => Opcode::QUALIFIED->value,
                            'name' => 'inv_total',
                        ],
                        'right' => [
                            'type' => Opcode::DOUBLE->value,
                            'value' => '1.1',
                        ],
                    ],
                ],
            ],
            'where'  => [
                'type' => Opcode::EQUALS->value,
                'left'  => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'inv_status_flag',
                ],
                'right' => [
                    'type' => Opcode::INTEGER->value,
                    'value' => '0',
                ],
            ],
        ];
        $actual   = (new Parser())->parse($source);
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests UPDATE with an INNER JOIN and no WHERE clause.
     *
     * @return void
     *
     * @issue  https://github.com/phalcon/cphalcon/issues/16984
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-06-06
     */
    public function testMvcModelQueryPhqlUpdateInnerJoin(): void
    {
        $source   = "UPDATE Invoices "
            . "INNER JOIN Customers ON Customers.cst_id = Invoices.inv_cst_id "
            . "SET inv_total = 999";
        $expected = [
            'type'   => Opcode::UPDATE->value,
            'update' => [
                'tables' => [
                    'qualifiedName' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Invoices',
                    ],
                ],
                'joins'  => [
                    'type'       => Opcode::INNERJOIN->value,
                    'qualified'  => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Customers',
                    ],
                    'conditions' => [
                        'type'  => Opcode::EQUALS->value,
                        'left'  => [
                            'type'   => Opcode::QUALIFIED->value,
                            'domain' => 'Customers',
                            'name'   => 'cst_id',
                        ],
                        'right' => [
                            'type'   => Opcode::QUALIFIED->value,
                            'domain' => 'Invoices',
                            'name'   => 'inv_cst_id',
                        ],
                    ],
                ],
                'values' => [
                    'column' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'inv_total',
                    ],
                    'expr'   => [
                        'type'  => Opcode::INTEGER->value,
                        'value' => '999',
                    ],
                ],
            ],
        ];
        $actual   = (new Parser())->parse($source);
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests UPDATE with an INNER JOIN using aliased tables.
     *
     * @return void
     *
     * @issue  https://github.com/phalcon/cphalcon/issues/16984
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-06-06
     */
    public function testMvcModelQueryPhqlUpdateInnerJoinAliasesWhereNum(): void
    {
        $source   = "UPDATE Invoices AS i "
            . "INNER JOIN Customers AS c ON c.cst_id = i.inv_cst_id "
            . "SET i.inv_total = 999 "
            . "WHERE c.cst_id = 1";
        $expected = [
            'type'   => Opcode::UPDATE->value,
            'update' => [
                'tables' => [
                    'qualifiedName' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Invoices',
                    ],
                    'alias'         => 'i',
                ],
                'joins'  => [
                    'type'       => Opcode::INNERJOIN->value,
                    'qualified'  => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Customers',
                    ],
                    'alias'      => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'c',
                    ],
                    'conditions' => [
                        'type'  => Opcode::EQUALS->value,
                        'left'  => [
                            'type'   => Opcode::QUALIFIED->value,
                            'domain' => 'c',
                            'name'   => 'cst_id',
                        ],
                        'right' => [
                            'type'   => Opcode::QUALIFIED->value,
                            'domain' => 'i',
                            'name'   => 'inv_cst_id',
                        ],
                    ],
                ],
                'values' => [
                    'column' => [
                        'type'   => Opcode::QUALIFIED->value,
                        'domain' => 'i',
                        'name'   => 'inv_total',
                    ],
                    'expr'   => [
                        'type'  => Opcode::INTEGER->value,
                        'value' => '999',
                    ],
                ],
            ],
            'where'  => [
                'type'  => Opcode::EQUALS->value,
                'left'  => [
                    'type'   => Opcode::QUALIFIED->value,
                    'domain' => 'c',
                    'name'   => 'cst_id',
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
     * Tests UPDATE with an INNER JOIN used to filter the rows to update.
     *
     * @return void
     *
     * @issue  https://github.com/phalcon/cphalcon/issues/16984
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-06-06
     */
    public function testMvcModelQueryPhqlUpdateInnerJoinWhereNum(): void
    {
        $source   = "UPDATE Invoices "
            . "INNER JOIN Customers ON Customers.cst_id = Invoices.inv_cst_id "
            . "SET inv_total = 999 "
            . "WHERE Customers.cst_id = 1";
        $expected = [
            'type'   => Opcode::UPDATE->value,
            'update' => [
                'tables' => [
                    'qualifiedName' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Invoices',
                    ],
                ],
                'joins'  => [
                    'type'       => Opcode::INNERJOIN->value,
                    'qualified'  => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Customers',
                    ],
                    'conditions' => [
                        'type'  => Opcode::EQUALS->value,
                        'left'  => [
                            'type'   => Opcode::QUALIFIED->value,
                            'domain' => 'Customers',
                            'name'   => 'cst_id',
                        ],
                        'right' => [
                            'type'   => Opcode::QUALIFIED->value,
                            'domain' => 'Invoices',
                            'name'   => 'inv_cst_id',
                        ],
                    ],
                ],
                'values' => [
                    'column' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'inv_total',
                    ],
                    'expr'   => [
                        'type'  => Opcode::INTEGER->value,
                        'value' => '999',
                    ],
                ],
            ],
            'where'  => [
                'type'  => Opcode::EQUALS->value,
                'left'  => [
                    'type'   => Opcode::QUALIFIED->value,
                    'domain' => 'Customers',
                    'name'   => 'cst_id',
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
     * Tests UPDATE with an INNER JOIN, a WHERE clause and a LIMIT clause.
     *
     * @return void
     *
     * @issue  https://github.com/phalcon/cphalcon/issues/16984
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-06-06
     */
    public function testMvcModelQueryPhqlUpdateInnerJoinWhereNumLimit(): void
    {
        $source   = "UPDATE Invoices "
            . "INNER JOIN Customers ON Customers.cst_id = Invoices.inv_cst_id "
            . "SET inv_total = 999 "
            . "WHERE Customers.cst_id = 1 "
            . "LIMIT 10";
        $expected = [
            'type'   => Opcode::UPDATE->value,
            'update' => [
                'tables' => [
                    'qualifiedName' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Invoices',
                    ],
                ],
                'joins'  => [
                    'type'       => Opcode::INNERJOIN->value,
                    'qualified'  => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Customers',
                    ],
                    'conditions' => [
                        'type'  => Opcode::EQUALS->value,
                        'left'  => [
                            'type'   => Opcode::QUALIFIED->value,
                            'domain' => 'Customers',
                            'name'   => 'cst_id',
                        ],
                        'right' => [
                            'type'   => Opcode::QUALIFIED->value,
                            'domain' => 'Invoices',
                            'name'   => 'inv_cst_id',
                        ],
                    ],
                ],
                'values' => [
                    'column' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'inv_total',
                    ],
                    'expr'   => [
                        'type'  => Opcode::INTEGER->value,
                        'value' => '999',
                    ],
                ],
            ],
            'where'  => [
                'type'  => Opcode::EQUALS->value,
                'left'  => [
                    'type'   => Opcode::QUALIFIED->value,
                    'domain' => 'Customers',
                    'name'   => 'cst_id',
                ],
                'right' => [
                    'type'  => Opcode::INTEGER->value,
                    'value' => '1',
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
     * Tests UPDATE with an INNER JOIN and named placeholders.
     *
     * @return void
     *
     * @issue  https://github.com/phalcon/cphalcon/issues/16984
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-06-06
     */
    public function testMvcModelQueryPhqlUpdateInnerJoinWherePlaceholder(): void
    {
        $source   = "UPDATE Invoices "
            . "INNER JOIN Customers ON Customers.cst_id = Invoices.inv_cst_id "
            . "SET inv_total = :total: "
            . "WHERE Customers.cst_id = :custId:";
        $expected = [
            'type'   => Opcode::UPDATE->value,
            'update' => [
                'tables' => [
                    'qualifiedName' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Invoices',
                    ],
                ],
                'joins'  => [
                    'type'       => Opcode::INNERJOIN->value,
                    'qualified'  => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Customers',
                    ],
                    'conditions' => [
                        'type'  => Opcode::EQUALS->value,
                        'left'  => [
                            'type'   => Opcode::QUALIFIED->value,
                            'domain' => 'Customers',
                            'name'   => 'cst_id',
                        ],
                        'right' => [
                            'type'   => Opcode::QUALIFIED->value,
                            'domain' => 'Invoices',
                            'name'   => 'inv_cst_id',
                        ],
                    ],
                ],
                'values' => [
                    'column' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'inv_total',
                    ],
                    'expr'   => [
                        'type'  => Opcode::SPLACEHOLDER->value,
                        'value' => 'total',
                    ],
                ],
            ],
            'where'  => [
                'type'  => Opcode::EQUALS->value,
                'left'  => [
                    'type'   => Opcode::QUALIFIED->value,
                    'domain' => 'Customers',
                    'name'   => 'cst_id',
                ],
                'right' => [
                    'type'  => Opcode::SPLACEHOLDER->value,
                    'value' => 'custId',
                ],
            ],
        ];
        $actual   = (new Parser())->parse($source);
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests UPDATE with a LEFT JOIN.
     *
     * @return void
     *
     * @issue  https://github.com/phalcon/cphalcon/issues/16984
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-06-06
     */
    public function testMvcModelQueryPhqlUpdateLeftJoinWhereNum(): void
    {
        $source   = "UPDATE Invoices "
            . "LEFT JOIN Customers ON Customers.cst_id = Invoices.inv_cst_id "
            . "SET inv_total = 999 "
            . "WHERE Customers.cst_id = 1";
        $expected = [
            'type'   => Opcode::UPDATE->value,
            'update' => [
                'tables' => [
                    'qualifiedName' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Invoices',
                    ],
                ],
                'joins'  => [
                    'type'       => Opcode::LEFTJOIN->value,
                    'qualified'  => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Customers',
                    ],
                    'conditions' => [
                        'type'  => Opcode::EQUALS->value,
                        'left'  => [
                            'type'   => Opcode::QUALIFIED->value,
                            'domain' => 'Customers',
                            'name'   => 'cst_id',
                        ],
                        'right' => [
                            'type'   => Opcode::QUALIFIED->value,
                            'domain' => 'Invoices',
                            'name'   => 'inv_cst_id',
                        ],
                    ],
                ],
                'values' => [
                    'column' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'inv_total',
                    ],
                    'expr'   => [
                        'type'  => Opcode::INTEGER->value,
                        'value' => '999',
                    ],
                ],
            ],
            'where'  => [
                'type'  => Opcode::EQUALS->value,
                'left'  => [
                    'type'   => Opcode::QUALIFIED->value,
                    'domain' => 'Customers',
                    'name'   => 'cst_id',
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
     * Tests UPDATE with multiple JOINs.
     *
     * @return void
     *
     * @issue  https://github.com/phalcon/cphalcon/issues/16984
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-06-06
     */
    public function testMvcModelQueryPhqlUpdateMultipleJoinsWhereNum(): void
    {
        $source   = "UPDATE Invoices "
            . "INNER JOIN Customers ON Customers.cst_id = Invoices.inv_cst_id "
            . "INNER JOIN Orders ON Orders.ord_cst_id = Customers.cst_id "
            . "SET inv_total = 999 "
            . "WHERE Orders.ord_id = 1";
        $expected = [
            'type'   => Opcode::UPDATE->value,
            'update' => [
                'tables' => [
                    'qualifiedName' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Invoices',
                    ],
                ],
                'joins'  => [
                    0 => [
                        'type'       => Opcode::INNERJOIN->value,
                        'qualified'  => [
                            'type' => Opcode::QUALIFIED->value,
                            'name' => 'Customers',
                        ],
                        'conditions' => [
                            'type'  => Opcode::EQUALS->value,
                            'left'  => [
                                'type'   => Opcode::QUALIFIED->value,
                                'domain' => 'Customers',
                                'name'   => 'cst_id',
                            ],
                            'right' => [
                                'type'   => Opcode::QUALIFIED->value,
                                'domain' => 'Invoices',
                                'name'   => 'inv_cst_id',
                            ],
                        ],
                    ],
                    1 => [
                        'type'       => Opcode::INNERJOIN->value,
                        'qualified'  => [
                            'type' => Opcode::QUALIFIED->value,
                            'name' => 'Orders',
                        ],
                        'conditions' => [
                            'type'  => Opcode::EQUALS->value,
                            'left'  => [
                                'type'   => Opcode::QUALIFIED->value,
                                'domain' => 'Orders',
                                'name'   => 'ord_cst_id',
                            ],
                            'right' => [
                                'type'   => Opcode::QUALIFIED->value,
                                'domain' => 'Customers',
                                'name'   => 'cst_id',
                            ],
                        ],
                    ],
                ],
                'values' => [
                    'column' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'inv_total',
                    ],
                    'expr'   => [
                        'type'  => Opcode::INTEGER->value,
                        'value' => '999',
                    ],
                ],
            ],
            'where'  => [
                'type'  => Opcode::EQUALS->value,
                'left'  => [
                    'type'   => Opcode::QUALIFIED->value,
                    'domain' => 'Orders',
                    'name'   => 'ord_id',
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
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-04-09
     */
    public function testMvcModelQueryPhqlUpdateNullWhereNum(): void
    {
        $source   = "UPDATE Invoices " . "SET inv_total = NULL " . "WHERE inv_status_flag = 0";
        $expected = [
            'type' => Opcode::UPDATE->value,
            'update' => [
                'tables' => [
                    'qualifiedName' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Invoices',
                    ],
                ],
                'values' => [
                    'column' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'inv_total',
                    ],
                    'expr'   => [
                        'type' => Opcode::NULL->value,
                    ],
                ],
            ],
            'where'  => [
                'type' => Opcode::EQUALS->value,
                'left'  => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'inv_status_flag',
                ],
                'right' => [
                    'type' => Opcode::INTEGER->value,
                    'value' => '0',
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
    public function testMvcModelQueryPhqlUpdateNumLimit(): void
    {
        $source   = "UPDATE Invoices " . "SET inv_status_flag = 0 " . "LIMIT 10";
        $expected = [
            'type' => Opcode::UPDATE->value,
            'update' => [
                'tables' => [
                    'qualifiedName' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Invoices',
                    ],
                ],
                'values' => [
                    'column' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'inv_status_flag',
                    ],
                    'expr'   => [
                        'type' => Opcode::INTEGER->value,
                        'value' => '0',
                    ],
                ],
            ],
            'limit'  => [
                'number' => [
                    'type' => Opcode::INTEGER->value,
                    'value' => '10',
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
    public function testMvcModelQueryPhqlUpdateNumPlaceholderWhereNumPlaceholder(): void
    {
        $source   = "UPDATE Invoices " . "SET inv_status_flag = ?0, inv_total = ?1 " . "WHERE inv_id = ?2";
        $expected = [
            'type' => Opcode::UPDATE->value,
            'update' => [
                'tables' => [
                    'qualifiedName' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Invoices',
                    ],
                ],
                'values' => [
                    0 => [
                        'column' => [
                            'type' => Opcode::QUALIFIED->value,
                            'name' => 'inv_status_flag',
                        ],
                        'expr'   => [
                            'type' => Opcode::NPLACEHOLDER->value,
                            'value' => '?0',
                        ],
                    ],
                    1 => [
                        'column' => [
                            'type' => Opcode::QUALIFIED->value,
                            'name' => 'inv_total',
                        ],
                        'expr'   => [
                            'type' => Opcode::NPLACEHOLDER->value,
                            'value' => '?1',
                        ],
                    ],
                ],
            ],
            'where'  => [
                'type' => Opcode::EQUALS->value,
                'left'  => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'inv_id',
                ],
                'right' => [
                    'type' => Opcode::NPLACEHOLDER->value,
                    'value' => '?2',
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
    public function testMvcModelQueryPhqlUpdateNumStringWhereEqNum(): void
    {
        $source   = "UPDATE Invoices " . "SET inv_status_flag = 1, inv_title = 'Updated' " . "WHERE inv_id = 1";
        $expected = [
            'type' => Opcode::UPDATE->value,
            'update' => [
                'tables' => [
                    'qualifiedName' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Invoices',
                    ],
                ],
                'values' => [
                    0 => [
                        'column' => [
                            'type' => Opcode::QUALIFIED->value,
                            'name' => 'inv_status_flag',
                        ],
                        'expr'   => [
                            'type' => Opcode::INTEGER->value,
                            'value' => '1',
                        ],
                    ],
                    1 => [
                        'column' => [
                            'type' => Opcode::QUALIFIED->value,
                            'name' => 'inv_title',
                        ],
                        'expr'   => [
                            'type' => Opcode::STRING->value,
                            'value' => 'Updated',
                        ],
                    ],
                ],
            ],
            'where'  => [
                'type' => Opcode::EQUALS->value,
                'left'  => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'inv_id',
                ],
                'right' => [
                    'type' => Opcode::INTEGER->value,
                    'value' => '1',
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
    public function testMvcModelQueryPhqlUpdateNumWhereEqNum(): void
    {
        $source   = "UPDATE Invoices SET inv_status_flag = 1 WHERE inv_id = 1";
        $expected = [
            'type' => Opcode::UPDATE->value,
            'update' => [
                'tables' => [
                    'qualifiedName' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Invoices',
                    ],
                ],
                'values' => [
                    'column' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'inv_status_flag',
                    ],
                    'expr'   => [
                        'type' => Opcode::INTEGER->value,
                        'value' => '1',
                    ],
                ],
            ],
            'where'  => [
                'type' => Opcode::EQUALS->value,
                'left'  => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'inv_id',
                ],
                'right' => [
                    'type' => Opcode::INTEGER->value,
                    'value' => '1',
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
    public function testMvcModelQueryPhqlUpdateNumWhereIn(): void
    {
        $source   = "UPDATE Invoices " . "SET inv_status_flag = 1 " . "WHERE inv_id IN (1, 2, 3)";
        $expected = [
            'type' => Opcode::UPDATE->value,
            'update' => [
                'tables' => [
                    'qualifiedName' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Invoices',
                    ],
                ],
                'values' => [
                    'column' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'inv_status_flag',
                    ],
                    'expr'   => [
                        'type' => Opcode::INTEGER->value,
                        'value' => '1',
                    ],
                ],
            ],
            'where'  => [
                'type' => Opcode::IN->value,
                'left'  => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'inv_id',
                ],
                'right' => [
                    0 => [
                        'type' => Opcode::INTEGER->value,
                        'value' => '1',
                    ],
                    1 => [
                        'type' => Opcode::INTEGER->value,
                        'value' => '2',
                    ],
                    2 => [
                        'type' => Opcode::INTEGER->value,
                        'value' => '3',
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
    public function testMvcModelQueryPhqlUpdatePlaceholderWherePlaceholder(): void
    {
        $source   = "UPDATE Invoices " . "SET inv_title = :title: " . "WHERE inv_id = :id:";
        $expected = [
            'type' => Opcode::UPDATE->value,
            'update' => [
                'tables' => [
                    'qualifiedName' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Invoices',
                    ],
                ],
                'values' => [
                    'column' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'inv_title',
                    ],
                    'expr'   => [
                        'type' => Opcode::SPLACEHOLDER->value,
                        'value' => 'title',
                    ],
                ],
            ],
            'where'  => [
                'type' => Opcode::EQUALS->value,
                'left'  => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'inv_id',
                ],
                'right' => [
                    'type' => Opcode::SPLACEHOLDER->value,
                    'value' => 'id',
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
    public function testMvcModelQueryPhqlUpdateTrueWhereNum(): void
    {
        $source   = "UPDATE Invoices " . "SET inv_status_flag = TRUE " . "WHERE inv_id = 1";
        $expected = [
            'type' => Opcode::UPDATE->value,
            'update' => [
                'tables' => [
                    'qualifiedName' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Invoices',
                    ],
                ],
                'values' => [
                    'column' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'inv_status_flag',
                    ],
                    'expr'   => [
                        'type' => Opcode::TRUE->value,
                    ],
                ],
            ],
            'where'  => [
                'type' => Opcode::EQUALS->value,
                'left'  => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'inv_id',
                ],
                'right' => [
                    'type' => Opcode::INTEGER->value,
                    'value' => '1',
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
    public function testMvcModelQueryPhqlUpdateUpperWhereNum(): void
    {
        $source   = "UPDATE Invoices " . "SET inv_title = UPPER(inv_title) " . "WHERE inv_status_flag = 1";
        $expected = [
            'type' => Opcode::UPDATE->value,
            'update' => [
                'tables' => [
                    'qualifiedName' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Invoices',
                    ],
                ],
                'values' => [
                    'column' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'inv_title',
                    ],
                    'expr'   => [
                        'type' => Opcode::FCALL->value,
                        'name'      => 'UPPER',
                        'arguments' => [
                            0 => [
                                'type' => Opcode::QUALIFIED->value,
                                'name' => 'inv_title',
                            ],
                        ],
                    ],
                ],
            ],
            'where'  => [
                'type' => Opcode::EQUALS->value,
                'left'  => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'inv_status_flag',
                ],
                'right' => [
                    'type' => Opcode::INTEGER->value,
                    'value' => '1',
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
    public function testMvcModelQueryPhqlUpdateWhereMultipleAndConditions(): void
    {
        $source   = "UPDATE Invoices SET inv_status_flag = 1 "
            . "WHERE inv_cst_id = 1 AND inv_total > 100 AND inv_status_flag = 0";
        $expected = [
            'type' => Opcode::UPDATE->value,
            'update' => [
                'tables' => [
                    'qualifiedName' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'Invoices',
                    ],
                ],
                'values' => [
                    'column' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'inv_status_flag',
                    ],
                    'expr'   => [
                        'type' => Opcode::INTEGER->value,
                        'value' => '1',
                    ],
                ],
            ],
            'where'  => [
                'type' => Opcode::EQUALS->value,
                'left'  => [
                    'type' => Opcode::GREATER->value,
                    'left'  => [
                        'type' => Opcode::EQUALS->value,
                        'left'  => [
                            'type' => Opcode::QUALIFIED->value,
                            'name' => 'inv_cst_id',
                        ],
                        'right' => [
                            'type' => Opcode::AND->value,
                            'left'  => [
                                'type' => Opcode::INTEGER->value,
                                'value' => '1',
                            ],
                            'right' => [
                                'type' => Opcode::QUALIFIED->value,
                                'name' => 'inv_total',
                            ],
                        ],
                    ],
                    'right' => [
                        'type' => Opcode::AND->value,
                        'left'  => [
                            'type' => Opcode::INTEGER->value,
                            'value' => '100',
                        ],
                        'right' => [
                            'type' => Opcode::QUALIFIED->value,
                            'name' => 'inv_status_flag',
                        ],
                    ],
                ],
                'right' => [
                    'type' => Opcode::INTEGER->value,
                    'value' => '0',
                ],
            ],
        ];
        $actual   = (new Parser())->parse($source);
        $this->assertSame($expected, $actual);
    }
}
