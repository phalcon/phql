<?php

/**
 * Inputs that feed the parser no token before end of input. Lemon returns
 * without a result when its first token is EOF, so cphalcon returns null
 * instead of raising an error.
 */

declare(strict_types=1);

return [
    'empty'                 => '',
    'space'                 => ' ',
    'tabs and newlines'     => "\n\t\r\n ",
    'nul'                   => "\0",
    'nul then statement'    => "\0SELECT * FROM r",
    'space nul statement'   => " \0SELECT * FROM r",
    'nul then garbage'      => "\0\x01\x02",
    'control byte only'     => "\x01",
    'single keyword'        => 'SELECT',
    'single identifier'     => 'a',
    'single literal'        => '1',
];
