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

use function ord;
use function str_contains;
use function strcspn;
use function strlen;
use function strncasecmp;
use function strpos;
use function strspn;
use function strtr;
use function substr;

/**
 * Turns a PHQL statement into tokens, one per call, exactly as cphalcon's
 * re2c scanner (ext/phalcon/mvc/model/query/scanner.re) does: the longest
 * match wins, and between matches of equal length the rule listed first.
 *
 * The scanner works on bytes and stops at the first NUL byte, as the C
 * scanner stops at the end of its C string. Every rule is matched with
 * strspn()/strcspn() runs from the cursor, so scanning is linear in the input
 * and never copies more than a token's own text.
 */
final class Scanner
{
    /**
     * BPLACEHOLDER = "{"[a-zA-Z0-9_:\-]+"}"
     */
    private const BPLACEHOLDER_NAME = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_:-';

    private const DIGITS = '0123456789';

    /**
     * Escape sequences phql_unescape_string() replaces; any other escape
     * keeps its backslash. strtr() replaces left to right without rescanning
     * its output, as the C loop does.
     */
    private const ESCAPES = [
        '\\n'  => "\n",
        '\\r'  => "\r",
        '\\t'  => "\t",
        '\\\\' => '\\',
        "\\'"  => "'",
        '\\"'  => '"',
    ];

    /**
     * HINTEGER = [x0-9A-Fa-f]+
     */
    private const HEX = 'x0123456789ABCDEFabcdef';

    /**
     * Characters after the first one of IDENTIFIER = [\\]?[a-zA-Z_][a-zA-Z0-9_\\:]*
     */
    private const IDENTIFIER_TAIL = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_\\:';

    /**
     * Case-insensitive keywords. Every keyword is also a valid IDENTIFIER, so
     * a word is a keyword only when the identifier match is exactly the
     * keyword (re2c prefers the longer `selected` or `select:`).
     */
    private const KEYWORDS = [
        'update'   => Opcode::UPDATE,
        'set'      => Opcode::SET,
        'delete'   => Opcode::DELETE,
        'from'     => Opcode::FROM,
        'as'       => Opcode::AS,
        'insert'   => Opcode::INSERT,
        'into'     => Opcode::INTO,
        'values'   => Opcode::VALUES,
        'where'    => Opcode::WHERE,
        'select'   => Opcode::SELECT,
        'order'    => Opcode::ORDER,
        'by'       => Opcode::BY,
        'asc'      => Opcode::ASC,
        'desc'     => Opcode::DESC,
        'group'    => Opcode::GROUP,
        'having'   => Opcode::HAVING,
        'limit'    => Opcode::LIMIT,
        'offset'   => Opcode::OFFSET,
        'not'      => Opcode::NOT,
        'and'      => Opcode::AND,
        'or'       => Opcode::OR,
        'like'     => Opcode::LIKE,
        'ilike'    => Opcode::ILIKE,
        'against'  => Opcode::AGAINST,
        'in'       => Opcode::IN,
        'on'       => Opcode::ON,
        'join'     => Opcode::JOIN,
        'inner'    => Opcode::INNER,
        'left'     => Opcode::LEFT,
        'right'    => Opcode::RIGHT,
        'cross'    => Opcode::CROSS,
        'outer'    => Opcode::OUTER,
        'full'     => Opcode::FULL,
        'is'       => Opcode::IS,
        'null'     => Opcode::NULL,
        'distinct' => Opcode::DISTINCT,
        'all'      => Opcode::ALL,
        'between'  => Opcode::BETWEEN,
        'cast'     => Opcode::CAST,
        'convert'  => Opcode::CONVERT,
        'using'    => Opcode::USING,
        'exists'   => Opcode::EXISTS,
        'true'     => Opcode::TRUE,
        'false'    => Opcode::FALSE,
        'case'     => Opcode::CASE,
        'when'     => Opcode::WHEN,
        'then'     => Opcode::THEN,
        'else'     => Opcode::ELSE,
        'end'      => Opcode::END,
        'for'      => Opcode::FOR,
        'with'     => Opcode::WITH,
    ];

    /**
     * ASCII case folding for keyword lookup. strtolower() follows the locale
     * before PHP 8.2 (under a Turkish one `I` does not lower to `i`), while
     * cphalcon compares keywords byte by byte.
     */
    private const LOWER_CASE = 'abcdefghijklmnopqrstuvwxyz';

    /**
     * SPLACEHOLDER = ":"[a-zA-Z0-9_\-]+":"
     */
    private const SPLACEHOLDER_NAME = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_-';

    private const UPPER_CASE = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /**
     * Byte offset just after the last token, cphalcon's `state->start`.
     */
    private int $cursor = 0;

    /**
     * Offset of the first NUL byte, or the length of the statement.
     */
    private readonly int $end;

    public function __construct(private readonly string $phql)
    {
        $nul       = strpos($phql, "\0");
        $this->end = $nul === false ? strlen($phql) : $nul;
    }

    /**
     * Byte offset just after the last token returned. After an EOF token it
     * is one past the NUL (or the end), after a SCAN_ERROR one past the
     * offending byte - where the C scanner leaves its cursor.
     */
    public function cursor(): int
    {
        return $this->cursor;
    }

    /**
     * Scans the next token, including whitespace (Opcode::IGNORE).
     */
    public function next(): Token
    {
        $at = $this->cursor;
        if ($at >= $this->end) {
            $this->cursor = $at + 1;

            return new Token(Token::EOF);
        }

        $char = $this->phql[$at];

        return match (true) {
            $char === ' ' || $char === "\t" || $char === "\n" || $char === "\r" => $this->emit(
                Opcode::IGNORE,
                strspn($this->phql, " \t\n\r", $at)
            ),
            $char >= '0' && $char <= '9'                                       => $this->scanNumber($at),
            ($char >= 'a' && $char <= 'z') || ($char >= 'A' && $char <= 'Z') || $char === '_'
                                                                               => $this->scanWord($at),
            $char === '\\'                                                     => $this->scanQualifiedWord($at),
            $char === "'" || $char === '"'                                     => $this->scanString($at, $char),
            $char === '['                                                      => $this->scanBracketed($at),
            $char === '?'                                                      => $this->scanNumberedPlaceholder($at),
            $char === ':'                                                      => $this->scanStringPlaceholder($at),
            $char === '{'                                                      => $this->scanBoundPlaceholder($at),
            $char === '.'                                                      => $this->scanDot($at),
            default                                                            => $this->scanOperator($at, $char),
        };
    }

    private function emit(Opcode $opcode, int $length, ?string $value = null): Token
    {
        $this->cursor += $length;

        return new Token($opcode->value, $value);
    }

    private function error(): Token
    {
        $this->cursor++;

        return new Token(Token::SCAN_ERROR);
    }

    /**
     * BPLACEHOLDER = "{"[a-zA-Z0-9_:\-]+"}"
     */
    private function scanBoundPlaceholder(int $at): Token
    {
        $name = strspn($this->phql, self::BPLACEHOLDER_NAME, $at + 1);
        if ($name === 0 || ($this->phql[$at + 1 + $name] ?? '') !== '}') {
            return $this->error();
        }

        return $this->emit(Opcode::BPLACEHOLDER, $name + 2, substr($this->phql, $at + 1, $name));
    }

    /**
     * EIDENTIFIER = '[' ([\\][\[]|[\\][\]]|PRINTABLE\[\[\]])* ']'
     *
     * The longest match ends at the last `]` that can close it: an unescaped
     * `]` is always the last candidate, an escaped one (`\]`) may also close
     * the identifier with the backslash as plain content. An unescaped `[` or
     * a control byte ends the search.
     */
    private function scanBracketed(int $at): Token
    {
        $close = null;
        $end   = $this->end;

        for ($i = $at + 1; $i < $end; $i++) {
            $byte = ord($this->phql[$i]);
            if ($byte < 0x20 || $byte === 0x7F) {
                break;
            }

            $escaped = $this->phql[$i - 1] === '\\';
            if ($byte === 0x5B && !$escaped) {
                break;
            }

            if ($byte === 0x5D) {
                $close = $i;
                if (!$escaped) {
                    break;
                }
            }
        }

        if ($close === null) {
            return $this->error();
        }

        return $this->emit(Opcode::IDENTIFIER, $close - $at + 1, substr($this->phql, $at + 1, $close - $at - 1));
    }

    /**
     * `.` is DOT unless a digit follows: DOUBLE = [0-9]*[.][0-9]+
     */
    private function scanDot(int $at): Token
    {
        $digits = strspn($this->phql, self::DIGITS, $at + 1);
        if ($digits === 0) {
            return $this->emit(Opcode::DOT, 1);
        }

        return $this->emit(Opcode::DOUBLE, 1 + $digits, substr($this->phql, $at, 1 + $digits));
    }

    /**
     * INTEGER = [0-9]+, HINTEGER = [x0-9A-Fa-f]+ and DOUBLE from a digit.
     *
     * A run of hex characters longer than the digits is HINTEGER's match:
     * cphalcon keeps it as HINTEGER only when it starts with `0x` and is
     * longer than 2, and otherwise makes it an IDENTIFIER (`1abc`, `0x`).
     */
    private function scanNumber(int $at): Token
    {
        $digits = strspn($this->phql, self::DIGITS, $at);

        if (($this->phql[$at + $digits] ?? '') === '.') {
            $length = $digits + 1 + strspn($this->phql, self::DIGITS, $at + $digits + 1);

            return $this->emit(Opcode::DOUBLE, $length, substr($this->phql, $at, $length));
        }

        $hex = strspn($this->phql, self::HEX, $at);
        if ($hex === $digits) {
            return $this->emit(Opcode::INTEGER, $digits, substr($this->phql, $at, $digits));
        }

        $value  = substr($this->phql, $at, $hex);
        $opcode = $hex > 2 && $value[0] === '0' && $value[1] === 'x' ? Opcode::HINTEGER : Opcode::IDENTIFIER;

        return $this->emit($opcode, $hex, $value);
    }

    /**
     * NPLACEHOLDER = "?"[0-9]+ (the value keeps the `?`)
     */
    private function scanNumberedPlaceholder(int $at): Token
    {
        $digits = strspn($this->phql, self::DIGITS, $at + 1);
        if ($digits === 0) {
            return $this->error();
        }

        return $this->emit(Opcode::NPLACEHOLDER, $digits + 1, substr($this->phql, $at, $digits + 1));
    }

    /**
     * Operators, longest first. `#` and `@` start no single-byte operator,
     * and every byte not listed matches no rule.
     */
    private function scanOperator(int $at, string $char): Token
    {
        $next = $this->phql[$at + 1] ?? '';

        return match ($char) {
            '+'     => $this->emit(Opcode::ADD, 1),
            '*'     => $this->emit(Opcode::MUL, 1),
            '/'     => $this->emit(Opcode::DIV, 1),
            '%'     => $this->emit(Opcode::MOD, 1),
            ','     => $this->emit(Opcode::COMMA, 1),
            '('     => $this->emit(Opcode::PARENTHESES_OPEN, 1),
            ')'     => $this->emit(Opcode::PARENTHESES_CLOSE, 1),
            '='     => $this->emit(Opcode::EQUALS, 1),
            '~'     => $this->emit(Opcode::BITWISE_NOT, 1),
            '^'     => $this->emit(Opcode::BITWISE_XOR, 1),
            '-'     => match (true) {
                $next === '>' && ($this->phql[$at + 2] ?? '') === '>' => $this->emit(Opcode::OP_JSON_GET_TEXT, 3),
                $next === '>'                                         => $this->emit(Opcode::OP_JSON_GET, 2),
                default                                               => $this->emit(Opcode::SUB, 1),
            },
            '#'     => match (true) {
                $next === '>' && ($this->phql[$at + 2] ?? '') === '>' => $this->emit(Opcode::OP_JSON_PATH_TEXT, 3),
                $next === '>'                                         => $this->emit(Opcode::OP_JSON_PATH, 2),
                default                                               => $this->error(),
            },
            '@'     => match ($next) {
                '@'     => $this->emit(Opcode::OP_MATCHES, 2),
                '>'     => $this->emit(Opcode::OP_CONTAINS, 2),
                default => $this->error(),
            },
            '<'     => match ($next) {
                '>'     => $this->emit(Opcode::NOTEQUALS, 2),
                '='     => $this->emit(Opcode::LESSEQUAL, 2),
                '@'     => $this->emit(Opcode::OP_CONTAINED, 2),
                default => $this->emit(Opcode::LESS, 1),
            },
            '>'     => $next === '=' ? $this->emit(Opcode::GREATEREQUAL, 2) : $this->emit(Opcode::GREATER, 1),
            '!'     => $next === '=' ? $this->emit(Opcode::NOTEQUALS, 2) : $this->emit(Opcode::NOT, 1),
            '&'     => $next === '&' ? $this->emit(Opcode::OP_OVERLAPS, 2) : $this->emit(Opcode::BITWISE_AND, 1),
            '|'     => $next === '|' ? $this->emit(Opcode::OP_CONCAT, 2) : $this->emit(Opcode::BITWISE_OR, 1),
            default => $this->error(),
        };
    }

    /**
     * [\\][a-zA-Z_]... is an IDENTIFIER without its leading backslash; any
     * other backslash matches no rule.
     */
    private function scanQualifiedWord(int $at): Token
    {
        $next = $this->phql[$at + 1] ?? '';
        if (!(($next >= 'a' && $next <= 'z') || ($next >= 'A' && $next <= 'Z') || $next === '_')) {
            return $this->error();
        }

        $length = 2 + strspn($this->phql, self::IDENTIFIER_TAIL, $at + 2);

        return $this->emit(Opcode::IDENTIFIER, $length, substr($this->phql, $at + 1, $length - 1));
    }

    /**
     * DSTRING = ["] (ESCQQ|ESCSEQ|ANYNOEOF\[\\"])* ["], SSTRING likewise with
     * [']: a backslash escapes any byte but a newline or NUL. Without a
     * closing quote the quote itself matches no rule.
     */
    private function scanString(int $at, string $quote): Token
    {
        $stop = $quote . "\\\0";
        $end  = $this->end;
        $i    = $at + 1;

        while (true) {
            $i += strcspn($this->phql, $stop, $i);
            if ($i >= $end) {
                return $this->error();
            }

            if ($this->phql[$i] === $quote) {
                break;
            }

            $escaped = $this->phql[$i + 1] ?? "\0";
            if ($escaped === "\n" || $escaped === "\0" || $i + 1 >= $end) {
                return $this->error();
            }

            $i += 2;
        }

        $value = substr($this->phql, $at + 1, $i - $at - 1);
        if (str_contains($value, '\\')) {
            $value = strtr($value, self::ESCAPES);
        }

        return $this->emit(Opcode::STRING, $i - $at + 1, $value);
    }

    /**
     * SPLACEHOLDER = ":"[a-zA-Z0-9_\-]+":", otherwise a lone COLON.
     */
    private function scanStringPlaceholder(int $at): Token
    {
        $name = strspn($this->phql, self::SPLACEHOLDER_NAME, $at + 1);
        if ($name === 0 || ($this->phql[$at + 1 + $name] ?? '') !== ':') {
            return $this->emit(Opcode::COLON, 1);
        }

        return $this->emit(Opcode::SPLACEHOLDER, $name + 2, substr($this->phql, $at + 1, $name));
    }

    /**
     * IDENTIFIER, a keyword, or `NOT BETWEEN` (exactly one space) from a
     * letter or underscore. HINTEGER matches no more than IDENTIFIER here
     * and makes an IDENTIFIER of the same text when it ties.
     */
    private function scanWord(int $at): Token
    {
        $length = 1 + strspn($this->phql, self::IDENTIFIER_TAIL, $at + 1);
        $word   = substr($this->phql, $at, $length);
        $lower  = strtr($word, self::UPPER_CASE, self::LOWER_CASE);

        if ($lower === 'not' && strncasecmp(substr($this->phql, $at + 3, 8), ' between', 8) === 0) {
            return $this->emit(Opcode::BETWEEN_NOT, 11);
        }

        $keyword = self::KEYWORDS[$lower] ?? null;
        if ($keyword !== null) {
            return $this->emit($keyword, $length);
        }

        return $this->emit(Opcode::IDENTIFIER, $length, $word);
    }
}
