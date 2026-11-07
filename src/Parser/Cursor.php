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
use Phalcon\Phql\Scanner\Token;

/**
 * One token of lookahead, plus a count of the entries cphalcon's Lemon parser
 * would hold on its stack at this point of the parse.
 *
 * cphalcon's parser (the generated parser.c) has a stack of 100 entries, the
 * first being a sentinel, and fails with "nested too deeply" when a push
 * finds it full. Every shifted token pushes one entry; every reduction pops
 * its right-hand side and pushes the left-hand side back (pp_reduce calls
 * pp_shift for the goto), so a reduction of n > 0 symbols frees n - 1
 * entries and can never overflow, while an empty rule pushes one entry and
 * can. The parsers call shift() for every token Lemon would shift and
 * reduce() for every rule application whose right-hand side is not exactly
 * one symbol (those leave the count unchanged), in the order Lemon applies
 * them. The count then equals Lemon's stack depth and overflows on the same
 * token.
 */
final class Cursor
{
    /**
     * Entries above the sentinel that fit on Lemon's 100-entry stack.
     */
    public const MAX_DEPTH = 99;

    private Token $current;

    private int $depth = 0;

    public function __construct(private readonly TokenStream $stream)
    {
        $this->current = $stream->next();
    }

    public function current(): Token
    {
        return $this->current;
    }

    /**
     * Shifts the current token if it has the given opcode.
     *
     * @throws Exception syntax error on any other token
     *
     * @phpstan-impure
     */
    public function expect(int $opcode): Token
    {
        if ($this->current->opcode !== $opcode) {
            throw $this->stream->syntaxError($this->current);
        }

        return $this->shift();
    }

    public function is(int $opcode): bool
    {
        return $this->current->opcode === $opcode;
    }

    /**
     * Applies a grammar rule with a right-hand side of $length symbols.
     *
     * @throws Exception when an empty rule overflows the stack
     *
     * @phpstan-impure
     */
    public function reduce(int $length): void
    {
        if ($length === 0) {
            $this->push();

            return;
        }

        $this->depth -= $length - 1;
    }

    /**
     * Pushes the current token and scans the next one right away: Lemon acts
     * on a token only once the following token has been scanned, so a
     * scanning error after the token is reported before anything else.
     *
     * @return Token the shifted token
     *
     * @throws Exception when the stack overflows, or on the next token's
     *                   scanning, literal or opcode error
     *
     * @phpstan-impure
     */
    public function shift(): Token
    {
        $this->push();

        $shifted       = $this->current;
        $this->current = $this->stream->next();

        return $shifted;
    }

    public function syntaxError(): Exception
    {
        return $this->stream->syntaxError($this->current);
    }

    private function push(): void
    {
        if ($this->depth >= self::MAX_DEPTH) {
            throw $this->stream->stackOverflow();
        }

        $this->depth++;
    }
}
