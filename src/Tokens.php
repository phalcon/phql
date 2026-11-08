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

use Phalcon\Phql\Scanner\Opcode;

/**
 * Human readable token names, used in syntax error messages ("unexpected
 * token X").
 */
final class Tokens
{
    /**
     * The name cphalcon prints for a token in "Syntax error, unexpected
     * token ..." messages: the phql_tokens[] table of base.c, in its order.
     * Tokens the table does not list (TRUE, FALSE, BPLACEHOLDER, BETWEEN_NOT,
     * `~`, `^`, whitespace) print as UNKNOWN.
     */
    public static function name(int $opcode): string
    {
        return match ($opcode) {
            Opcode::INTEGER->value           => 'INTEGER',
            Opcode::DOUBLE->value            => 'DOUBLE',
            Opcode::STRING->value            => 'STRING',
            Opcode::IDENTIFIER->value        => 'IDENTIFIER',
            Opcode::HINTEGER->value          => 'HEXAINTEGER',
            Opcode::MINUS->value             => 'MINUS',
            Opcode::ADD->value               => '+',
            Opcode::SUB->value               => '-',
            Opcode::MUL->value               => '*',
            Opcode::DIV->value               => '/',
            Opcode::BITWISE_AND->value       => '&',
            Opcode::BITWISE_OR->value        => '|',
            Opcode::OP_MATCHES->value        => '@@',
            Opcode::OP_CONTAINS->value       => '@>',
            Opcode::OP_CONTAINED->value      => '<@',
            Opcode::OP_OVERLAPS->value       => '&&',
            Opcode::OP_CONCAT->value         => '||',
            Opcode::OP_JSON_GET->value       => '->',
            Opcode::OP_JSON_GET_TEXT->value  => '->>',
            Opcode::OP_JSON_PATH->value      => '#>',
            Opcode::OP_JSON_PATH_TEXT->value => '#>>',
            Opcode::MOD->value               => '%%',
            Opcode::AND->value               => 'AND',
            Opcode::OR->value                => 'OR',
            Opcode::LIKE->value              => 'LIKE',
            Opcode::ILIKE->value             => 'ILIKE',
            Opcode::DOT->value               => 'DOT',
            Opcode::COLON->value             => 'COLON',
            Opcode::COMMA->value             => 'COMMA',
            Opcode::EQUALS->value            => 'EQUALS',
            Opcode::NOTEQUALS->value         => 'NOT EQUALS',
            Opcode::NOT->value               => 'NOT',
            Opcode::LESS->value              => '<',
            Opcode::LESSEQUAL->value         => '<=',
            Opcode::GREATER->value           => '>',
            Opcode::GREATEREQUAL->value      => '>=',
            Opcode::PARENTHESES_OPEN->value  => '(',
            Opcode::PARENTHESES_CLOSE->value => ')',
            Opcode::NPLACEHOLDER->value      => 'NUMERIC PLACEHOLDER',
            Opcode::SPLACEHOLDER->value      => 'STRING PLACEHOLDER',
            Opcode::UPDATE->value            => 'UPDATE',
            Opcode::SET->value               => 'SET',
            Opcode::WHERE->value             => 'WHERE',
            Opcode::DELETE->value            => 'DELETE',
            Opcode::FROM->value              => 'FROM',
            Opcode::AS->value                => 'AS',
            Opcode::INSERT->value            => 'INSERT',
            Opcode::INTO->value              => 'INTO',
            Opcode::VALUES->value            => 'VALUES',
            Opcode::SELECT->value            => 'SELECT',
            Opcode::ORDER->value             => 'ORDER',
            Opcode::BY->value                => 'BY',
            Opcode::LIMIT->value             => 'LIMIT',
            Opcode::OFFSET->value            => 'OFFSET',
            Opcode::GROUP->value             => 'GROUP',
            Opcode::HAVING->value            => 'HAVING',
            Opcode::IN->value                => 'IN',
            Opcode::ON->value                => 'ON',
            Opcode::INNER->value             => 'INNER',
            Opcode::JOIN->value              => 'JOIN',
            Opcode::LEFT->value              => 'LEFT',
            Opcode::RIGHT->value             => 'RIGHT',
            Opcode::IS->value                => 'IS',
            Opcode::NULL->value              => 'NULL',
            Opcode::NOTIN->value             => 'NOT IN',
            Opcode::CROSS->value             => 'CROSS',
            Opcode::OUTER->value             => 'OUTER',
            Opcode::FULL->value              => 'FULL',
            Opcode::ASC->value               => 'ASC',
            Opcode::DESC->value              => 'DESC',
            Opcode::BETWEEN->value           => 'BETWEEN',
            Opcode::DISTINCT->value          => 'DISTINCT',
            Opcode::AGAINST->value           => 'AGAINST',
            Opcode::CAST->value              => 'CAST',
            Opcode::CONVERT->value           => 'CONVERT',
            Opcode::USING->value             => 'USING',
            Opcode::ALL->value               => 'ALL',
            Opcode::EXISTS->value            => 'EXISTS',
            Opcode::CASE->value              => 'CASE',
            Opcode::WHEN->value              => 'WHEN',
            Opcode::THEN->value              => 'THEN',
            Opcode::ELSE->value              => 'ELSE',
            Opcode::END->value               => 'END',
            Opcode::FOR->value               => 'FOR',
            Opcode::WITH->value              => 'WITH',
            default                          => 'UNKNOWN',
        };
    }
}
