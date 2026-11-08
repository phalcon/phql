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

use Phalcon\Phql\Exception;
use Phalcon\Phql\Parser;
use Phalcon\Phql\Scanner\Opcode;
use Phalcon\Phql\Tests\AbstractUnitTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

use function gc_collect_cycles;
use function str_repeat;

/**
 * The public facade behaves as cphalcon's Lang::parsePHQL() with the AST
 * cache off: the same AST, the same messages, and no result at all for a
 * statement without tokens. The parity corpus (FixturesTest) covers the
 * grammar; these tests pin the facade's contract.
 */
final class ParserTest extends AbstractUnitTestCase
{
    /**
     * @return array<string, array{0: string}>
     */
    public static function blankProvider(): array
    {
        return [
            'empty'      => [''],
            'whitespace' => [" \t\r\n"],
            'nul first'  => ["\0SELECT * FROM Robots"],
        ];
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function literalProvider(): array
    {
        return [
            'integer' => ['SELECT 1 FROM Invoices'],
            'double'  => ['SELECT 1.5 FROM Invoices'],
            'string'  => ["SELECT 'a' FROM Invoices"],
            'hex'     => ['SELECT 0x1F FROM Invoices'],
            'true'    => ['SELECT TRUE FROM Invoices'],
            'false'   => ['SELECT FALSE FROM Invoices'],
        ];
    }

    #[DataProvider('blankProvider')]
    public function testBlankStatementHasNoResult(string $phql): void
    {
        $this->assertNull((new Parser())->parse($phql));
    }

    public function testDisabledLiteralsAllowPlaceholdersAndNull(): void
    {
        $ast = (new Parser())->setEnableLiterals(false)->parse('SELECT * FROM Invoices WHERE a = :a: AND b IS NULL');

        $this->assertSame(Opcode::SELECT->value, $ast['type'] ?? null);
    }

    #[DataProvider('literalProvider')]
    public function testDisabledLiteralsAreRejected(string $phql): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Literals are disabled in PHQL statements');

        (new Parser())->setEnableLiterals(false)->parse($phql);
    }

    #[DataProvider('literalProvider')]
    public function testLiteralsAreEnabledByDefault(string $phql): void
    {
        $this->assertSame(Opcode::SELECT->value, (new Parser())->parse($phql)['type'] ?? null);
    }

    public function testLoneColonIsAnUnknownOpcode(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Scanner: Unknown opcode 58');

        (new Parser())->parse('SELECT a : b FROM Invoices');
    }

    public function testNestingIsLimited(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Parsing failed, the PHQL statement is nested too deeply');

        (new Parser())->parse('SELECT ' . str_repeat('(', 300) . '1' . str_repeat(')', 300) . ' FROM Invoices');
    }

    public function testParsesSelect(): void
    {
        $this->assertSame(
            [
                'type'   => Opcode::SELECT->value,
                'select' => [
                    'columns' => [['type' => Opcode::STARALL->value]],
                    'tables'  => ['qualifiedName' => ['type' => Opcode::QUALIFIED->value, 'name' => 'Invoices']],
                ],
            ],
            (new Parser())->parse('SELECT * FROM Invoices')
        );
    }

    /**
     * Every object a parse creates must be freed by reference counting as
     * soon as parse() returns or throws; a reference cycle would leave the
     * whole parser behind for the cycle collector on every call.
     */
    public function testParsingLeavesNoReferenceCycles(): void
    {
        gc_collect_cycles();
        $parser = new Parser();

        for ($i = 0; $i < 50; $i++) {
            $parser->setEnableLiterals(true)->parse('SELECT a FROM r WHERE b IN (SELECT c FROM s)');

            try {
                $parser->setEnableLiterals(false)->parse("SELECT * FROM r WHERE a = 'x'");
            } catch (Exception) {
                // Expected: literals are disabled.
            }
        }

        $this->assertSame(0, gc_collect_cycles());
    }

    public function testScanningErrorMessage(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage(
            "Scanning error before ' xxxxxxxxxxxxxxx...' when parsing: SELECT a # xxxxxxxxxxxxxxxxxxxx (31)"
        );

        (new Parser())->parse('SELECT a # ' . str_repeat('x', 20));
    }

    public function testSetEnableLiteralsReturnsTheParser(): void
    {
        $parser = new Parser();

        $this->assertSame($parser, $parser->setEnableLiterals(false));
    }

    public function testStringLiteralsAreUnescaped(): void
    {
        $ast = (new Parser())->parse("SELECT 'a\\nb\\'c\\%' FROM Invoices");

        $this->assertSame("a\nb'c\\%", $ast['select']['columns'][0]['column']['value'] ?? null);
    }

    public function testSyntaxErrorMessage(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage(
            "Syntax error, unexpected token EQUALS, near to ' 1', "
            . 'when parsing: SELECT * FROM Robots WHERE id = = 1 (35)'
        );

        (new Parser())->parse('SELECT * FROM Robots WHERE id = = 1');
    }

    public function testTrailingBackslashInStringIsAScanningError(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage(
            "Scanning error before 'abc\\' when parsing: SELECT * FROM Robots WHERE name = 'abc\\ (39)"
        );

        (new Parser())->parse("SELECT * FROM Robots WHERE name = 'abc\\");
    }
}
