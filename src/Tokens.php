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

class Tokens
{
    public static array $names = [
        'INTEGER'       => Enum::PHQL_T_INTEGER,
        'DOUBLE'        => Enum::PHQL_T_DOUBLE,
        'STRING'        => Enum::PHQL_T_STRING,
        'IDENTIFIER'    => Enum::PHQL_T_IDENTIFIER,
        'MINUS'         => Enum::PHQL_T_MINUS,
        '+'             => Enum::PHQL_T_ADD,
        '-'             => Enum::PHQL_T_SUB,
        '*'             => Enum::PHQL_T_MUL,
        '/'             => Enum::PHQL_T_DIV,
        '%%'            => Enum::PHQL_T_MOD,
        '!'             => Enum::PHQL_T_NOT,
        //'~'             => Enum::PHQL_T_CONCAT,
        'AND'           => Enum::PHQL_T_AND,
        'OR'            => Enum::PHQL_T_OR,
        'DOT'           => Enum::PHQL_T_DOT,
        'COMMA'         => Enum::PHQL_T_COMMA,
        'EQUALS'        => Enum::PHQL_T_EQUALS,
        'NOT EQUALS'    => Enum::PHQL_T_NOTEQUALS,
        //'IDENTICAL'     => Enum::PHQL_T_IDENTICAL,
        //'NOT IDENTICAL' => Enum::PHQL_T_NOTIDENTICAL,
        'NOT'           => Enum::PHQL_T_NOT,
        //'RANGE'         => Enum::PHQL_T_RANGE,
        'COLON'         => Enum::PHQL_T_COLON,
        //'QUESTION MARK' => Enum::PHQL_T_QUESTION,
        '<'             => Enum::PHQL_T_LESS,
        '<='            => Enum::PHQL_T_LESSEQUAL,
        '>'             => Enum::PHQL_T_GREATER,
        '>='            => Enum::PHQL_T_GREATEREQUAL,
        '('             => Enum::PHQL_T_PARENTHESES_OPEN,
        ')'             => Enum::PHQL_T_PARENTHESES_CLOSE,
        //'['             => Enum::PHQL_T_SBRACKET_OPEN,
        //']'             => Enum::PHQL_T_SBRACKET_CLOSE,
        //'{'             => Enum::PHQL_T_CBRACKET_OPEN,
        //'}'             => Enum::PHQL_T_CBRACKET_CLOSE,
    ];
}
