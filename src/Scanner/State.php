<?php

declare(strict_types=1);

namespace Phalcon\Phql\Scanner;

class State
{
    public mixed $activeToken = null;
    public int $startLength;
    protected int $cursor = 0;

    protected ?string $end = null;
    protected ?string $start = null;

    public function __construct(string $buffer)
    {
        $this->rawBuffer   = $buffer;
        $this->startLength = mb_strlen($buffer);
        if ($this->startLength > 0) {
            $this->setStart($buffer[0]);
            $this->setEnd($buffer[0]);
        }
    }

    public function getActiveToken(): mixed
    {
        return $this->activeToken;
    }

    public function getCursor(): int
    {
        return $this->cursor;
    }

    public function getStart(): ?string
    {
        return $this->start;
    }

    public function getStartLength(): int
    {
        return $this->startLength;
    }

    public function incrementStart(int $value = 1): self
    {
        $this->cursor += $value;
        $this->setStart($this->rawBuffer[$this->cursor] ?? null);

        return $this;
    }

    public function setCursor(int $cursor): self
    {
        $this->cursor = $cursor;
        $this->setStart($this->rawBuffer[$this->cursor] ?? null);

        return $this;
    }

    public function setEnd(?string $end): self
    {
        $this->end = $end;

        return $this;
    }

    public function setStart(?string $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function setStartLength(int $startLength): self
    {
        $this->startLength = $startLength;

        return $this;
    }
}
