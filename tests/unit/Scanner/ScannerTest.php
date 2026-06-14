<?php

declare(strict_types=1);

namespace Phalcon\Phql\Tests\Unit\Scanner;

use Phalcon\Phql\Scanner\Opcode;
use Phalcon\Phql\Scanner\Scanner;
use Phalcon\Phql\Scanner\ScannerStatus;
use Phalcon\Phql\Scanner\State;
use Phalcon\Phql\Tests\AbstractUnitTestCase;

final class ScannerTest extends AbstractUnitTestCase
{
    public function testBitwiseOperators(): void
    {
        $opcodes = $this->scanAll('& | ~ ^');
        $this->assertSame([
            Opcode::BITWISE_AND,
            Opcode::BITWISE_OR,
            Opcode::BITWISE_NOT,
            Opcode::BITWISE_XOR,
        ], $opcodes);
    }

    public function testDialectSpecificOperators(): void
    {
        $opcodes = $this->scanAll('@@ @> <@ && || -> ->> #> #>>');
        $this->assertSame([
            Opcode::OP_MATCHES,
            Opcode::OP_CONTAINS,
            Opcode::OP_CONTAINED,
            Opcode::OP_OVERLAPS,
            Opcode::OP_CONCAT,
            Opcode::OP_JSON_GET,
            Opcode::OP_JSON_GET_TEXT,
            Opcode::OP_JSON_PATH,
            Opcode::OP_JSON_PATH_TEXT,
        ], $opcodes);
    }

    public function testDialectSpecificOperatorsDoNotBreakNeighbours(): void
    {
        $opcodes = $this->scanAll('- < > !! ->');
        $this->assertSame([
            Opcode::SUB,
            Opcode::LESS,
            Opcode::GREATER,
            Opcode::NOT,
            Opcode::NOT,
            Opcode::OP_JSON_GET,
        ], $opcodes);
    }

    public function testBracketPlaceholder(): void
    {
        $state   = new State('{id}');
        $scanner = new Scanner($state);

        $scanner->scanForToken();
        $token = $scanner->getToken();

        $this->assertSame(Opcode::BPLACEHOLDER, $token->opcode);
        $this->assertSame('id', $token->value);
    }

    public function testComparisonOperators(): void
    {
        $opcodes = $this->scanAll('= != ! < <= > >=');
        $this->assertSame([
            Opcode::EQUALS,
            Opcode::NOTEQUALS,
            Opcode::NOT,
            Opcode::LESS,
            Opcode::LESSEQUAL,
            Opcode::GREATER,
            Opcode::GREATEREQUAL,
        ], $opcodes);
    }

    public function testDoubleLiteral(): void
    {
        $state   = new State('3.14');
        $scanner = new Scanner($state);

        $scanner->scanForToken();
        $token = $scanner->getToken();

        $this->assertSame(Opcode::DOUBLE, $token->opcode);
        $this->assertSame('3.14', $token->value);
    }

    public function testDoubleQuotedString(): void
    {
        $state   = new State('"world"');
        $scanner = new Scanner($state);

        $scanner->scanForToken();
        $token = $scanner->getToken();

        $this->assertSame(Opcode::STRING, $token->opcode);
        $this->assertSame('world', $token->value);
    }

    public function testEmptyInputReturnsEof(): void
    {
        $state   = new State('');
        $scanner = new Scanner($state);

        $this->assertSame(ScannerStatus::EOF, $scanner->scanForToken());
    }

    public function testFromKeyword(): void
    {
        $opcodes = $this->scanAll('FROM');
        $this->assertSame([Opcode::FROM], $opcodes);
    }

    public function testHexIntegerLiteral(): void
    {
        $state   = new State('0xFF');
        $scanner = new Scanner($state);

        $scanner->scanForToken();
        $token = $scanner->getToken();

        $this->assertSame(Opcode::HINTEGER, $token->opcode);
    }

    public function testIdentifier(): void
    {
        $state   = new State('Invoices');
        $scanner = new Scanner($state);

        $scanner->scanForToken();
        $token = $scanner->getToken();

        $this->assertSame(Opcode::IDENTIFIER, $token->opcode);
        $this->assertSame('Invoices', $token->value);
    }

    public function testIntegerLiteral(): void
    {
        $state   = new State('42');
        $scanner = new Scanner($state);

        $scanner->scanForToken();
        $token = $scanner->getToken();

        $this->assertSame(Opcode::INTEGER, $token->opcode);
        $this->assertSame('42', $token->value);
    }

    public function testNamedPlaceholder(): void
    {
        $state   = new State(':id:');
        $scanner = new Scanner($state);

        $scanner->scanForToken();
        $token = $scanner->getToken();

        $this->assertSame(Opcode::SPLACEHOLDER, $token->opcode);
        $this->assertSame('id', $token->value);
    }

    public function testNoLegacyRetcodeConstants(): void
    {
        $this->assertFalse(defined('Phalcon\Phql\Scanner\Scanner::PHQL_SCANNER_RETCODE_EOF'));
        $this->assertFalse(defined('Phalcon\Phql\Scanner\Scanner::PHQL_SCANNER_RETCODE_ERR'));
        $this->assertFalse(defined('Phalcon\Phql\Scanner\Scanner::PHQL_SCANNER_RETCODE_IMPOSSIBLE'));
    }

    public function testNumericPlaceholder(): void
    {
        $state   = new State('?0');
        $scanner = new Scanner($state);

        $scanner->scanForToken();
        $token = $scanner->getToken();

        $this->assertSame(Opcode::NPLACEHOLDER, $token->opcode);
    }

    public function testOperators(): void
    {
        $opcodes = $this->scanAll('+ - * / %');
        $this->assertSame([
            Opcode::ADD,
            Opcode::SUB,
            Opcode::MUL,
            Opcode::DIV,
            Opcode::MOD,
        ], $opcodes);
    }

    public function testReturnTypeIsScannerStatus(): void
    {
        $state   = new State('SELECT');
        $scanner = new Scanner($state);
        $result  = $scanner->scanForToken();

        $this->assertInstanceOf(ScannerStatus::class, $result);
    }

    public function testSelectFromSequence(): void
    {
        $opcodes = $this->scanAll('SELECT * FROM Invoices');
        $this->assertSame([
            Opcode::SELECT,
            Opcode::MUL,
            Opcode::FROM,
            Opcode::IDENTIFIER,
        ], $opcodes);
    }

    public function testSelectKeyword(): void
    {
        $opcodes = $this->scanAll('SELECT');
        $this->assertSame([Opcode::SELECT], $opcodes);
    }

    public function testSingleQuotedString(): void
    {
        $state   = new State("'hello'");
        $scanner = new Scanner($state);

        $scanner->scanForToken();
        $token = $scanner->getToken();

        $this->assertSame(Opcode::STRING, $token->opcode);
        $this->assertSame('hello', $token->value);
    }

    public function testUnknownCharacterReturnsErr(): void
    {
        $state   = new State('#');
        $scanner = new Scanner($state);
        $result  = $scanner->scanForToken();

        // '#' is not a valid PHQL token — scanner returns ERR
        $this->assertSame(ScannerStatus::ERR, $result);
    }

    public function testWhereKeyword(): void
    {
        $opcodes = $this->scanAll('WHERE');
        $this->assertSame([Opcode::WHERE], $opcodes);
    }

    public function testWhitespaceProducesIgnore(): void
    {
        $state   = new State('   ');
        $scanner = new Scanner($state);

        $scanner->scanForToken();
        $this->assertSame(Opcode::IGNORE, $scanner->getToken()->opcode);
    }

    private function scanAll(string $input): array
    {
        $state   = new State($input);
        $scanner = new Scanner($state);
        $opcodes = [];

        while (($scanner->scanForToken()) === ScannerStatus::OK) {
            $token = $scanner->getToken();
            if ($token->opcode !== Opcode::IGNORE) {
                $opcodes[] = $token->opcode;
            }
        }

        return $opcodes;
    }
}
