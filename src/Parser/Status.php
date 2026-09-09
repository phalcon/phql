<?php

declare(strict_types=1);

namespace Phalcon\Phql\Parser;

use Phalcon\Phql\Scanner\State;
use Phalcon\Phql\Scanner\Token;

class Status
{
    public const PHQL_PARSING_FAILED = 0;

    public const PHQL_PARSING_OK     = 1;

    /** @var array<mixed>|null $ast */
    private array | null $ast = null;

    private bool $enableLiterals = false;

    private ?string $syntaxError = null;

    private ?Token $token = null;

    public function __construct(
        private readonly State $scannerState,
        private int $status = self::PHQL_PARSING_OK,
    ) {
    }

    /** @return array<mixed>|null */
    public function getAst(): array | null
    {
        return $this->ast;
    }

    public function getEnableLiterals(): bool
    {
        return $this->enableLiterals;
    }

    public function getState(): State
    {
        return $this->scannerState;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getSyntaxError(): ?string
    {
        return $this->syntaxError;
    }

    public function getToken(): ?Token
    {
        return $this->token;
    }

    /** @param array<mixed> $ast */
    public function setAst(array $ast): static
    {
        $this->ast = $ast;

        return $this;
    }

    public function setEnableLiterals(bool $enable): static
    {
        $this->enableLiterals = $enable;

        return $this;
    }

    public function setStatus(int $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function setSyntaxError(string $syntaxError): static
    {
        $this->syntaxError = $syntaxError;

        return $this;
    }

    public function setToken(Token $token): static
    {
        $this->token = $token;

        return $this;
    }
}
