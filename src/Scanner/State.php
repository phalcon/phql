<?php

declare(strict_types=1);

namespace Phalcon\Phql\Scanner;

class State
{
    public readonly string $rawBuffer;

    public int $startLength;

    private ?Opcode $activeToken = null;

    private readonly int $bufferLength;

    private int $cursor = 0;

    private ?string $start = null;

    public function __construct(string $buffer)
    {
        $this->bufferLength = strlen($buffer);
        $this->rawBuffer    = $buffer . "\0";
        $this->startLength  = mb_strlen($buffer);

        if ($this->startLength > 0) {
            $this->start = $buffer[0];
        }
    }

    public function getActiveToken(): ?Opcode
    {
        return $this->activeToken;
    }

    public function getBufferLength(): int
    {
        return $this->bufferLength;
    }

    public function getCursor(): int
    {
        return $this->cursor;
    }

    public function getRawBuffer(): string
    {
        return $this->rawBuffer;
    }

    public function getStart(): ?string
    {
        return $this->start;
    }

    public function getStartLength(): int
    {
        return $this->startLength;
    }

    public function incrementStart(int $value = 1): static
    {
        $this->cursor += $value;
        $this->start   = $this->rawBuffer[$this->cursor] ?? null;

        return $this;
    }

    public function setActiveToken(?Opcode $activeToken): static
    {
        $this->activeToken = $activeToken;

        return $this;
    }

    public function setCursor(int $cursor): static
    {
        $this->cursor = $cursor;
        $this->start  = $this->rawBuffer[$this->cursor] ?? null;

        return $this;
    }

    public function setStartLength(int $startLength): static
    {
        $this->startLength = $startLength;

        return $this;
    }
}
