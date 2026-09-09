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

final class MatchAgainstTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-04-09
     */
    public function testMvcModelQueryPhqlSelectAgainst(): void
    {
        $source   = "SELECT * FROM Invoices WHERE inv_title AGAINST 'invoice'";
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
                'type'  => Opcode::AGAINST->value,
                'left'  => [
                    'type' => Opcode::QUALIFIED->value,
                    'name' => 'inv_title',
                ],
                'right' => [
                    'type'  => Opcode::STRING->value,
                    'value' => 'invoice',
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
    public function testMvcModelQueryPhqlSelectMatchAgainst(): void
    {
        $source   = "SELECT * FROM Invoices WHERE MATCH(inv_title) AGAINST ('invoice')";
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
                'type'  => Opcode::AGAINST->value,
                'left'  => [
                    'type'      => Opcode::FCALL->value,
                    'name'      => 'MATCH',
                    'arguments' => [
                        0 => [
                            'type' => Opcode::QUALIFIED->value,
                            'name' => 'inv_title',
                        ],
                    ],
                ],
                'right' => [
                    'type' => Opcode::ENCLOSED->value,
                    'left' => [
                        'type'  => Opcode::STRING->value,
                        'value' => 'invoice',
                    ],
                ],
            ],
        ];
        $actual   = (new Parser())->parse($source);
        $this->assertSame($expected, $actual);
    }
}
