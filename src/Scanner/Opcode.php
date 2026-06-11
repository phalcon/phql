<?php

declare(strict_types=1);

namespace Phalcon\Phql\Scanner;

enum Opcode: int
{
    case ADD                 = 43;
    case AGAINST             = 276;
    case ALL                 = 338;
    case AND                 = 266;
    case AS                  = 305;
    case ASC                 = 327;
    case BETWEEN             = 331;
    case BETWEEN_NOT         = 332;
    case BITWISE_AND         = 38;
    case BITWISE_NOT         = 126;
    case BITWISE_OR          = 124;
    case BITWISE_XOR         = 94;
    case BPLACEHOLDER        = 277;
    case BY                  = 311;
    case CASE                = 409;
    case CAST                = 333;
    case COLON               = 58;
    case COMMA               = 269;
    case CONVERT             = 336;
    case CROSS               = 324;
    case CROSSJOIN           = 363;
    case DELETE              = 303;
    case DESC                = 328;
    case DISTINCT            = 330;
    case DIV                 = 47;
    case DOMAINALL           = 353;
    case DOT                 = 46;
    case DOUBLE              = 259;
    case ELSE                = 411;
    case ENCLOSED            = 356;
    case END                 = 412;
    case EQUALS              = 61;
    case EXISTS              = 408;
    case EXPR                = 354;
    case FALSE               = 335;
    case FCALL               = 350;
    case FOR                 = 339;
    case FROM                = 304;
    case FULL                = 325;
    case FULLJOIN            = 364;
    case GREATER             = 62;
    case GREATEREQUAL        = 272;
    case GROUP               = 313;
    case HAVING              = 314;
    case HINTEGER            = 414;
    case IDENTIFIER          = 265;
    case IGNORE              = 257;
    case ILIKE               = 275;
    case IN                  = 315;
    case INNER               = 317;
    case INNERJOIN           = 360;
    case INSERT              = 306;
    case INTEGER             = 258;
    case INTO                = 307;
    case IS                  = 321;
    case ISNOTNULL           = 366;
    case ISNULL              = 365;
    case JOIN                = 318;
    case LEFT                = 319;
    case LEFTJOIN            = 361;
    case LESS                = 60;
    case LESSEQUAL           = 271;
    case LIKE                = 268;
    case LIMIT               = 312;
    case MINUS               = 367;
    case MOD                 = 37;
    case MUL                 = 42;
    case NILIKE              = 357;
    case NLIKE               = 351;
    case NOT                 = 33;
    case NOTEQUALS           = 270;
    case NOTIN               = 323;
    case NPLACEHOLDER        = 273;
    case NULL                = 322;
    case OFFSET              = 329;
    case ON                  = 316;
    case OP_CONCAT           = 405;
    case OP_CONTAINED        = 403;
    case OP_CONTAINS         = 402;
    case OP_JSON_GET         = 406;
    case OP_JSON_GET_TEXT    = 416;
    case OP_JSON_PATH        = 417;
    case OP_JSON_PATH_TEXT   = 418;
    case OP_MATCHES          = 401;
    case OP_OVERLAPS         = 404;
    case OR                  = 267;
    case ORDER               = 310;
    case OUTER               = 326;
    case PARENTHESES_CLOSE   = 41;
    case PARENTHESES_OPEN    = 40;
    case QUALIFIED           = 355;
    case RAW_QUALIFIED       = 358;
    case RIGHT               = 320;
    case RIGHTJOIN           = 362;
    case SELECT              = 309;
    case SET                 = 301;
    case SPLACEHOLDER        = 274;
    case STARALL             = 352;
    case STRING              = 260;
    case SUB                 = 45;
    case SUBQUERY            = 407;
    case THEN                = 413;
    case TRUE                = 334;
    case UPDATE              = 300;
    case USING               = 337;
    case VALUES              = 308;
    case WHEN                = 410;
    case WHERE               = 302;
    case WITH                = 415;

    public function label(): string
    {
        return match ($this) {
            self::ADD               => '+',
            self::BITWISE_AND       => '&',
            self::BITWISE_NOT       => '~',
            self::BITWISE_OR        => '|',
            self::BITWISE_XOR       => '^',
            self::COLON             => ':',
            self::DIV               => '/',
            self::DOT               => '.',
            self::EQUALS            => '=',
            self::GREATER           => '>',
            self::GREATEREQUAL      => '>=',
            self::LESS              => '<',
            self::LESSEQUAL         => '<=',
            self::MOD               => '%',
            self::MUL               => '*',
            self::NOT               => '!',
            self::NOTEQUALS         => '<>',
            self::OP_CONCAT         => '||',
            self::OP_CONTAINED      => '<@',
            self::OP_CONTAINS       => '@>',
            self::OP_JSON_GET       => '->',
            self::OP_JSON_GET_TEXT  => '->>',
            self::OP_JSON_PATH      => '#>',
            self::OP_JSON_PATH_TEXT => '#>>',
            self::OP_MATCHES        => '@@',
            self::OP_OVERLAPS       => '&&',
            self::PARENTHESES_CLOSE => ')',
            self::PARENTHESES_OPEN  => '(',
            self::SUB               => '-',
            default                 => $this->name,
        };
    }
}
