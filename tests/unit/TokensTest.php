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

namespace Phalcon\Phql\Tests\Unit;

use Phalcon\Phql\Scanner\Opcode;
use Phalcon\Phql\Tests\AbstractUnitTestCase;
use Phalcon\Phql\Tokens;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * Token names come from cphalcon's base.c phql_tokens[] table; tokens it
 * does not list are printed as UNKNOWN.
 */
final class TokensTest extends AbstractUnitTestCase
{
    /**
     * @return array<string, array{0: int, 1: string}>
     */
    public static function nameProvider(): array
    {
        return [
            'identifier'           => [Opcode::IDENTIFIER->value, 'IDENTIFIER'],
            'hex integer'          => [Opcode::HINTEGER->value, 'HEXAINTEGER'],
            'mod is escaped'       => [Opcode::MOD->value, '%%'],
            'not equals'           => [Opcode::NOTEQUALS->value, 'NOT EQUALS'],
            'numeric placeholder'  => [Opcode::NPLACEHOLDER->value, 'NUMERIC PLACEHOLDER'],
            'string placeholder'   => [Opcode::SPLACEHOLDER->value, 'STRING PLACEHOLDER'],
            'json path text'       => [Opcode::OP_JSON_PATH_TEXT->value, '#>>'],
            'keyword'              => [Opcode::WITH->value, 'WITH'],
            'bound placeholder'    => [Opcode::BPLACEHOLDER->value, 'UNKNOWN'],
            'true'                 => [Opcode::TRUE->value, 'UNKNOWN'],
            'false'                => [Opcode::FALSE->value, 'UNKNOWN'],
            'not between'          => [Opcode::BETWEEN_NOT->value, 'UNKNOWN'],
            'bitwise not'          => [Opcode::BITWISE_NOT->value, 'UNKNOWN'],
            'bitwise xor'          => [Opcode::BITWISE_XOR->value, 'UNKNOWN'],
            'whitespace'           => [Opcode::IGNORE->value, 'UNKNOWN'],
            'no token'             => [0, 'UNKNOWN'],
        ];
    }

    #[DataProvider('nameProvider')]
    public function testName(int $opcode, string $name): void
    {
        $this->assertSame($name, Tokens::name($opcode));
    }
}
