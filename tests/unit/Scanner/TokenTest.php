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

namespace Phalcon\Phql\Tests\Unit\Scanner;

use Phalcon\Phql\Scanner\Opcode;
use Phalcon\Phql\Scanner\Token;
use Phalcon\Phql\Tests\AbstractUnitTestCase;

final class TokenTest extends AbstractUnitTestCase
{
    public function testHoldsOpcodeAndValue(): void
    {
        $token = new Token(Opcode::IDENTIFIER->value, 'robots');

        $this->assertSame([Opcode::IDENTIFIER->value, 'robots'], [$token->opcode, $token->value]);
    }

    /**
     * EOF is Lemon's end-of-input token number; scanning errors use a code no
     * PHQL token can have.
     */
    public function testSentinelOpcodes(): void
    {
        $this->assertSame([0, -2], [Token::EOF, Token::SCAN_ERROR]);
    }

    public function testValueDefaultsToNull(): void
    {
        $this->assertNull((new Token(Opcode::COMMA->value))->value);
    }
}
