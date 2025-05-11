<?php

declare(strict_types=1);

namespace Phalcon\Phql\Scanner;

class Token
{
    public int    $len;
    public int    $opcode;
    public string $value;
}
