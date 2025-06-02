<?php

declare(strict_types=1);

namespace Phalcon\Phql\Parser;

use Phalcon\Phql\Scanner\State;
use Phalcon\Phql\Scanner\Token;

class Status
{
    public const PHQL_PARSING_FAILED = 0;
    public const PHQL_PARSING_OK     = 1;

    protected ?string $syntaxError = null;

    protected ?Token $token = null;

    protected bool $enableLiterals = false;

    public function __construct(
        protected State $scannerState,
        protected int $status = self::PHQL_PARSING_OK,
    ) {
    }

    public function getState(): State
    {
        return $this->scannerState;
    }

    public function setEnableLiterals(bool $enable): self
    {
        $this->enableLiterals = $enable;

        return $this;
    }

    public function getEnableLiterals(): bool
    {
        return $this->enableLiterals;
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

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function setSyntaxError(string $syntaxError): self
    {
        $this->syntaxError = $syntaxError;

        return $this;
    }

    public function setToken(Token $token): self
    {
        $this->token = $token;

        return $this;
    }
}