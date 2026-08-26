<?php

declare(strict_types=1);

namespace Phalcon\Phql\Tests\Unit;

use Phalcon\Phql\Exception;
use Phalcon\Phql\Parser;
use Phalcon\Phql\Scanner\Opcode;
use Phalcon\Phql\Tests\AbstractUnitTestCase;
use Phalcon\Phql\Tokens;

final class ParserTest extends AbstractUnitTestCase
{
    public function testLiteralsDisabledBlocksDouble(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Literals are disabled in PHQL statements');

        (new Parser())->setEnableLiterals(false)->parse('SELECT 1.5 FROM Invoices');
    }

    public function testLiteralsDisabledBlocksFalse(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Literals are disabled in PHQL statements');

        (new Parser())->setEnableLiterals(false)->parse('SELECT FALSE FROM Invoices');
    }

    public function testLiteralsDisabledBlocksHinteger(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Literals are disabled in PHQL statements');

        (new Parser())->setEnableLiterals(false)->parse('SELECT 0xFF FROM Invoices');
    }

    public function testLiteralsDisabledBlocksInteger(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Literals are disabled in PHQL statements');

        (new Parser())->setEnableLiterals(false)->parse('SELECT 1 FROM Invoices');
    }

    public function testLiteralsDisabledBlocksString(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Literals are disabled in PHQL statements');

        (new Parser())->setEnableLiterals(false)->parse("SELECT 'hello' FROM Invoices");
    }

    public function testLiteralsDisabledBlocksTrue(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Literals are disabled in PHQL statements');

        (new Parser())->setEnableLiterals(false)->parse('SELECT TRUE FROM Invoices');
    }

    public function testLiteralsEnabledByDefault(): void
    {
        // Integer literal should parse without error when literals are enabled (default)
        $result = (new Parser())->parse('SELECT 1 FROM Invoices');
        $this->assertIsArray($result);
    }

    public function testParseEmptyStringThrows(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('PHQL statement cannot be NULL');

        (new Parser())->parse('');
    }

    public function testParseSimpleSelect(): void
    {
        $result = (new Parser())->parse('SELECT * FROM Invoices');

        $this->assertIsArray($result);
        $this->assertSame(Opcode::SELECT->value, $result['type']);
        $this->assertArrayHasKey('select', $result);
    }

    public function testScannerErrorLongMessage(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessageMatches('/Scanning error before/');
        $this->expectExceptionMessageMatches('/\.\.\./');

        (new Parser())->parse('#' . str_repeat('x', 20));
    }

    public function testScannerErrorShortMessage(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessageMatches('/Scanning error before/');

        (new Parser())->parse('#');
    }

    public function testScannerErrorTrailingBackslash(): void
    {
        $parser = new Parser();

        foreach (['"abc\\', "'abc\\"] as $literal) {
            $caught = null;

            try {
                $parser->parse('SELECT * FROM Robots WHERE name = ' . $literal);
            } catch (Exception $ex) {
                $caught = $ex;
            }

            $this->assertNotNull($caught, $literal);
            $this->assertStringContainsString('Scanning error', $caught->getMessage());
        }
    }

    public function testSetEnableLiteralsChaining(): void
    {
        // Should not throw — fluent chaining works
        $result = (new Parser())->setEnableLiterals(true)->parse('SELECT * FROM Invoices');
        $this->assertIsArray($result);
    }

    public function testSetEnableLiteralsReturnsSelf(): void
    {
        $parser = new Parser();
        $result = $parser->setEnableLiterals(false);

        $this->assertSame($parser, $result);
    }

    public function testSyntaxErrorReportsTokenName(): void
    {
        $parser = new Parser();

        // Unexpected token: the name comes from Tokens::$names.
        $caught = null;
        try {
            $parser->parse('SELECT FROM Robots');
        } catch (Exception $ex) {
            $caught = $ex;
        }
        $this->assertNotNull($caught);
        $this->assertMatchesRegularExpression('/^Syntax error, unexpected token FROM/', $caught->getMessage());

        // Unexpected end of input.
        $this->expectException(Exception::class);
        $this->expectExceptionMessageMatches('/^Syntax error, unexpected EOF/');

        $parser->parse('SELECT * FROM Robots WHERE (((');
    }

    public function testThrowsPhqlException(): void
    {
        try {
            (new Parser())->parse('');
        } catch (\Throwable $e) {
            $this->assertInstanceOf(Exception::class, $e);
            $this->assertNotInstanceOf(\RuntimeException::class, $e);
        }
    }

    public function testTokensNamesMatchOpcodes(): void
    {
        $this->assertNotEmpty(Tokens::$names);

        foreach (Tokens::$names as $name => $code) {
            $this->assertNotNull(Opcode::tryFrom($code), $name);
        }
    }

    public function testUnknownOpcodeThrows(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessageMatches('/Unknown opcode/');

        (new Parser())->parse('SELECT : FROM Invoices');
    }
}
