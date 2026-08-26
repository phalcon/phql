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

/**
 * Human readable token names, used by the generated parser to build syntax
 * error messages ("unexpected token X"). The values are the
 * Phalcon\Phql\Scanner\Opcode codes; they are written as integers because an
 * enum case value cannot be used in a static property initializer on PHP 8.1.
 */
class Tokens
{
    /**
     * @var array<string, int>
     */
    public static array $names = [
        'INTEGER'              => 258, // Opcode::INTEGER
        'DOUBLE'               => 259, // Opcode::DOUBLE
        'STRING'               => 260, // Opcode::STRING
        'IDENTIFIER'           => 265, // Opcode::IDENTIFIER
        'HEXAINTEGER'          => 414, // Opcode::HINTEGER
        'MINUS'                => 367, // Opcode::MINUS
        '+'                    => 43, // Opcode::ADD
        '-'                    => 45, // Opcode::SUB
        '*'                    => 42, // Opcode::MUL
        '/'                    => 47, // Opcode::DIV
        '&'                    => 38, // Opcode::BITWISE_AND
        '|'                    => 124, // Opcode::BITWISE_OR
        '@@'                   => 401, // Opcode::OP_MATCHES
        '@>'                   => 402, // Opcode::OP_CONTAINS
        '<@'                   => 403, // Opcode::OP_CONTAINED
        '&&'                   => 404, // Opcode::OP_OVERLAPS
        '||'                   => 405, // Opcode::OP_CONCAT
        '->'                   => 406, // Opcode::OP_JSON_GET
        '->>'                  => 416, // Opcode::OP_JSON_GET_TEXT
        '#>'                   => 417, // Opcode::OP_JSON_PATH
        '#>>'                  => 418, // Opcode::OP_JSON_PATH_TEXT
        '%%'                   => 37, // Opcode::MOD
        'AND'                  => 266, // Opcode::AND
        'OR'                   => 267, // Opcode::OR
        'LIKE'                 => 268, // Opcode::LIKE
        'ILIKE'                => 275, // Opcode::ILIKE
        'DOT'                  => 46, // Opcode::DOT
        'COLON'                => 58, // Opcode::COLON
        'COMMA'                => 269, // Opcode::COMMA
        'EQUALS'               => 61, // Opcode::EQUALS
        'NOT EQUALS'           => 270, // Opcode::NOTEQUALS
        'NOT'                  => 33, // Opcode::NOT
        '<'                    => 60, // Opcode::LESS
        '<='                   => 271, // Opcode::LESSEQUAL
        '>'                    => 62, // Opcode::GREATER
        '>='                   => 272, // Opcode::GREATEREQUAL
        '('                    => 40, // Opcode::PARENTHESES_OPEN
        ')'                    => 41, // Opcode::PARENTHESES_CLOSE
        'NUMERIC PLACEHOLDER'  => 273, // Opcode::NPLACEHOLDER
        'STRING PLACEHOLDER'   => 274, // Opcode::SPLACEHOLDER
        'UPDATE'               => 300, // Opcode::UPDATE
        'SET'                  => 301, // Opcode::SET
        'WHERE'                => 302, // Opcode::WHERE
        'DELETE'               => 303, // Opcode::DELETE
        'FROM'                 => 304, // Opcode::FROM
        'AS'                   => 305, // Opcode::AS
        'INSERT'               => 306, // Opcode::INSERT
        'INTO'                 => 307, // Opcode::INTO
        'VALUES'               => 308, // Opcode::VALUES
        'SELECT'               => 309, // Opcode::SELECT
        'ORDER'                => 310, // Opcode::ORDER
        'BY'                   => 311, // Opcode::BY
        'LIMIT'                => 312, // Opcode::LIMIT
        'OFFSET'               => 329, // Opcode::OFFSET
        'GROUP'                => 313, // Opcode::GROUP
        'HAVING'               => 314, // Opcode::HAVING
        'IN'                   => 315, // Opcode::IN
        'ON'                   => 316, // Opcode::ON
        'INNER'                => 317, // Opcode::INNER
        'JOIN'                 => 318, // Opcode::JOIN
        'LEFT'                 => 319, // Opcode::LEFT
        'RIGHT'                => 320, // Opcode::RIGHT
        'IS'                   => 321, // Opcode::IS
        'NULL'                 => 322, // Opcode::NULL
        'NOT IN'               => 323, // Opcode::NOTIN
        'CROSS'                => 324, // Opcode::CROSS
        'OUTER'                => 326, // Opcode::OUTER
        'FULL'                 => 325, // Opcode::FULL
        'ASC'                  => 327, // Opcode::ASC
        'DESC'                 => 328, // Opcode::DESC
        'BETWEEN'              => 331, // Opcode::BETWEEN
        'DISTINCT'             => 330, // Opcode::DISTINCT
        'AGAINST'              => 276, // Opcode::AGAINST
        'CAST'                 => 333, // Opcode::CAST
        'CONVERT'              => 336, // Opcode::CONVERT
        'USING'                => 337, // Opcode::USING
        'ALL'                  => 338, // Opcode::ALL
        'EXISTS'               => 408, // Opcode::EXISTS
        'CASE'                 => 409, // Opcode::CASE
        'WHEN'                 => 410, // Opcode::WHEN
        'THEN'                 => 413, // Opcode::THEN
        'ELSE'                 => 411, // Opcode::ELSE
        'END'                  => 412, // Opcode::END
        'FOR'                  => 339, // Opcode::FOR
        'WITH'                 => 415, // Opcode::WITH
    ];
}
