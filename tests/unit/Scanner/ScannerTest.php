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
use Phalcon\Phql\Scanner\Scanner;
use Phalcon\Phql\Scanner\Token;
use Phalcon\Phql\Tests\AbstractUnitTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

use function array_column;
use function count;
use function setlocale;

use const LC_CTYPE;

/**
 * Token boundaries follow cphalcon's scanner.re: the longest match wins, and
 * between matches of equal length the rule listed first.
 */
final class ScannerTest extends AbstractUnitTestCase
{
    /**
     * @return array<string, array{0: string, 1: int, 2: int}>
     */
    public static function errorProvider(): array
    {
        return [
            'unterminated string'       => ['"abc', 0, 1],
            'control byte'              => ["\x01 x", 0, 1],
            'error after tokens'        => ['a ?', 2, 3],
            'backslash without name'    => ['\\\\x', 0, 1],
            'hash alone'                => ['#', 0, 1],
            'empty braces'              => ['{}', 0, 1],
            'newline in brackets'       => ["[a\nb]", 0, 1],
            'escaped newline in string' => ["'a\\\nb'", 0, 1],
            'trailing backslash'        => ["'abc\\'", 0, 1],
            'utf-8 lead byte'           => ['ü', 0, 1],
        ];
    }

    /**
     * @return array<string, array{0: string, 1: list<array{0: int, 1: string|null}>}>
     */
    public static function tokenProvider(): array
    {
        $t = static fn (Opcode $opcode, ?string $value = null): array => [$opcode->value, $value];

        return [
            'integer'                   => ['123', [$t(Opcode::INTEGER, '123')]],
            'hex'                       => ['0x1F', [$t(Opcode::HINTEGER, '0x1F')]],
            'hex lowercase'             => ['0xab', [$t(Opcode::HINTEGER, '0xab')]],
            'bare 0x is an identifier'  => ['0x', [$t(Opcode::IDENTIFIER, '0x')]],
            'digits then hex letters'   => ['1abc', [$t(Opcode::IDENTIFIER, '1abc')]],
            'exponent is an identifier' => ['1e5', [$t(Opcode::IDENTIFIER, '1e5')]],
            'hex letters'               => ['abc', [$t(Opcode::IDENTIFIER, 'abc')]],
            'uppercase X splits'        => ['0X1F', [$t(Opcode::INTEGER, '0'), $t(Opcode::IDENTIFIER, 'X1F')]],
            'double'                    => ['1.5', [$t(Opcode::DOUBLE, '1.5')]],
            'double without integer'    => ['.5', [$t(Opcode::DOUBLE, '.5')]],
            'double without fraction'   => ['5.', [$t(Opcode::DOUBLE, '5.')]],
            'two doubles'               => ['1.2.3', [$t(Opcode::DOUBLE, '1.2'), $t(Opcode::DOUBLE, '.3')]],
            'dot'                       => [
                'a.b',
                [$t(Opcode::IDENTIFIER, 'a'), $t(Opcode::DOT), $t(Opcode::IDENTIFIER, 'b')],
            ],
            'numbered placeholder'      => ['?0', [$t(Opcode::NPLACEHOLDER, '?0')]],
            'string placeholder'        => [':a-b:', [$t(Opcode::SPLACEHOLDER, 'a-b')]],
            'bound placeholder'         => ['{a:int}', [$t(Opcode::BPLACEHOLDER, 'a:int')]],
            'lone colon'                => [':', [$t(Opcode::COLON)]],
            'leading backslash dropped' => ['\A\B', [$t(Opcode::IDENTIFIER, 'A\B')]],
            'colons inside identifier'  => ['x::int', [$t(Opcode::IDENTIFIER, 'x::int')]],
            'bracketed identifier raw'  => ['[a\]b]', [$t(Opcode::IDENTIFIER, 'a\]b')]],
            'empty brackets'            => ['[]', [$t(Opcode::IDENTIFIER, '')]],
            'string escapes'            => ["'a\\nb\\'c\\%'", [$t(Opcode::STRING, "a\nb'c\\%")]],
            'double quoted escapes'     => ['"a\\"b\\\\c\\td"', [$t(Opcode::STRING, "a\"b\\c\td")]],
            'empty string'              => ["''", [$t(Opcode::STRING, '')]],
            'raw newline in string'     => ["'a\nb'", [$t(Opcode::STRING, "a\nb")]],
            'keyword'                   => ['SELECT', [$t(Opcode::SELECT)]],
            'keyword lowercase'         => ['select', [$t(Opcode::SELECT)]],
            'keyword prefix'            => ['selected', [$t(Opcode::IDENTIFIER, 'selected')]],
            'keyword with colon'        => ['select:', [$t(Opcode::IDENTIFIER, 'select:')]],
            'not between'               => ['NOT BETWEEN', [$t(Opcode::BETWEEN_NOT)]],
            'not between then letter'   => ['NOT BETWEENX', [$t(Opcode::BETWEEN_NOT), $t(Opcode::IDENTIFIER, 'X')]],
            'not two spaces between'    => [
                'not  between',
                [$t(Opcode::NOT), $t(Opcode::IGNORE), $t(Opcode::BETWEEN)],
            ],
            'whitespace run'            => [" \t\r\n", [$t(Opcode::IGNORE)]],
            'json get text'             => ['->>', [$t(Opcode::OP_JSON_GET_TEXT)]],
            'json get'                  => ['->', [$t(Opcode::OP_JSON_GET)]],
            'json path text'            => ['#>>', [$t(Opcode::OP_JSON_PATH_TEXT)]],
            'json path'                 => ['#>', [$t(Opcode::OP_JSON_PATH)]],
            'not equals'                => ['<>', [$t(Opcode::NOTEQUALS)]],
            'bang not equals'           => ['!=', [$t(Opcode::NOTEQUALS)]],
            'bang'                      => ['!', [$t(Opcode::NOT)]],
            'contained'                 => ['<@', [$t(Opcode::OP_CONTAINED)]],
            'less equal'                => ['<=', [$t(Opcode::LESSEQUAL)]],
            'matches'                   => ['@@', [$t(Opcode::OP_MATCHES)]],
            'contains'                  => ['@>', [$t(Opcode::OP_CONTAINS)]],
            'overlaps'                  => ['&&', [$t(Opcode::OP_OVERLAPS)]],
            'concat'                    => ['||', [$t(Opcode::OP_CONCAT)]],
            'single char operators'     => [
                '+-*/%,()=<>~^&|',
                [
                    $t(Opcode::ADD), $t(Opcode::SUB), $t(Opcode::MUL), $t(Opcode::DIV), $t(Opcode::MOD),
                    $t(Opcode::COMMA), $t(Opcode::PARENTHESES_OPEN), $t(Opcode::PARENTHESES_CLOSE),
                    $t(Opcode::EQUALS), $t(Opcode::NOTEQUALS), $t(Opcode::BITWISE_NOT),
                    $t(Opcode::BITWISE_XOR), $t(Opcode::BITWISE_AND), $t(Opcode::BITWISE_OR),
                ],
            ],
            'nul ends input'            => ["a\0b", [$t(Opcode::IDENTIFIER, 'a')]],
        ];
    }

    public function testCursorFollowsEachToken(): void
    {
        $scanner = new Scanner('ab  1');
        $cursors = [];
        while ($scanner->next()->opcode !== Token::EOF) {
            $cursors[] = $scanner->cursor();
        }

        $this->assertSame([2, 4, 5], $cursors);
    }

    public function testEofSkipsPastTheNul(): void
    {
        $scanner = new Scanner("a\0b");
        $scanner->next();
        $eof = $scanner->next();

        $this->assertSame([Token::EOF, 2], [$eof->opcode, $scanner->cursor()]);
    }

    /**
     * cphalcon matches keywords byte by byte. Before PHP 8.2 strtolower()
     * follows the locale, and under a Turkish one `I` does not lower to `i`.
     * CI and the development image generate tr_TR.UTF-8; where it is missing
     * the test runs under the current locale rather than being skipped.
     */
    public function testKeywordsIgnoreTheLocale(): void
    {
        $previous = (string) setlocale(LC_CTYPE, '0');
        setlocale(LC_CTYPE, 'tr_TR.UTF-8', 'tr_TR.ISO-8859-9', 'tr_TR');

        try {
            $opcodes = array_column($this->scanAll(new Scanner('IN INTO LIMIT DISTINCT')), 0);
        } finally {
            setlocale(LC_CTYPE, $previous);
        }

        $this->assertSame(
            [
                Opcode::IN->value,
                Opcode::IGNORE->value,
                Opcode::INTO->value,
                Opcode::IGNORE->value,
                Opcode::LIMIT->value,
                Opcode::IGNORE->value,
                Opcode::DISTINCT->value,
            ],
            $opcodes
        );
    }

    #[DataProvider('errorProvider')]
    public function testScanErrorLeavesCursorAfterBadByte(string $phql, int $tokensBefore, int $cursor): void
    {
        $scanner = new Scanner($phql);
        $tokens  = $this->scanAll($scanner);

        $this->assertSame(
            [$tokensBefore, [Token::SCAN_ERROR, null], $cursor],
            [count($tokens) - 1, $tokens[count($tokens) - 1], $scanner->cursor()]
        );
    }

    /**
     * @param list<array{0: int, 1: string|null}> $expected
     */
    #[DataProvider('tokenProvider')]
    public function testScansTokens(string $phql, array $expected): void
    {
        $this->assertSame($expected, $this->scanAll(new Scanner($phql)));
    }

    /**
     * @return list<array{0: int, 1: string|null}> every token up to EOF or a scanning error
     */
    private function scanAll(Scanner $scanner): array
    {
        $tokens = [];
        do {
            $token = $scanner->next();
            if ($token->opcode !== Token::EOF) {
                $tokens[] = [$token->opcode, $token->value];
            }
        } while ($token->opcode !== Token::EOF && $token->opcode !== Token::SCAN_ERROR);

        return $tokens;
    }
}
