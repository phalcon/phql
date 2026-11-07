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

namespace Phalcon\Phql;

use Phalcon\Phql\Parser\Cursor;
use Phalcon\Phql\Parser\StatementParser;
use Phalcon\Phql\Parser\TokenStream;
use Phalcon\Phql\Scanner\Token;

/**
 * Parses a PHQL statement into cphalcon's intermediate representation, as
 * phql_internal_parse_phql() in ext/phalcon/mvc/model/query/base.c does:
 * the same AST, the same error messages, the same nesting limit.
 *
 * Unlike cphalcon's Lang::parsePHQL(), the parser keeps no cache and adds no
 * `id` key; that belongs to the caller (Phalcon\Mvc\Model\Query\Lang).
 */
final class Parser
{
    private bool $enableLiterals = true;

    /**
     * Returns the statement's AST, or null when the statement holds no token
     * at all (empty, blank, or starting with a NUL byte) - cphalcon's Lemon
     * parser returns without a result when its first token is end of input.
     *
     * @return array<string, mixed>|null
     *
     * @throws Exception on a scanning or syntax error, a disabled literal, or
     *                   a statement nested too deeply
     */
    public function parse(string $phql): ?array
    {
        $cursor = new Cursor(new TokenStream($phql, $this->enableLiterals));
        if ($cursor->is(Token::EOF)) {
            return null;
        }

        return (new StatementParser($cursor))->parseProgram();
    }

    /**
     * Whether literals (numbers, strings, TRUE/FALSE) may appear in the
     * statement; cphalcon's `phalcon.orm.enable_literals`.
     */
    public function setEnableLiterals(bool $enable): static
    {
        $this->enableLiterals = $enable;

        return $this;
    }
}
