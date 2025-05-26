<?php

declare(strict_types=1);

namespace Phalcon\Phql\Parser;

class Parser
{
    private ?Token $token = null;

    private bool $debug = false;

    private string $debugFile = 'volt.txt';

    public function __construct(private string $phql)
    {
    }

    public function parse(): array
    {

    }
}
