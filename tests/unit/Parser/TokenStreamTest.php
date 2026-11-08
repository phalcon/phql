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
use Phalcon\Phql\Parser\TokenStream;
use Phalcon\Phql\Scanner\Opcode;
use Phalcon\Phql\Scanner\Token;
use Phalcon\Phql\Tests\AbstractUnitTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

use function str_repeat;

/**
 * The token loop of cphalcon's base.c and the messages of its scanner and
 * of parser.php.lemon's %syntax_error, byte for byte.
 */
final class TokenStreamTest extends AbstractUnitTestCase
{
    /**
     * @return array<string, array{0: string}>
     */
    public static function literalProvider(): array
    {
        return [
            'integer' => ['1'],
            'double'  => ['1.0'],
            'string'  => ["'a'"],
            'hex'     => ['0x1'],
            'true'    => ['TRUE'],
            'false'   => ['FALSE'],
        ];
    }

    #[DataProvider('literalProvider')]
    public function testDisabledLiteralFails(string $literal): void
    {
        $stream = new TokenStream('SELECT ' . $literal, false);
        $stream->next();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Literals are disabled in PHQL statements');

        $stream->next();
    }

    public function testDisabledLiteralsStillAllowNullAndPlaceholders(): void
    {
        $stream  = new TokenStream('NULL :a: ?0 {b}', false);
        $opcodes = [];
        while (($token = $stream->next())->opcode !== Token::EOF) {
            $opcodes[] = $token->opcode;
        }

        $this->assertSame(
            [
                Opcode::NULL->value,
                Opcode::SPLACEHOLDER->value,
                Opcode::NPLACEHOLDER->value,
                Opcode::BPLACEHOLDER->value,
            ],
            $opcodes
        );
    }

    public function testLoneColonIsAnUnknownOpcode(): void
    {
        $stream = new TokenStream('SELECT : FROM r', true);
        $stream->next();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Scanner: Unknown opcode 58');

        $stream->next();
    }

    public function testNextSkipsWhitespace(): void
    {
        $stream = new TokenStream(" a \n b ", true);

        $this->assertSame(
            ['a', 'b', Token::EOF],
            [$stream->next()->value, $stream->next()->value, $stream->next()->opcode]
        );
    }

    public function testScanningErrorAfterControlByteQuotesTheRest(): void
    {
        $this->assertScanningError(
            "SELECT \x01 FROM x",
            "Scanning error before ' FROM x' when parsing: SELECT \x01 FROM x (15)"
        );
    }

    /**
     * Before the first token start_length is 0: the excerpt is not cut, and
     * C's snprintf() buffer (64 + start_length + statement length bytes) is
     * too small for the message, which loses its end.
     */
    public function testScanningErrorAtFirstByteOverflowsCsBuffer(): void
    {
        $rest = str_repeat('b', 30);

        $this->assertScanningError(
            "\x01" . $rest,
            "Scanning error before '{$rest}' when parsing: \x01" . str_repeat('b', 24)
        );
    }

    public function testScanningErrorCutsAfterSixteenBytes(): void
    {
        $rest = str_repeat('b', 16);

        $this->assertScanningError(
            "SELECT a\x01" . $rest,
            "Scanning error before '{$rest}...' when parsing: SELECT a\x01{$rest} (25)"
        );
    }

    public function testScanningErrorOfUnterminatedString(): void
    {
        $this->assertScanningError(
            'SELECT * FROM Robots WHERE a = "abc',
            "Scanning error before 'abc' when parsing: SELECT * FROM Robots WHERE a = \"abc (35)"
        );
    }

    public function testStackOverflowMessage(): void
    {
        $this->assertSame(
            'Parsing failed, the PHQL statement is nested too deeply',
            (new TokenStream('a', true))->stackOverflow()->getMessage()
        );
    }

    public function testSyntaxErrorAfterNulQuotesPastTheNul(): void
    {
        $this->assertSyntaxError(
            "SELECT * FROM r WHERE\0x",
            6,
            "Syntax error, unexpected token WHERE, near to 'x', when parsing: SELECT * FROM r WHERE (23)"
        );
    }

    public function testSyntaxErrorAtEof(): void
    {
        $this->assertSyntaxError('SELECT', 2, 'Syntax error, unexpected EOF');
    }

    public function testSyntaxErrorOnLastByteReadsAsEof(): void
    {
        $this->assertSyntaxError('SELECT * FROM r WHERE a = =', 8, 'Syntax error, unexpected EOF');
    }

    public function testSyntaxErrorPrintsEmptyValue(): void
    {
        $this->assertSyntaxError(
            "SELECT a '' FROM r",
            3,
            "Syntax error, unexpected token STRING(), near to ' FROM r', when parsing: SELECT a '' FROM r (18)"
        );
    }

    public function testSyntaxErrorPrintsHexaIntegerName(): void
    {
        $this->assertSyntaxError(
            'SELECT a 0x1F FROM r',
            3,
            "Syntax error, unexpected token HEXAINTEGER(0x1F), near to ' FROM r', "
            . 'when parsing: SELECT a 0x1F FROM r (20)'
        );
    }

    public function testSyntaxErrorPrintsTheRestAndLength(): void
    {
        $this->assertSyntaxError(
            'SELECT * FROM Robots WHERE id = = 1',
            8,
            "Syntax error, unexpected token EQUALS, near to ' 1', "
            . 'when parsing: SELECT * FROM Robots WHERE id = = 1 (35)'
        );
    }

    public function testSyntaxErrorPrintsUnknownForUnnamedTokens(): void
    {
        $this->assertSyntaxError(
            'SELECT a TRUE FROM r',
            3,
            "Syntax error, unexpected token UNKNOWN, near to ' FROM r', when parsing: SELECT a TRUE FROM r (20)"
        );
    }

    public function testSyntaxErrorPrintsZeroValue(): void
    {
        $this->assertSyntaxError(
            'SELECT 0 0 FROM r',
            3,
            "Syntax error, unexpected token INTEGER(0), near to ' FROM r', when parsing: SELECT 0 0 FROM r (17)"
        );
    }

    private function assertScanningError(string $phql, string $message): void
    {
        $stream = new TokenStream($phql, true);

        try {
            while ($stream->next()->opcode !== Token::EOF) {
                continue;
            }
            $this->fail('no scanning error');
        } catch (Exception $ex) {
            $this->assertSame($message, $ex->getMessage());
        }
    }

    /**
     * Reports a syntax error on the nth token returned by next().
     */
    private function assertSyntaxError(string $phql, int $nth, string $message): void
    {
        $stream = new TokenStream($phql, true);
        for ($i = 1; $i < $nth; $i++) {
            $stream->next();
        }

        $this->assertSame($message, $stream->syntaxError($stream->next())->getMessage());
    }
}
