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

namespace Phalcon\Phql\Tests\Unit\Parser;

use Phalcon\Phql\Exception;
use Phalcon\Phql\Parser\Cursor;
use Phalcon\Phql\Parser\TokenStream;
use Phalcon\Phql\Scanner\Opcode;
use Phalcon\Phql\Tests\AbstractUnitTestCase;

use function str_repeat;

/**
 * The cursor counts parser stack entries the way cphalcon's Lemon parser
 * does: 100 entries including the bottom one, a shifted token takes one, a
 * reduction replaces its right-hand side by one entry, and an empty rule
 * pushes one.
 */
final class CursorTest extends AbstractUnitTestCase
{
    private const NESTED_TOO_DEEPLY = 'Parsing failed, the PHQL statement is nested too deeply';

    public function testEmptyReductionPushesAnEntry(): void
    {
        $cursor = $this->cursorOver(98);
        $this->shiftTimes($cursor, 98);
        $cursor->reduce(0);

        $this->expectExceptionMessage(self::NESTED_TOO_DEEPLY);

        $cursor->reduce(0);
    }

    public function testExpectFailsWithSyntaxErrorOnAnotherToken(): void
    {
        $cursor = new Cursor(new TokenStream('a b', true));

        $this->expectException(Exception::class);
        $this->expectExceptionMessage(
            "Syntax error, unexpected token IDENTIFIER(a), near to ' b', when parsing: a b (3)"
        );

        $cursor->expect(Opcode::COMMA->value);
    }

    public function testExpectShiftsTheExpectedToken(): void
    {
        $cursor = new Cursor(new TokenStream('a , b', true));
        $cursor->shift();

        $this->assertSame(
            [Opcode::COMMA->value, 'b'],
            [$cursor->expect(Opcode::COMMA->value)->opcode, $cursor->current()->value]
        );
    }

    public function testIsComparesTheCurrentOpcode(): void
    {
        $cursor = new Cursor(new TokenStream('a', true));

        $this->assertSame(
            [true, false],
            [$cursor->is(Opcode::IDENTIFIER->value), $cursor->is(Opcode::COMMA->value)]
        );
    }

    public function testNinetyNineEntriesFitAndTheHundredthOverflows(): void
    {
        $cursor = $this->cursorOver(100);
        $this->shiftTimes($cursor, 99);

        $this->expectExceptionMessage(self::NESTED_TOO_DEEPLY);

        $cursor->shift();
    }

    public function testReductionFreesAllButOneEntry(): void
    {
        $cursor = $this->cursorOver(102);
        $this->shiftTimes($cursor, 99);
        $cursor->reduce(3);
        $this->shiftTimes($cursor, 2);

        $this->expectExceptionMessage(self::NESTED_TOO_DEEPLY);

        $cursor->shift();
    }

    public function testShiftReturnsTheCurrentToken(): void
    {
        $cursor = new Cursor(new TokenStream('a b', true));

        $this->assertSame(['a', 'b'], [$cursor->shift()->value, $cursor->current()->value]);
    }

    /**
     * Lemon only acts on a token once the next one has been scanned, so a
     * scanning error right after a token surfaces while shifting it.
     */
    public function testShiftScansTheNextToken(): void
    {
        $cursor = new Cursor(new TokenStream("a \x01", true));

        $this->expectExceptionMessage("Scanning error before '' when parsing: a \x01 (3)");

        $cursor->shift();
    }

    private function cursorOver(int $tokens): Cursor
    {
        return new Cursor(new TokenStream(str_repeat('a ', $tokens), true));
    }

    private function shiftTimes(Cursor $cursor, int $times): void
    {
        for ($i = 0; $i < $times; $i++) {
            $cursor->shift();
        }
    }
}
