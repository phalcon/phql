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

namespace Phalcon\Phql\Parser;

use Phalcon\Phql\Exception;
use Phalcon\Phql\Scanner\Opcode;
use Phalcon\Phql\Scanner\Scanner;
use Phalcon\Phql\Scanner\Token;
use Phalcon\Phql\Tokens;

use function sprintf;
use function strlen;
use function strpos;
use function substr;

/**
 * Feeds the parser one significant token at a time, as the token loop of
 * cphalcon's phql_internal_parse_phql() (ext/phalcon/mvc/model/query/base.c)
 * does, and builds every error message that loop and the Lemon grammar's
 * %syntax_error produce.
 *
 * For each token scanned - whitespace included - it records the token as the
 * "active" one and how many bytes follow it (`start_length`); the error
 * messages are built from these, not from the token the parser failed on.
 * End of input and scanning errors update neither.
 */
final class TokenStream
{
    private const LITERALS_DISABLED = 'Literals are disabled in PHQL statements';

    private const NESTED_TOO_DEEPLY = 'Parsing failed, the PHQL statement is nested too deeply';

    private int $activeOpcode = 0;

    private readonly int $length;

    private readonly Scanner $scanner;

    private int $startLength = 0;

    public function __construct(private readonly string $phql, private readonly bool $enableLiterals)
    {
        $this->scanner = new Scanner($phql);
        $this->length  = strlen($phql);
    }

    /**
     * The next token that is not whitespace, or the EOF token.
     *
     * @throws Exception on a scanning error, a literal while literals are
     *                   disabled, or a token the parser has no terminal for
     */
    public function next(): Token
    {
        while (true) {
            $token  = $this->scanner->next();
            $opcode = $token->opcode;

            if ($opcode === Token::EOF) {
                return $token;
            }

            if ($opcode === Token::SCAN_ERROR) {
                throw new Exception($this->scanningError());
            }

            $this->startLength  = $this->length - $this->scanner->cursor();
            $this->activeOpcode = $opcode;

            if ($opcode === Opcode::IGNORE->value) {
                continue;
            }

            if (!$this->enableLiterals && $this->isLiteral($opcode)) {
                throw new Exception(self::LITERALS_DISABLED);
            }

            // base.c hands every scanner token to the parser but COLON, which
            // reaches the `default:` branch of its switch.
            if ($opcode === Opcode::COLON->value) {
                throw new Exception(sprintf('Scanner: Unknown opcode %d', $opcode));
            }

            return $token;
        }
    }

    public function stackOverflow(): Exception
    {
        return new Exception(self::NESTED_TOO_DEEPLY);
    }

    /**
     * The %syntax_error message of parser.php.lemon. It names the last
     * token scanned and quotes the input after it, so an error on the
     * input's last byte reads as end of input. The value is printed for
     * value tokens only, even when empty or "0". The quoted parts are C
     * strings and end at the first NUL; the length counts every byte.
     */
    public function syntaxError(Token $lookahead): Exception
    {
        if ($this->startLength === 0) {
            return new Exception('Syntax error, unexpected EOF');
        }

        $name = Tokens::name($this->activeOpcode);
        if ($lookahead->value !== null) {
            $name .= '(' . $lookahead->value . ')';
        }

        return new Exception(sprintf(
            "Syntax error, unexpected token %s, near to '%s', when parsing: %s (%d)",
            $name,
            $this->cString(substr($this->phql, $this->scanner->cursor())),
            $this->cString($this->phql),
            $this->length
        ));
    }

    /**
     * A string as C's `%s` prints it: up to its first NUL byte.
     */
    private function cString(string $bytes): string
    {
        $nul = strpos($bytes, "\0");

        return $nul === false ? $bytes : substr($bytes, 0, $nul);
    }

    private function isLiteral(int $opcode): bool
    {
        return $opcode === Opcode::INTEGER->value
            || $opcode === Opcode::DOUBLE->value
            || $opcode === Opcode::STRING->value
            || $opcode === Opcode::HINTEGER->value
            || $opcode === Opcode::TRUE->value
            || $opcode === Opcode::FALSE->value;
    }

    /**
     * phql_scanner_error_msg() of base.c. It quotes the input after the
     * offending byte, cut to 16 bytes when more than 16 bytes followed the
     * previous token - the last `start_length` recorded, 0 before any token.
     *
     * C formats into a buffer of 64 + start_length + statement length bytes,
     * which is too small when the very first token fails (start_length 0)
     * and the statement is long: the message then loses its end.
     */
    private function scanningError(): string
    {
        $rest = $this->cString(substr($this->phql, $this->scanner->cursor()));
        if ($this->startLength > 16) {
            $rest = $this->cString(substr($this->phql, $this->scanner->cursor(), 16)) . '...';
        }

        $message = sprintf(
            "Scanning error before '%s' when parsing: %s (%d)",
            $rest,
            $this->cString($this->phql),
            $this->length
        );

        return substr($message, 0, 63 + $this->startLength + $this->length);
    }
}
