<?php

/**
 * This file is part of the Phalcon Framework.
 *
 * (c) Phalcon Team <team@phalcon.io>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Phalcon\Phql\Scanner;

/**
 * One scanned token: an Opcode value (or one of the sentinels below) and,
 * for identifiers, literals and placeholders, its text.
 */
final class Token
{
    /**
     * End of input: the end of the string or its first NUL byte. 0 is also
     * the end-of-input token number of cphalcon's Lemon parser.
     */
    public const EOF = 0;

    /**
     * No token matches at the cursor (cphalcon's PHQL_SCANNER_RETCODE_ERR).
     */
    public const SCAN_ERROR = -2;

    public function __construct(
        public readonly int $opcode,
        public readonly ?string $value = null,
    ) {
    }
}
