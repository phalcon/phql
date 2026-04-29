<?php

declare(strict_types=1);

namespace Phalcon\Phql;

use Phalcon\Phql\Parser\Status;
use Phalcon\Phql\Scanner\Opcode;
use Phalcon\Phql\Scanner\Scanner;
use Phalcon\Phql\Scanner\ScannerStatus;
use Phalcon\Phql\Scanner\State;
use Phalcon\Phql\Scanner\Token;

/**
 * Orchestrates the PHQL lexer and parser, equivalent to
 * phql_internal_parse_phql() in base.c.
 */
final class Parser
{
    /** @var array<string, array<string, mixed>> */
    private static array $parserCache = [];

    private bool $enableLiterals = true;

    /**
     * Parse a PHQL string and return the AST array.
     *
     * @return array<string, mixed>
     * @throws Exception on syntax or scanner error
     */
    public function parse(string $phql): array
    {
        if ($phql === '') {
            throw new Exception('PHQL statement cannot be NULL');
        }

        if (isset(self::$parserCache[$phql])) {
            return self::$parserCache[$phql];
        }

        $state        = new State($phql);
        $scanner      = new Scanner($state);
        $token        = $scanner->getToken();
        $status       = new Status($state);
        $parserObject = new \phql_Parser($status);

        $status->setToken($token);
        $status->setEnableLiterals($this->enableLiterals);

        $errorMessage = null;
        $parseFailed  = false;

        while (($scannerStatus = $scanner->scanForToken()) === ScannerStatus::OK) {
            $state->setStartLength(mb_strlen($phql) - $state->getCursor());
            $state->setActiveToken($scanner->getToken()->opcode);

            match ($scanner->getToken()->opcode) {
                Opcode::IGNORE => null,

                Opcode::ADD          => $parserObject->phql_(\phql_Parser::PHQL_PLUS),
                Opcode::SUB          => $parserObject->phql_(\phql_Parser::PHQL_MINUS),
                Opcode::MUL          => $parserObject->phql_(\phql_Parser::PHQL_TIMES),
                Opcode::DIV          => $parserObject->phql_(\phql_Parser::PHQL_DIVIDE),
                Opcode::MOD          => $parserObject->phql_(\phql_Parser::PHQL_MOD),
                Opcode::AND          => $parserObject->phql_(\phql_Parser::PHQL_AND),
                Opcode::OR           => $parserObject->phql_(\phql_Parser::PHQL_OR),
                Opcode::EQUALS       => $parserObject->phql_(\phql_Parser::PHQL_EQUALS),
                Opcode::NOTEQUALS    => $parserObject->phql_(\phql_Parser::PHQL_NOTEQUALS),
                Opcode::LESS         => $parserObject->phql_(\phql_Parser::PHQL_LESS),
                Opcode::GREATER      => $parserObject->phql_(\phql_Parser::PHQL_GREATER),
                Opcode::GREATEREQUAL => $parserObject->phql_(\phql_Parser::PHQL_GREATEREQUAL),
                Opcode::LESSEQUAL    => $parserObject->phql_(\phql_Parser::PHQL_LESSEQUAL),
                Opcode::DOT          => $parserObject->phql_(\phql_Parser::PHQL_DOT),
                Opcode::COMMA        => $parserObject->phql_(\phql_Parser::PHQL_COMMA),
                Opcode::PARENTHESES_OPEN  => $parserObject->phql_(\phql_Parser::PHQL_PARENTHESES_OPEN),
                Opcode::PARENTHESES_CLOSE => $parserObject->phql_(\phql_Parser::PHQL_PARENTHESES_CLOSE),
                Opcode::LIKE         => $parserObject->phql_(\phql_Parser::PHQL_LIKE),
                Opcode::ILIKE        => $parserObject->phql_(\phql_Parser::PHQL_ILIKE),
                Opcode::NOT          => $parserObject->phql_(\phql_Parser::PHQL_NOT),
                Opcode::BITWISE_AND  => $parserObject->phql_(\phql_Parser::PHQL_BITWISE_AND),
                Opcode::BITWISE_OR   => $parserObject->phql_(\phql_Parser::PHQL_BITWISE_OR),
                Opcode::BITWISE_NOT  => $parserObject->phql_(\phql_Parser::PHQL_BITWISE_NOT),
                Opcode::BITWISE_XOR  => $parserObject->phql_(\phql_Parser::PHQL_BITWISE_XOR),
                Opcode::AGAINST      => $parserObject->phql_(\phql_Parser::PHQL_AGAINST),
                Opcode::CASE         => $parserObject->phql_(\phql_Parser::PHQL_CASE),
                Opcode::WHEN         => $parserObject->phql_(\phql_Parser::PHQL_WHEN),
                Opcode::THEN         => $parserObject->phql_(\phql_Parser::PHQL_THEN),
                Opcode::END          => $parserObject->phql_(\phql_Parser::PHQL_END),
                Opcode::ELSE         => $parserObject->phql_(\phql_Parser::PHQL_ELSE),
                Opcode::FOR          => $parserObject->phql_(\phql_Parser::PHQL_FOR),
                Opcode::WITH         => $parserObject->phql_(\phql_Parser::PHQL_WITH),
                Opcode::FROM         => $parserObject->phql_(\phql_Parser::PHQL_FROM),
                Opcode::UPDATE       => $parserObject->phql_(\phql_Parser::PHQL_UPDATE),
                Opcode::SET          => $parserObject->phql_(\phql_Parser::PHQL_SET),
                Opcode::WHERE        => $parserObject->phql_(\phql_Parser::PHQL_WHERE),
                Opcode::DELETE       => $parserObject->phql_(\phql_Parser::PHQL_DELETE),
                Opcode::INSERT       => $parserObject->phql_(\phql_Parser::PHQL_INSERT),
                Opcode::INTO         => $parserObject->phql_(\phql_Parser::PHQL_INTO),
                Opcode::VALUES       => $parserObject->phql_(\phql_Parser::PHQL_VALUES),
                Opcode::SELECT       => $parserObject->phql_(\phql_Parser::PHQL_SELECT),
                Opcode::AS           => $parserObject->phql_(\phql_Parser::PHQL_AS),
                Opcode::ORDER        => $parserObject->phql_(\phql_Parser::PHQL_ORDER),
                Opcode::BY           => $parserObject->phql_(\phql_Parser::PHQL_BY),
                Opcode::LIMIT        => $parserObject->phql_(\phql_Parser::PHQL_LIMIT),
                Opcode::OFFSET       => $parserObject->phql_(\phql_Parser::PHQL_OFFSET),
                Opcode::GROUP        => $parserObject->phql_(\phql_Parser::PHQL_GROUP),
                Opcode::HAVING       => $parserObject->phql_(\phql_Parser::PHQL_HAVING),
                Opcode::ASC          => $parserObject->phql_(\phql_Parser::PHQL_ASC),
                Opcode::DESC         => $parserObject->phql_(\phql_Parser::PHQL_DESC),
                Opcode::IN           => $parserObject->phql_(\phql_Parser::PHQL_IN),
                Opcode::ON           => $parserObject->phql_(\phql_Parser::PHQL_ON),
                Opcode::INNER        => $parserObject->phql_(\phql_Parser::PHQL_INNER),
                Opcode::JOIN         => $parserObject->phql_(\phql_Parser::PHQL_JOIN),
                Opcode::LEFT         => $parserObject->phql_(\phql_Parser::PHQL_LEFT),
                Opcode::RIGHT        => $parserObject->phql_(\phql_Parser::PHQL_RIGHT),
                Opcode::CROSS        => $parserObject->phql_(\phql_Parser::PHQL_CROSS),
                Opcode::FULL         => $parserObject->phql_(\phql_Parser::PHQL_FULL),
                Opcode::OUTER        => $parserObject->phql_(\phql_Parser::PHQL_OUTER),
                Opcode::IS           => $parserObject->phql_(\phql_Parser::PHQL_IS),
                Opcode::NULL         => $parserObject->phql_(\phql_Parser::PHQL_NULL),
                Opcode::BETWEEN      => $parserObject->phql_(\phql_Parser::PHQL_BETWEEN),
                Opcode::BETWEEN_NOT  => $parserObject->phql_(\phql_Parser::PHQL_BETWEEN_NOT),
                Opcode::DISTINCT     => $parserObject->phql_(\phql_Parser::PHQL_DISTINCT),
                Opcode::ALL          => $parserObject->phql_(\phql_Parser::PHQL_ALL),
                Opcode::CAST         => $parserObject->phql_(\phql_Parser::PHQL_CAST),
                Opcode::CONVERT      => $parserObject->phql_(\phql_Parser::PHQL_CONVERT),
                Opcode::USING        => $parserObject->phql_(\phql_Parser::PHQL_USING),
                Opcode::EXISTS       => $parserObject->phql_(\phql_Parser::PHQL_EXISTS),

                Opcode::IDENTIFIER  => $parserObject->phql_(
                    \phql_Parser::PHQL_IDENTIFIER,
                    $this->makeParserToken($scanner->getToken())
                ),
                Opcode::NPLACEHOLDER => $parserObject->phql_(
                    \phql_Parser::PHQL_NPLACEHOLDER,
                    $this->makeParserToken($scanner->getToken())
                ),
                Opcode::SPLACEHOLDER => $parserObject->phql_(
                    \phql_Parser::PHQL_SPLACEHOLDER,
                    $this->makeParserToken($scanner->getToken())
                ),
                Opcode::BPLACEHOLDER => $parserObject->phql_(
                    \phql_Parser::PHQL_BPLACEHOLDER,
                    $this->makeParserToken($scanner->getToken())
                ),

                Opcode::INTEGER  => $this->enableLiterals
                    ? $parserObject->phql_(\phql_Parser::PHQL_INTEGER, $this->makeParserToken($scanner->getToken()))
                    : $this->handleLiteralsDisabled($status),
                Opcode::DOUBLE   => $this->enableLiterals
                    ? $parserObject->phql_(\phql_Parser::PHQL_DOUBLE, $this->makeParserToken($scanner->getToken()))
                    : $this->handleLiteralsDisabled($status),
                Opcode::STRING   => $this->enableLiterals
                    ? $parserObject->phql_(\phql_Parser::PHQL_STRING, $this->makeParserToken($scanner->getToken()))
                    : $this->handleLiteralsDisabled($status),
                Opcode::HINTEGER => $this->enableLiterals
                    ? $parserObject->phql_(\phql_Parser::PHQL_HINTEGER, $this->makeParserToken($scanner->getToken()))
                    : $this->handleLiteralsDisabled($status),
                Opcode::TRUE     => $this->enableLiterals
                    ? $parserObject->phql_(\phql_Parser::PHQL_TRUE)
                    : $this->handleLiteralsDisabled($status),
                Opcode::FALSE    => $this->enableLiterals
                    ? $parserObject->phql_(\phql_Parser::PHQL_FALSE)
                    : $this->handleLiteralsDisabled($status),

                default => $this->handleUnknownOpcode($scanner->getToken()->opcode, $status),
            };

            if ($status->getStatus() !== Status::PHQL_PARSING_OK) {
                $parseFailed = true;
                break;
            }
        }

        if (!$parseFailed) {
            if (
                $scannerStatus === ScannerStatus::ERR
                || $scannerStatus === ScannerStatus::IMPOSSIBLE
            ) {
                $errorMessage = $this->buildScannerErrorMessage($status, $phql);
                $parseFailed  = true;
            } else {
                $parserObject->phql_(0);
            }
        }

        $state->setActiveToken(null);

        if ($status->getStatus() !== Status::PHQL_PARSING_OK) {
            $parseFailed = true;
            if ($status->getSyntaxError() !== null && $errorMessage === null) {
                $errorMessage = $status->getSyntaxError();
            }
        }

        if ($parseFailed) {
            throw new Exception($errorMessage ?? 'Unknown PHQL parsing error');
        }

        /** @var array<string, mixed>|null $ast */
        $ast = $status->getAst();
        if (!is_array($ast)) {
            throw new Exception('PHQL parsing produced no result'); // @codeCoverageIgnore
        }

        self::$parserCache[$phql] = $ast;

        return $ast;
    }

    public static function clean(): void
    {
        self::$parserCache = [];
    }

    public function setEnableLiterals(bool $enable): static
    {
        $this->enableLiterals = $enable;

        return $this;
    }

    /**
     * Mirrors phql_scanner_error_msg() in base.c.
     */
    private function buildScannerErrorMessage(Status $status, string $phql): string
    {
        $state      = $status->getState();
        $phqlLength = mb_strlen($phql);

        if ($state->getStart() !== null && $state->getStartLength() > 0) {
            $startStr = substr($phql, $state->getCursor());
            if ($state->getStartLength() > 16) {
                $errorPart = substr($startStr, 0, 16);

                return sprintf(
                    "Scanning error before '%s...' when parsing: %s (%d)",
                    $errorPart,
                    $phql,
                    $phqlLength
                );
            }

            return sprintf(
                "Scanning error before '%s' when parsing: %s (%d)",
                $startStr,
                $phql,
                $phqlLength
            );
        }

        return 'Scanning error near to EOF'; // @codeCoverageIgnore
    }

    private function handleLiteralsDisabled(Status $status): void
    {
        $status->setSyntaxError('Literals are disabled in PHQL statements');
        $status->setStatus(Status::PHQL_PARSING_FAILED);
    }

    private function handleUnknownOpcode(?Opcode $opcode, Status $status): void
    {
        $status->setStatus(Status::PHQL_PARSING_FAILED);

        throw new Exception(
            sprintf('Scanner: Unknown opcode %d', $opcode->value ?? 0)
        );
    }

    /**
     * Snapshot the current scanner token into a new Token instance so the
     * parser stack holds stable values (the scanner reuses its token object).
     * Mirrors phql_parse_with_token() in base.c.
     */
    private function makeParserToken(Token $token): Token
    {
        return new Token(
            $token->opcode,
            $token->value,
            $token->length,
        );
    }
}
