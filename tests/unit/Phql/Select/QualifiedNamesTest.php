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

final class QualifiedNamesTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-04-09
     */
    public function testMvcModelQueryPhqlSelectModelNamespace(): void
    {
        $source   = "SELECT * FROM App\Models\Invoices";
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
                        'name' => 'App\\Models\\Invoices',
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
    public function testMvcModelQueryPhqlSelectModelNamespaceAliasAs(): void
    {
        $source   = "SELECT i.inv_id FROM App\Models\Invoices AS i";
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
                ],
                'tables'  => [
                    'qualifiedName' => [
                        'type' => Opcode::QUALIFIED->value,
                        'name' => 'App\\Models\\Invoices',
                    ],
                    'alias'         => 'i',
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
    public function testMvcModelQueryPhqlSelectModelNamespaceWhereInt(): void
    {
        $source   = "SELECT * FROM App\Models\Invoices WHERE inv_id = 1";
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
                        'name' => 'App\\Models\\Invoices',
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
}
