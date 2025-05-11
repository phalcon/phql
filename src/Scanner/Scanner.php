<?php

declare(strict_types=1);

namespace Phalcon\Phql\Scanner;

use Phalcon\Phql\Scanner\State;
use Phalcon\Phql\Scanner\Token;

/* scanner.rex
 * This file is part of the Phalcon Framework.
 *
 * (c) Phalcon Team <team@phalcon.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

#ifdef HAVE_CONFIG_H
#include "config.h"
#endif

#include "php.h"
#include "php_phalcon.h"

#include "scanner.h"

#define YYCTYPE unsigned char
#define YYCURSOR (s->start)
#define YYLIMIT (s->end)
#define YYMARKER q

class Scanner
{
    public const PHQL_SCANNER_RETCODE_EOF        = -1;
    public const PHQL_SCANNER_RETCODE_ERR        = -2;
    public const PHQL_SCANNER_RETCODE_IMPOSSIBLE = -3;

    private Token $token;

    public function __construct(private State $state)
    {
        $this->token = new Token();
    }

    public function getToken(): Token
    {
        return $this->token;
    }

    public function scanForToken(): int
    {
        $start = $this->state->getCursor();
        $status = self::PHQL_SCANNER_RETCODE_IMPOSSIBLE;


        while (self::PHQL_SCANNER_RETCODE_IMPOSSIBLE == $status) {
            $yych     = 0;
            $yyaccept = 0;
            $yystate  = 0;
            while (true) {
                switch ($yystate) {
                    case 0:
                        $yych     = $yyinput[$yycursor];
                        $yycursor += 1;
                        switch ($yych) {
                            case 0x00:
                                $yystate = 1;
                                break 2;
                            case '\t':
                            case '\n':
                            case '\r':
                            case ' ':
                                $yystate = 4;
                                break 2;
                            case '!':
                                $yystate = 6;
                                break 2;
                            case '"':
                                $yystate = 8;
                                break 2;
                            case '%':
                                $yystate = 9;
                                break 2;
                            case '&':
                                $yystate = 10;
                                break 2;
                            case '\'':
                                $yystate = 12;
                                break 2;
                            case '(':
                                $yystate = 13;
                                break 2;
                            case ')':
                                $yystate = 14;
                                break 2;
                            case '*':
                                $yystate = 15;
                                break 2;
                            case '+':
                                $yystate = 16;
                                break 2;
                            case ',':
                                $yystate = 17;
                                break 2;
                            case '-':
                                $yystate = 18;
                                break 2;
                            case '.':
                                $yystate = 19;
                                break 2;
                            case '/':
                                $yystate = 21;
                                break 2;
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                                $yystate = 22;
                                break 2;
                            case ':':
                                $yystate = 24;
                                break 2;
                            case '<':
                                $yystate = 26;
                                break 2;
                            case '=':
                                $yystate = 28;
                                break 2;
                            case '>':
                                $yystate = 29;
                                break 2;
                            case '?':
                                $yystate = 31;
                                break 2;
                            case '@':
                                $yystate = 32;
                                break 2;
                            case 'A':
                            case 'a':
                                $yystate = 33;
                                break 2;
                            case 'B':
                            case 'b':
                                $yystate = 35;
                                break 2;
                            case 'C':
                            case 'c':
                                $yystate = 36;
                                break 2;
                            case 'D':
                            case 'd':
                                $yystate = 37;
                                break 2;
                            case 'E':
                            case 'e':
                                $yystate = 38;
                                break 2;
                            case 'F':
                            case 'f':
                                $yystate = 39;
                                break 2;
                            case 'G':
                            case 'g':
                                $yystate = 40;
                                break 2;
                            case 'H':
                            case 'h':
                                $yystate = 42;
                                break 2;
                            case 'I':
                            case 'i':
                                $yystate = 43;
                                break 2;
                            case 'J':
                            case 'j':
                                $yystate = 44;
                                break 2;
                            case 'K':
                            case 'M':
                            case 'P':
                            case 'Q':
                            case 'X':
                            case 'Y':
                            case 'Z':
                            case '_':
                            case 'k':
                            case 'm':
                            case 'p':
                            case 'q':
                            case 'y':
                            case 'z':
                                $yystate = 45;
                                break 2;
                            case 'L':
                            case 'l':
                                $yystate = 47;
                                break 2;
                            case 'N':
                            case 'n':
                                $yystate = 48;
                                break 2;
                            case 'O':
                            case 'o':
                                $yystate = 49;
                                break 2;
                            case 'R':
                            case 'r':
                                $yystate = 50;
                                break 2;
                            case 'S':
                            case 's':
                                $yystate = 51;
                                break 2;
                            case 'T':
                            case 't':
                                $yystate = 52;
                                break 2;
                            case 'U':
                            case 'u':
                                $yystate = 53;
                                break 2;
                            case 'V':
                            case 'v':
                                $yystate = 54;
                                break 2;
                            case 'W':
                            case 'w':
                                $yystate = 55;
                                break 2;
                            case '[':
                                $yystate = 56;
                                break 2;
                            case '\\':
                                $yystate = 57;
                                break 2;
                            case '^':
                                $yystate = 58;
                                break 2;
                            case 'x':
                                $yystate = 59;
                                break 2;
                            case '{':
                                $yystate = 61;
                                break 2;
                            case '|':
                                $yystate = 62;
                                break 2;
                            case '~':
                                $yystate = 64;
                                break 2;
                            default:
                                $yystate = 2;
                                break 2;
                        }
                    case 1:

                        $status = Opcode::PHQL_SCANNER_RETCODE_EOF;
                        break;

                    case 2:
                        $yystate = 3;
                        break 2;
                    case 3:

                        $status = Opcode::PHQL_SCANNER_RETCODE_ERR;
                        break;

                    case 4:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '\t':
                            case '\n':
                            case '\r':
                            case ' ':
                                $yycursor += 1;
                                $yystate  = 4;
                                break 2;
                            default:
                                $yystate = 5;
                                break 2;
                        }
                    case 5:

                        $token->opcode = Opcode::PHQL_T_IGNORE;
                        return 0;

                    case 6:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '!':
                                $yycursor += 1;
                                $yystate  = 65;
                                break 2;
                            case '=':
                                $yycursor += 1;
                                $yystate  = 66;
                                break 2;
                            default:
                                $yystate = 7;
                                break 2;
                        }
                    case 7:

                        $token->opcode = Opcode::PHQL_T_NOT;
                        return 0;

                    case 8:
                        $yyaccept = 0;
                        $yymarker = $yycursor;
                        $yych     = $yyinput[$yycursor];
                        if ($yych <= 0x00) {
                            $yystate = 3;
                            break 2;
                        }
                        $yystate = 68;
                        break 2;
                    case 9:

                        $token->opcode = Opcode::PHQL_T_MOD;
                        return 0;

                    case 10:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '&':
                                $yycursor += 1;
                                $yystate  = 72;
                                break 2;
                            default:
                                $yystate = 11;
                                break 2;
                        }
                    case 11:

                        $token->opcode = Opcode::PHQL_T_BITWISE_AND;
                        return 0;

                    case 12:
                        $yyaccept = 0;
                        $yymarker = $yycursor;
                        $yych     = $yyinput[$yycursor];
                        if ($yych <= 0x00) {
                            $yystate = 3;
                            break 2;
                        }
                        $yystate = 74;
                        break 2;
                    case 13:

                        $token->opcode = Opcode::PHQL_T_PARENTHESES_OPEN;
                        return 0;

                    case 14:

                        $token->opcode = Opcode::PHQL_T_PARENTHESES_CLOSE;
                        return 0;

                    case 15:

                        $token->opcode = Opcode::PHQL_T_MUL;
                        return 0;

                    case 16:

                        $token->opcode = Opcode::PHQL_T_ADD;
                        return 0;

                    case 17:

                        $token->opcode = Opcode::PHQL_T_COMMA;
                        return 0;

                    case 18:

                        $token->opcode = Opcode::PHQL_T_SUB;
                        return 0;

                    case 19:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                                $yycursor += 1;
                                $yystate  = 76;
                                break 2;
                            default:
                                $yystate = 20;
                                break 2;
                        }
                    case 20:

                        $token->opcode = Opcode::PHQL_T_DOT;
                        return 0;

                    case 21:

                        $token->opcode = Opcode::PHQL_T_DIV;
                        return 0;

                    case 22:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '.':
                                $yycursor += 1;
                                $yystate  = 76;
                                break 2;
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                                $yycursor += 1;
                                $yystate  = 22;
                                break 2;
                            case 'A':
                            case 'B':
                            case 'C':
                            case 'D':
                            case 'E':
                            case 'F':
                            case 'a':
                            case 'b':
                            case 'c':
                            case 'd':
                            case 'e':
                            case 'f':
                            case 'x':
                                $yycursor += 1;
                                $yystate  = 78;
                                break 2;
                            default:
                                $yystate = 23;
                                break 2;
                        }
                    case 23:

                        $token->opcode = Opcode::PHQL_T_INTEGER;
                        $token->value  = estrndup(q, YYCURSOR - q);
                        $token->len    = YYCURSOR - q;
                        $q             = YYCURSOR;
                        return 0;

                    case 24:
                        $yyaccept = 1;
                        $yymarker = $yycursor;
                        $yych     = $yyinput[$yycursor];
                        switch ($yych) {
                            case '-':
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                            case 'A':
                            case 'B':
                            case 'C':
                            case 'D':
                            case 'E':
                            case 'F':
                            case 'G':
                            case 'H':
                            case 'I':
                            case 'J':
                            case 'K':
                            case 'L':
                            case 'M':
                            case 'N':
                            case 'O':
                            case 'P':
                            case 'Q':
                            case 'R':
                            case 'S':
                            case 'T':
                            case 'U':
                            case 'V':
                            case 'W':
                            case 'X':
                            case 'Y':
                            case 'Z':
                            case '_':
                            case 'a':
                            case 'b':
                            case 'c':
                            case 'd':
                            case 'e':
                            case 'f':
                            case 'g':
                            case 'h':
                            case 'i':
                            case 'j':
                            case 'k':
                            case 'l':
                            case 'm':
                            case 'n':
                            case 'o':
                            case 'p':
                            case 'q':
                            case 'r':
                            case 's':
                            case 't':
                            case 'u':
                            case 'v':
                            case 'w':
                            case 'x':
                            case 'y':
                            case 'z':
                                $yycursor += 1;
                                $yystate  = 79;
                                break 2;
                            default:
                                $yystate = 25;
                                break 2;
                        }
                    case 25:

                        $token->opcode = Opcode::PHQL_T_COLON;
                        return 0;

                    case 26:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '=':
                                $yycursor += 1;
                                $yystate  = 80;
                                break 2;
                            case '>':
                                $yycursor += 1;
                                $yystate  = 81;
                                break 2;
                            default:
                                $yystate = 27;
                                break 2;
                        }
                    case 27:

                        $token->opcode = Opcode::PHQL_T_LESS;
                        return 0;

                    case 28:

                        $token->opcode = Opcode::PHQL_T_EQUALS;
                        return 0;

                    case 29:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '=':
                                $yycursor += 1;
                                $yystate  = 82;
                                break 2;
                            default:
                                $yystate = 30;
                                break 2;
                        }
                    case 30:

                        $token->opcode = Opcode::PHQL_T_GREATER;
                        return 0;

                    case 31:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                                $yycursor += 1;
                                $yystate  = 83;
                                break 2;
                            default:
                                $yystate = 3;
                                break 2;
                        }
                    case 32:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '>':
                                $yycursor += 1;
                                $yystate  = 85;
                                break 2;
                            case '@':
                                $yycursor += 1;
                                $yystate  = 86;
                                break 2;
                            default:
                                $yystate = 3;
                                break 2;
                        }
                    case 33:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'G':
                            case 'g':
                                $yycursor += 1;
                                $yystate  = 87;
                                break 2;
                            case 'L':
                            case 'l':
                                $yycursor += 1;
                                $yystate  = 88;
                                break 2;
                            case 'N':
                            case 'n':
                                $yycursor += 1;
                                $yystate  = 89;
                                break 2;
                            case 'S':
                            case 's':
                                $yycursor += 1;
                                $yystate  = 90;
                                break 2;
                            default:
                                $yystate = 60;
                                break 2;
                        }
                    case 34:

                        $token->value = estrndup(q, YYCURSOR - q);
                        $token->len   = YYCURSOR - q;
                        if ($token->len > 2 && !memcmp($token->value, "0x", 2)) {
                            $token->opcode = Opcode::PHQL_T_HINTEGER;
                        } else {
                            $alpha = 0;
                            for ($i = 0; $i < $token->len; $i++) {
                                $ch = $token->value[$i];
                                if (!(($ch >= '0') && ($ch <= '9'))) {
                                    $alpha = 1;
                                    break;
                                }
                            }

                            if ($alpha) {
                                $token->opcode = Opcode::PHQL_T_IDENTIFIER;
                            } else {
                                $token->opcode = Opcode::PHQL_T_INTEGER;
                            }
                        }

                        $q = YYCURSOR;
                        return 0;

                    case 35:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'E':
                            case 'e':
                                $yycursor += 1;
                                $yystate  = 92;
                                break 2;
                            case 'Y':
                            case 'y':
                                $yycursor += 1;
                                $yystate  = 93;
                                break 2;
                            default:
                                $yystate = 60;
                                break 2;
                        }
                    case 36:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'A':
                            case 'a':
                                $yycursor += 1;
                                $yystate  = 95;
                                break 2;
                            case 'O':
                            case 'o':
                                $yycursor += 1;
                                $yystate  = 96;
                                break 2;
                            case 'R':
                            case 'r':
                                $yycursor += 1;
                                $yystate  = 97;
                                break 2;
                            default:
                                $yystate = 60;
                                break 2;
                        }
                    case 37:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'E':
                            case 'e':
                                $yycursor += 1;
                                $yystate  = 98;
                                break 2;
                            case 'I':
                            case 'i':
                                $yycursor += 1;
                                $yystate  = 99;
                                break 2;
                            default:
                                $yystate = 60;
                                break 2;
                        }
                    case 38:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'L':
                            case 'l':
                                $yycursor += 1;
                                $yystate  = 100;
                                break 2;
                            case 'N':
                            case 'n':
                                $yycursor += 1;
                                $yystate  = 101;
                                break 2;
                            case 'X':
                                $yycursor += 1;
                                $yystate  = 102;
                                break 2;
                            case 'x':
                                $yycursor += 1;
                                $yystate  = 103;
                                break 2;
                            default:
                                $yystate = 60;
                                break 2;
                        }
                    case 39:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'A':
                            case 'a':
                                $yycursor += 1;
                                $yystate  = 104;
                                break 2;
                            case 'O':
                            case 'o':
                                $yycursor += 1;
                                $yystate  = 105;
                                break 2;
                            case 'R':
                            case 'r':
                                $yycursor += 1;
                                $yystate  = 106;
                                break 2;
                            case 'U':
                            case 'u':
                                $yycursor += 1;
                                $yystate  = 107;
                                break 2;
                            default:
                                $yystate = 60;
                                break 2;
                        }
                    case 40:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'R':
                            case 'r':
                                $yycursor += 1;
                                $yystate  = 108;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 41:

                        $token->opcode = Opcode::PHQL_T_IDENTIFIER;
                        if ((YYCURSOR - q) > 1) {
                            if (q[0] == '\\') {
                                $token->value = estrndup(q + 1, YYCURSOR - q - 1);
                                $token->len   = YYCURSOR - q - 1;
                            } else {
                                $token->value = estrndup(q, YYCURSOR - q);
                                $token->len   = YYCURSOR - q;
                            }
                        } else {
                            $token->value = estrndup(q, YYCURSOR - q);
                            $token->len   = YYCURSOR - q;
                        }
                        $q = YYCURSOR;
                        return 0;

                    case 42:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'A':
                            case 'a':
                                $yycursor += 1;
                                $yystate  = 109;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 43:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'L':
                            case 'l':
                                $yycursor += 1;
                                $yystate  = 110;
                                break 2;
                            case 'N':
                            case 'n':
                                $yycursor += 1;
                                $yystate  = 111;
                                break 2;
                            case 'S':
                            case 's':
                                $yycursor += 1;
                                $yystate  = 113;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 44:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'O':
                            case 'o':
                                $yycursor += 1;
                                $yystate  = 115;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 45:
                        $yych    = $yyinput[$yycursor];
                        $yystate = 46;
                        break 2;
                    case 46:
                        switch ($yych) {
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                            case ':':
                            case 'A':
                            case 'B':
                            case 'C':
                            case 'D':
                            case 'E':
                            case 'F':
                            case 'G':
                            case 'H':
                            case 'I':
                            case 'J':
                            case 'K':
                            case 'L':
                            case 'M':
                            case 'N':
                            case 'O':
                            case 'P':
                            case 'Q':
                            case 'R':
                            case 'S':
                            case 'T':
                            case 'U':
                            case 'V':
                            case 'W':
                            case 'X':
                            case 'Y':
                            case 'Z':
                            case '\\':
                            case '_':
                            case 'a':
                            case 'b':
                            case 'c':
                            case 'd':
                            case 'e':
                            case 'f':
                            case 'g':
                            case 'h':
                            case 'i':
                            case 'j':
                            case 'k':
                            case 'l':
                            case 'm':
                            case 'n':
                            case 'o':
                            case 'p':
                            case 'q':
                            case 'r':
                            case 's':
                            case 't':
                            case 'u':
                            case 'v':
                            case 'w':
                            case 'x':
                            case 'y':
                            case 'z':
                                $yycursor += 1;
                                $yystate  = 45;
                                break 2;
                            default:
                                $yystate = 41;
                                break 2;
                        }
                    case 47:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'E':
                            case 'e':
                                $yycursor += 1;
                                $yystate  = 116;
                                break 2;
                            case 'I':
                            case 'i':
                                $yycursor += 1;
                                $yystate  = 117;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 48:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'O':
                            case 'o':
                                $yycursor += 1;
                                $yystate  = 118;
                                break 2;
                            case 'U':
                            case 'u':
                                $yycursor += 1;
                                $yystate  = 119;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 49:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'F':
                            case 'f':
                                $yycursor += 1;
                                $yystate  = 120;
                                break 2;
                            case 'N':
                            case 'n':
                                $yycursor += 1;
                                $yystate  = 121;
                                break 2;
                            case 'R':
                            case 'r':
                                $yycursor += 1;
                                $yystate  = 123;
                                break 2;
                            case 'U':
                            case 'u':
                                $yycursor += 1;
                                $yystate  = 125;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 50:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'I':
                            case 'i':
                                $yycursor += 1;
                                $yystate  = 126;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 51:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'E':
                            case 'e':
                                $yycursor += 1;
                                $yystate  = 127;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 52:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'H':
                            case 'h':
                                $yycursor += 1;
                                $yystate  = 128;
                                break 2;
                            case 'R':
                            case 'r':
                                $yycursor += 1;
                                $yystate  = 129;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 53:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'P':
                            case 'p':
                                $yycursor += 1;
                                $yystate  = 130;
                                break 2;
                            case 'S':
                            case 's':
                                $yycursor += 1;
                                $yystate  = 131;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 54:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'A':
                            case 'a':
                                $yycursor += 1;
                                $yystate  = 132;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 55:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'H':
                            case 'h':
                                $yycursor += 1;
                                $yystate  = 133;
                                break 2;
                            case 'I':
                            case 'i':
                                $yycursor += 1;
                                $yystate  = 134;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 56:
                        $yyaccept = 0;
                        $yymarker = $yycursor;
                        $yych     = $yyinput[$yycursor];
                        switch ($yych) {
                            case 0x00:
                            case 0x01:
                            case 0x02:
                            case 0x03:
                            case 0x04:
                            case 0x05:
                            case 0x06:
                            case 0x07:
                            case 0x08:
                            case '\t':
                            case '\n':
                            case '\v':
                            case '\f':
                            case '\r':
                            case 0x0E:
                            case 0x0F:
                            case 0x10:
                            case 0x11:
                            case 0x12:
                            case 0x13:
                            case 0x14:
                            case 0x15:
                            case 0x16:
                            case 0x17:
                            case 0x18:
                            case 0x19:
                            case 0x1A:
                            case 0x1B:
                            case 0x1C:
                            case 0x1D:
                            case 0x1E:
                            case 0x1F:
                            case '[':
                            case 0x7F:
                                $yystate = 3;
                                break 2;
                            default:
                                $yystate = 136;
                                break 2;
                        }
                    case 57:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'A':
                            case 'B':
                            case 'C':
                            case 'D':
                            case 'E':
                            case 'F':
                            case 'G':
                            case 'H':
                            case 'I':
                            case 'J':
                            case 'K':
                            case 'L':
                            case 'M':
                            case 'N':
                            case 'O':
                            case 'P':
                            case 'Q':
                            case 'R':
                            case 'S':
                            case 'T':
                            case 'U':
                            case 'V':
                            case 'W':
                            case 'X':
                            case 'Y':
                            case 'Z':
                            case '_':
                            case 'a':
                            case 'b':
                            case 'c':
                            case 'd':
                            case 'e':
                            case 'f':
                            case 'g':
                            case 'h':
                            case 'i':
                            case 'j':
                            case 'k':
                            case 'l':
                            case 'm':
                            case 'n':
                            case 'o':
                            case 'p':
                            case 'q':
                            case 'r':
                            case 's':
                            case 't':
                            case 'u':
                            case 'v':
                            case 'w':
                            case 'x':
                            case 'y':
                            case 'z':
                                $yycursor += 1;
                                $yystate  = 45;
                                break 2;
                            default:
                                $yystate = 3;
                                break 2;
                        }
                    case 58:

                        $token->opcode = Opcode::PHQL_T_BITWISE_XOR;
                        return 0;

                    case 59:
                        $yych    = $yyinput[$yycursor];
                        $yystate = 60;
                        break 2;
                    case 60:
                        switch ($yych) {
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                            case 'A':
                            case 'B':
                            case 'C':
                            case 'D':
                            case 'E':
                            case 'F':
                            case 'a':
                            case 'b':
                            case 'c':
                            case 'd':
                            case 'e':
                            case 'f':
                            case 'x':
                                $yycursor += 1;
                                $yystate  = 59;
                                break 2;
                            case ':':
                            case 'G':
                            case 'H':
                            case 'I':
                            case 'J':
                            case 'K':
                            case 'L':
                            case 'M':
                            case 'N':
                            case 'O':
                            case 'P':
                            case 'Q':
                            case 'R':
                            case 'S':
                            case 'T':
                            case 'U':
                            case 'V':
                            case 'W':
                            case 'X':
                            case 'Y':
                            case 'Z':
                            case '\\':
                            case '_':
                            case 'g':
                            case 'h':
                            case 'i':
                            case 'j':
                            case 'k':
                            case 'l':
                            case 'm':
                            case 'n':
                            case 'o':
                            case 'p':
                            case 'q':
                            case 'r':
                            case 's':
                            case 't':
                            case 'u':
                            case 'v':
                            case 'w':
                            case 'y':
                            case 'z':
                                $yycursor += 1;
                                $yystate  = 45;
                                break 2;
                            default:
                                $yystate = 34;
                                break 2;
                        }
                    case 61:
                        $yyaccept = 0;
                        $yymarker = $yycursor;
                        $yych     = $yyinput[$yycursor];
                        switch ($yych) {
                            case '-':
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                            case ':':
                            case 'A':
                            case 'B':
                            case 'C':
                            case 'D':
                            case 'E':
                            case 'F':
                            case 'G':
                            case 'H':
                            case 'I':
                            case 'J':
                            case 'K':
                            case 'L':
                            case 'M':
                            case 'N':
                            case 'O':
                            case 'P':
                            case 'Q':
                            case 'R':
                            case 'S':
                            case 'T':
                            case 'U':
                            case 'V':
                            case 'W':
                            case 'X':
                            case 'Y':
                            case 'Z':
                            case '_':
                            case 'a':
                            case 'b':
                            case 'c':
                            case 'd':
                            case 'e':
                            case 'f':
                            case 'g':
                            case 'h':
                            case 'i':
                            case 'j':
                            case 'k':
                            case 'l':
                            case 'm':
                            case 'n':
                            case 'o':
                            case 'p':
                            case 'q':
                            case 'r':
                            case 's':
                            case 't':
                            case 'u':
                            case 'v':
                            case 'w':
                            case 'x':
                            case 'y':
                            case 'z':
                                $yycursor += 1;
                                $yystate  = 140;
                                break 2;
                            default:
                                $yystate = 3;
                                break 2;
                        }
                    case 62:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '|':
                                $yycursor += 1;
                                $yystate  = 141;
                                break 2;
                            default:
                                $yystate = 63;
                                break 2;
                        }
                    case 63:

                        $token->opcode = Opcode::PHQL_T_BITWISE_OR;
                        return 0;

                    case 64:

                        $token->opcode = Opcode::PHQL_T_BITWISE_NOT;
                        return 0;

                    case 65:

                        $token->opcode = Opcode::PHQL_T_TS_NEGATE;
                        return 0;

                    case 66:

                        $token->opcode = Opcode::PHQL_T_NOTEQUALS;
                        return 0;

                    case 67:
                        $yych    = $yyinput[$yycursor];
                        $yystate = 68;
                        break 2;
                    case 68:
                        switch ($yych) {
                            case 0x00:
                                $yystate = 69;
                                break 2;
                            case '"':
                                $yycursor += 1;
                                $yystate  = 70;
                                break 2;
                            case '\\':
                                $yycursor += 1;
                                $yystate  = 71;
                                break 2;
                            default:
                                $yycursor += 1;
                                $yystate  = 67;
                                break 2;
                        }
                    case 69:
                        $yycursor = $yymarker;
                        switch ($yyaccept) {
                            case 0:
                                $yystate = 3;
                                break 2;
                            case 1:
                                $yystate = 25;
                                break 2;
                            case 2:
                                $yystate = 177;
                                break 2;
                            default:
                                $yystate = 139;
                                break 2;
                        }
                    case 70:

                        $token->opcode = Opcode::PHQL_T_STRING;
                        $token->value  = estrndup($q, YYCURSOR - $q - 1);
                        $token->len    = YYCURSOR - $q - 1;
                        q              = YYCURSOR;
                        return 0;

                    case 71:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '\n':
                                $yystate = 69;
                                break 2;
                            default:
                                $yycursor += 1;
                                $yystate  = 67;
                                break 2;
                        }
                    case 72:

                        $token->opcode = Opcode::PHQL_T_TS_AND;
                        return 0;

                    case 73:
                        $yych    = $yyinput[$yycursor];
                        $yystate = 74;
                        break 2;
                    case 74:
                        switch ($yych) {
                            case 0x00:
                                $yystate = 69;
                                break 2;
                            case '\'':
                                $yycursor += 1;
                                $yystate  = 70;
                                break 2;
                            case '\\':
                                $yycursor += 1;
                                $yystate  = 75;
                                break 2;
                            default:
                                $yycursor += 1;
                                $yystate  = 73;
                                break 2;
                        }
                    case 75:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '\n':
                                $yystate = 69;
                                break 2;
                            default:
                                $yycursor += 1;
                                $yystate  = 73;
                                break 2;
                        }
                    case 76:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                                $yycursor += 1;
                                $yystate  = 76;
                                break 2;
                            default:
                                $yystate = 77;
                                break 2;
                        }
                    case 77:

                        $token->opcode = Opcode::PHQL_T_DOUBLE;
                        $token->value  = estrndup($q, YYCURSOR - $q);
                        $token->len    = YYCURSOR - $q;
                        $q             = YYCURSOR;
                        return 0;

                    case 78:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                            case 'A':
                            case 'B':
                            case 'C':
                            case 'D':
                            case 'E':
                            case 'F':
                            case 'a':
                            case 'b':
                            case 'c':
                            case 'd':
                            case 'e':
                            case 'f':
                            case 'x':
                                $yycursor += 1;
                                $yystate  = 78;
                                break 2;
                            default:
                                $yystate = 34;
                                break 2;
                        }
                    case 79:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '-':
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                            case 'A':
                            case 'B':
                            case 'C':
                            case 'D':
                            case 'E':
                            case 'F':
                            case 'G':
                            case 'H':
                            case 'I':
                            case 'J':
                            case 'K':
                            case 'L':
                            case 'M':
                            case 'N':
                            case 'O':
                            case 'P':
                            case 'Q':
                            case 'R':
                            case 'S':
                            case 'T':
                            case 'U':
                            case 'V':
                            case 'W':
                            case 'X':
                            case 'Y':
                            case 'Z':
                            case '_':
                            case 'a':
                            case 'b':
                            case 'c':
                            case 'd':
                            case 'e':
                            case 'f':
                            case 'g':
                            case 'h':
                            case 'i':
                            case 'j':
                            case 'k':
                            case 'l':
                            case 'm':
                            case 'n':
                            case 'o':
                            case 'p':
                            case 'q':
                            case 'r':
                            case 's':
                            case 't':
                            case 'u':
                            case 'v':
                            case 'w':
                            case 'x':
                            case 'y':
                            case 'z':
                                $yycursor += 1;
                                $yystate  = 79;
                                break 2;
                            case ':':
                                $yycursor += 1;
                                $yystate  = 142;
                                break 2;
                            default:
                                $yystate = 69;
                                break 2;
                        }
                    case 80:
                        $token->opcode = Opcode::PHQL_T_LESSEQUAL;
                        return 0;
                    case 81:
                        $token->opcode = Opcode::PHQL_T_NOTEQUALS;
                        return 0;
                    case 82:
                        $token->opcode = Opcode::PHQL_T_GREATEREQUAL;
                        return 0;
                    case 83:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                                $yycursor += 1;
                                $yystate  = 83;
                                break 2;
                            default:
                                $yystate = 84;
                                break 2;
                        }
                    case 84:
                        $token->opcode = Opcode::PHQL_T_NPLACEHOLDER;
                        $token->value  = estrndup(q, YYCURSOR - q);
                        $token->len    = YYCURSOR - q;
                        $q             = YYCURSOR;
                        return 0;
                    case 85:
                        $token->opcode = Opcode::PHQL_T_TS_CONTAINS_ANOTHER;
                        return 0;
                    case 86:
                        $token->opcode = Opcode::PHQL_T_TS_MATCHES;
                        return 0;
                    case 87:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'A':
                            case 'a':
                                $yycursor += 1;
                                $yystate  = 143;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 88:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'L':
                            case 'l':
                                $yycursor += 1;
                                $yystate  = 144;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 89:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'D':
                            case 'd':
                                $yycursor += 1;
                                $yystate  = 146;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 90:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                            case ':':
                            case 'A':
                            case 'B':
                            case 'D':
                            case 'E':
                            case 'F':
                            case 'G':
                            case 'H':
                            case 'I':
                            case 'J':
                            case 'K':
                            case 'L':
                            case 'M':
                            case 'N':
                            case 'O':
                            case 'P':
                            case 'Q':
                            case 'R':
                            case 'S':
                            case 'T':
                            case 'U':
                            case 'V':
                            case 'W':
                            case 'X':
                            case 'Y':
                            case 'Z':
                            case '\\':
                            case '_':
                            case 'a':
                            case 'b':
                            case 'd':
                            case 'e':
                            case 'f':
                            case 'g':
                            case 'h':
                            case 'i':
                            case 'j':
                            case 'k':
                            case 'l':
                            case 'm':
                            case 'n':
                            case 'o':
                            case 'p':
                            case 'q':
                            case 'r':
                            case 's':
                            case 't':
                            case 'u':
                            case 'v':
                            case 'w':
                            case 'x':
                            case 'y':
                            case 'z':
                                $yycursor += 1;
                                $yystate  = 45;
                                break 2;
                            case 'C':
                            case 'c':
                                $yycursor += 1;
                                $yystate  = 148;
                                break 2;
                            default:
                                $yystate = 91;
                                break 2;
                        }
                    case 91:

                        $token->opcode = Opcode::PHQL_T_AS;
                        return 0;
                    case 92:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'T':
                            case 't':
                                $yycursor += 1;
                                $yystate  = 150;
                                break 2;
                            default:
                                $yystate = 60;
                                break 2;
                        }
                    case 93:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                            case ':':
                            case 'A':
                            case 'B':
                            case 'C':
                            case 'D':
                            case 'E':
                            case 'F':
                            case 'G':
                            case 'H':
                            case 'I':
                            case 'J':
                            case 'K':
                            case 'L':
                            case 'M':
                            case 'N':
                            case 'O':
                            case 'P':
                            case 'Q':
                            case 'R':
                            case 'S':
                            case 'T':
                            case 'U':
                            case 'V':
                            case 'W':
                            case 'X':
                            case 'Y':
                            case 'Z':
                            case '\\':
                            case '_':
                            case 'a':
                            case 'b':
                            case 'c':
                            case 'd':
                            case 'e':
                            case 'f':
                            case 'g':
                            case 'h':
                            case 'i':
                            case 'j':
                            case 'k':
                            case 'l':
                            case 'm':
                            case 'n':
                            case 'o':
                            case 'p':
                            case 'q':
                            case 'r':
                            case 's':
                            case 't':
                            case 'u':
                            case 'v':
                            case 'w':
                            case 'x':
                            case 'y':
                            case 'z':
                                $yycursor += 1;
                                $yystate  = 45;
                                break 2;
                            default:
                                $yystate = 94;
                                break 2;
                        }
                    case 94:

                        $token->opcode = Opcode::PHQL_T_BY;
                        return 0;
                    case 95:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'S':
                            case 's':
                                $yycursor += 1;
                                $yystate  = 151;
                                break 2;
                            default:
                                $yystate = 60;
                                break 2;
                        }
                    case 96:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'N':
                            case 'n':
                                $yycursor += 1;
                                $yystate  = 152;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 97:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'O':
                            case 'o':
                                $yycursor += 1;
                                $yystate  = 153;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 98:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'L':
                            case 'l':
                                $yycursor += 1;
                                $yystate  = 154;
                                break 2;
                            case 'S':
                            case 's':
                                $yycursor += 1;
                                $yystate  = 155;
                                break 2;
                            default:
                                $yystate = 60;
                                break 2;
                        }
                    case 99:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'S':
                            case 's':
                                $yycursor += 1;
                                $yystate  = 156;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 100:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'S':
                            case 's':
                                $yycursor += 1;
                                $yystate  = 157;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 101:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'D':
                            case 'd':
                                $yycursor += 1;
                                $yystate  = 158;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 102:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'I':
                            case 'i':
                                $yycursor += 1;
                                $yystate  = 160;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 103:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'I':
                            case 'i':
                                $yycursor += 1;
                                $yystate  = 160;
                                break 2;
                            default:
                                $yystate = 60;
                                break 2;
                        }
                    case 104:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'L':
                            case 'l':
                                $yycursor += 1;
                                $yystate  = 161;
                                break 2;
                            default:
                                $yystate = 60;
                                break 2;
                        }
                    case 105:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'R':
                            case 'r':
                                $yycursor += 1;
                                $yystate  = 162;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 106:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'O':
                            case 'o':
                                $yycursor += 1;
                                $yystate  = 164;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 107:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'L':
                            case 'l':
                                $yycursor += 1;
                                $yystate  = 165;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 108:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'O':
                            case 'o':
                                $yycursor += 1;
                                $yystate  = 166;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 109:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'V':
                            case 'v':
                                $yycursor += 1;
                                $yystate  = 167;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 110:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'I':
                            case 'i':
                                $yycursor += 1;
                                $yystate  = 168;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 111:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                            case ':':
                            case 'A':
                            case 'B':
                            case 'C':
                            case 'D':
                            case 'E':
                            case 'F':
                            case 'G':
                            case 'H':
                            case 'I':
                            case 'J':
                            case 'K':
                            case 'L':
                            case 'M':
                            case 'O':
                            case 'P':
                            case 'Q':
                            case 'R':
                            case 'U':
                            case 'V':
                            case 'W':
                            case 'X':
                            case 'Y':
                            case 'Z':
                            case '\\':
                            case '_':
                            case 'a':
                            case 'b':
                            case 'c':
                            case 'd':
                            case 'e':
                            case 'f':
                            case 'g':
                            case 'h':
                            case 'i':
                            case 'j':
                            case 'k':
                            case 'l':
                            case 'm':
                            case 'o':
                            case 'p':
                            case 'q':
                            case 'r':
                            case 'u':
                            case 'v':
                            case 'w':
                            case 'x':
                            case 'y':
                            case 'z':
                                $yycursor += 1;
                                $yystate  = 45;
                                break 2;
                            case 'N':
                            case 'n':
                                $yycursor += 1;
                                $yystate  = 169;
                                break 2;
                            case 'S':
                            case 's':
                                $yycursor += 1;
                                $yystate  = 170;
                                break 2;
                            case 'T':
                            case 't':
                                $yycursor += 1;
                                $yystate  = 171;
                                break 2;
                            default:
                                $yystate = 112;
                                break 2;
                        }
                    case 112:

                        $token->opcode = Opcode::PHQL_T_IN;
                        return 0;

                    case 113:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                            case ':':
                            case 'A':
                            case 'B':
                            case 'C':
                            case 'D':
                            case 'E':
                            case 'F':
                            case 'G':
                            case 'H':
                            case 'I':
                            case 'J':
                            case 'K':
                            case 'L':
                            case 'M':
                            case 'N':
                            case 'O':
                            case 'P':
                            case 'Q':
                            case 'R':
                            case 'S':
                            case 'T':
                            case 'U':
                            case 'V':
                            case 'W':
                            case 'X':
                            case 'Y':
                            case 'Z':
                            case '\\':
                            case '_':
                            case 'a':
                            case 'b':
                            case 'c':
                            case 'd':
                            case 'e':
                            case 'f':
                            case 'g':
                            case 'h':
                            case 'i':
                            case 'j':
                            case 'k':
                            case 'l':
                            case 'm':
                            case 'n':
                            case 'o':
                            case 'p':
                            case 'q':
                            case 'r':
                            case 's':
                            case 't':
                            case 'u':
                            case 'v':
                            case 'w':
                            case 'x':
                            case 'y':
                            case 'z':
                                $yycursor += 1;
                                $yystate  = 45;
                                break 2;
                            default:
                                $yystate = 114;
                                break 2;
                        }
                    case 114:

                        $token->opcode = Opcode::PHQL_T_IS;
                        return 0;

                    case 115:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'I':
                            case 'i':
                                $yycursor += 1;
                                $yystate  = 172;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 116:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'F':
                            case 'f':
                                $yycursor += 1;
                                $yystate  = 173;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 117:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'K':
                            case 'k':
                                $yycursor += 1;
                                $yystate  = 174;
                                break 2;
                            case 'M':
                            case 'm':
                                $yycursor += 1;
                                $yystate  = 175;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 118:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'T':
                            case 't':
                                $yycursor += 1;
                                $yystate  = 176;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 119:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'L':
                            case 'l':
                                $yycursor += 1;
                                $yystate  = 178;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 120:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'F':
                            case 'f':
                                $yycursor += 1;
                                $yystate  = 179;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 121:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                            case ':':
                            case 'A':
                            case 'B':
                            case 'C':
                            case 'D':
                            case 'E':
                            case 'F':
                            case 'G':
                            case 'H':
                            case 'I':
                            case 'J':
                            case 'K':
                            case 'L':
                            case 'M':
                            case 'N':
                            case 'O':
                            case 'P':
                            case 'Q':
                            case 'R':
                            case 'S':
                            case 'T':
                            case 'U':
                            case 'V':
                            case 'W':
                            case 'X':
                            case 'Y':
                            case 'Z':
                            case '\\':
                            case '_':
                            case 'a':
                            case 'b':
                            case 'c':
                            case 'd':
                            case 'e':
                            case 'f':
                            case 'g':
                            case 'h':
                            case 'i':
                            case 'j':
                            case 'k':
                            case 'l':
                            case 'm':
                            case 'n':
                            case 'o':
                            case 'p':
                            case 'q':
                            case 'r':
                            case 's':
                            case 't':
                            case 'u':
                            case 'v':
                            case 'w':
                            case 'x':
                            case 'y':
                            case 'z':
                                $yycursor += 1;
                                $yystate  = 45;
                                break 2;
                            default:
                                $yystate = 122;
                                break 2;
                        }
                    case 122:

                        $token->opcode = Opcode::PHQL_T_ON;
                        return 0;

                    case 123:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                            case ':':
                            case 'A':
                            case 'B':
                            case 'C':
                            case 'E':
                            case 'F':
                            case 'G':
                            case 'H':
                            case 'I':
                            case 'J':
                            case 'K':
                            case 'L':
                            case 'M':
                            case 'N':
                            case 'O':
                            case 'P':
                            case 'Q':
                            case 'R':
                            case 'S':
                            case 'T':
                            case 'U':
                            case 'V':
                            case 'W':
                            case 'X':
                            case 'Y':
                            case 'Z':
                            case '\\':
                            case '_':
                            case 'a':
                            case 'b':
                            case 'c':
                            case 'e':
                            case 'f':
                            case 'g':
                            case 'h':
                            case 'i':
                            case 'j':
                            case 'k':
                            case 'l':
                            case 'm':
                            case 'n':
                            case 'o':
                            case 'p':
                            case 'q':
                            case 'r':
                            case 's':
                            case 't':
                            case 'u':
                            case 'v':
                            case 'w':
                            case 'x':
                            case 'y':
                            case 'z':
                                $yycursor += 1;
                                $yystate  = 45;
                                break 2;
                            case 'D':
                            case 'd':
                                $yycursor += 1;
                                $yystate  = 180;
                                break 2;
                            default:
                                $yystate = 124;
                                break 2;
                        }
                    case 124:

                        $token->opcode = Opcode::PHQL_T_OR;
                        return 0;

                    case 125:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'T':
                            case 't':
                                $yycursor += 1;
                                $yystate  = 181;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 126:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'G':
                            case 'g':
                                $yycursor += 1;
                                $yystate  = 182;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 127:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'L':
                            case 'l':
                                $yycursor += 1;
                                $yystate  = 183;
                                break 2;
                            case 'T':
                            case 't':
                                $yycursor += 1;
                                $yystate  = 184;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 128:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'E':
                            case 'e':
                                $yycursor += 1;
                                $yystate  = 186;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 129:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'U':
                            case 'u':
                                $yycursor += 1;
                                $yystate  = 187;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 130:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'D':
                            case 'd':
                                $yycursor += 1;
                                $yystate  = 188;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 131:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'I':
                            case 'i':
                                $yycursor += 1;
                                $yystate  = 189;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 132:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'L':
                            case 'l':
                                $yycursor += 1;
                                $yystate  = 190;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 133:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'E':
                            case 'e':
                                $yycursor += 1;
                                $yystate  = 191;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 134:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'T':
                            case 't':
                                $yycursor += 1;
                                $yystate  = 192;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 135:
                        $yych    = $yyinput[$yycursor];
                        $yystate = 136;
                        break 2;
                    case 136:
                        switch ($yych) {
                            case 0x00:
                            case 0x01:
                            case 0x02:
                            case 0x03:
                            case 0x04:
                            case 0x05:
                            case 0x06:
                            case 0x07:
                            case 0x08:
                            case '\t':
                            case '\n':
                            case '\v':
                            case '\f':
                            case '\r':
                            case 0x0E:
                            case 0x0F:
                            case 0x10:
                            case 0x11:
                            case 0x12:
                            case 0x13:
                            case 0x14:
                            case 0x15:
                            case 0x16:
                            case 0x17:
                            case 0x18:
                            case 0x19:
                            case 0x1A:
                            case 0x1B:
                            case 0x1C:
                            case 0x1D:
                            case 0x1E:
                            case 0x1F:
                            case '[':
                            case 0x7F:
                                $yystate = 69;
                                break 2;
                            case '\\':
                                $yycursor += 1;
                                $yystate  = 137;
                                break 2;
                            case ']':
                                $yycursor += 1;
                                $yystate  = 138;
                                break 2;
                            default:
                                $yycursor += 1;
                                $yystate  = 135;
                                break 2;
                        }
                    case 137:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 0x00:
                            case 0x01:
                            case 0x02:
                            case 0x03:
                            case 0x04:
                            case 0x05:
                            case 0x06:
                            case 0x07:
                            case 0x08:
                            case '\t':
                            case '\n':
                            case '\v':
                            case '\f':
                            case '\r':
                            case 0x0E:
                            case 0x0F:
                            case 0x10:
                            case 0x11:
                            case 0x12:
                            case 0x13:
                            case 0x14:
                            case 0x15:
                            case 0x16:
                            case 0x17:
                            case 0x18:
                            case 0x19:
                            case 0x1A:
                            case 0x1B:
                            case 0x1C:
                            case 0x1D:
                            case 0x1E:
                            case 0x1F:
                            case 0x7F:
                                $yystate = 69;
                                break 2;
                            case '\\':
                                $yycursor += 1;
                                $yystate  = 137;
                                break 2;
                            case ']':
                                $yycursor += 1;
                                $yystate  = 193;
                                break 2;
                            default:
                                $yycursor += 1;
                                $yystate  = 135;
                                break 2;
                        }
                    case 138:
                        $yystate = 139;
                        break 2;
                    case 139:

                        $token->opcode = Opcode::PHQL_T_IDENTIFIER;
                        $token->value  = estrndup(q, YYCURSOR - q - 1);
                        $token->len    = YYCURSOR - q - 1;
                        $q             = YYCURSOR;
                        return 0;

                    case 140:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '-':
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                            case ':':
                            case 'A':
                            case 'B':
                            case 'C':
                            case 'D':
                            case 'E':
                            case 'F':
                            case 'G':
                            case 'H':
                            case 'I':
                            case 'J':
                            case 'K':
                            case 'L':
                            case 'M':
                            case 'N':
                            case 'O':
                            case 'P':
                            case 'Q':
                            case 'R':
                            case 'S':
                            case 'T':
                            case 'U':
                            case 'V':
                            case 'W':
                            case 'X':
                            case 'Y':
                            case 'Z':
                            case '_':
                            case 'a':
                            case 'b':
                            case 'c':
                            case 'd':
                            case 'e':
                            case 'f':
                            case 'g':
                            case 'h':
                            case 'i':
                            case 'j':
                            case 'k':
                            case 'l':
                            case 'm':
                            case 'n':
                            case 'o':
                            case 'p':
                            case 'q':
                            case 'r':
                            case 's':
                            case 't':
                            case 'u':
                            case 'v':
                            case 'w':
                            case 'x':
                            case 'y':
                            case 'z':
                                $yycursor += 1;
                                $yystate  = 140;
                                break 2;
                            case '}':
                                $yycursor += 1;
                                $yystate  = 194;
                                break 2;
                            default:
                                $yystate = 69;
                                break 2;
                        }
                    case 141:

                        $token->opcode = Opcode::PHQL_T_TS_OR;
                        return 0;

                    case 142:

                        $token->opcode = Opcode::PHQL_T_SPLACEHOLDER;
                        $token->value  = estrndup(q, YYCURSOR - q - 1);
                        $token->len    = YYCURSOR - q - 1;
                        q              = YYCURSOR;
                        return 0;

                    case 143:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'I':
                            case 'i':
                                $yycursor += 1;
                                $yystate  = 195;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 144:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                            case ':':
                            case 'A':
                            case 'B':
                            case 'C':
                            case 'D':
                            case 'E':
                            case 'F':
                            case 'G':
                            case 'H':
                            case 'I':
                            case 'J':
                            case 'K':
                            case 'L':
                            case 'M':
                            case 'N':
                            case 'O':
                            case 'P':
                            case 'Q':
                            case 'R':
                            case 'S':
                            case 'T':
                            case 'U':
                            case 'V':
                            case 'W':
                            case 'X':
                            case 'Y':
                            case 'Z':
                            case '\\':
                            case '_':
                            case 'a':
                            case 'b':
                            case 'c':
                            case 'd':
                            case 'e':
                            case 'f':
                            case 'g':
                            case 'h':
                            case 'i':
                            case 'j':
                            case 'k':
                            case 'l':
                            case 'm':
                            case 'n':
                            case 'o':
                            case 'p':
                            case 'q':
                            case 'r':
                            case 's':
                            case 't':
                            case 'u':
                            case 'v':
                            case 'w':
                            case 'x':
                            case 'y':
                            case 'z':
                                $yycursor += 1;
                                $yystate  = 45;
                                break 2;
                            default:
                                $yystate = 145;
                                break 2;
                        }
                    case 145:

                        $token->opcode = Opcode::PHQL_T_ALL;
                        return 0;

                    case 146:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                            case ':':
                            case 'A':
                            case 'B':
                            case 'C':
                            case 'D':
                            case 'E':
                            case 'F':
                            case 'G':
                            case 'H':
                            case 'I':
                            case 'J':
                            case 'K':
                            case 'L':
                            case 'M':
                            case 'N':
                            case 'O':
                            case 'P':
                            case 'Q':
                            case 'R':
                            case 'S':
                            case 'T':
                            case 'U':
                            case 'V':
                            case 'W':
                            case 'X':
                            case 'Y':
                            case 'Z':
                            case '\\':
                            case '_':
                            case 'a':
                            case 'b':
                            case 'c':
                            case 'd':
                            case 'e':
                            case 'f':
                            case 'g':
                            case 'h':
                            case 'i':
                            case 'j':
                            case 'k':
                            case 'l':
                            case 'm':
                            case 'n':
                            case 'o':
                            case 'p':
                            case 'q':
                            case 'r':
                            case 's':
                            case 't':
                            case 'u':
                            case 'v':
                            case 'w':
                            case 'x':
                            case 'y':
                            case 'z':
                                $yycursor += 1;
                                $yystate  = 45;
                                break 2;
                            default:
                                $yystate = 147;
                                break 2;
                        }
                    case 147:

                        $token->opcode = Opcode::PHQL_T_AND;
                        return 0;

                    case 148:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                            case ':':
                            case 'A':
                            case 'B':
                            case 'C':
                            case 'D':
                            case 'E':
                            case 'F':
                            case 'G':
                            case 'H':
                            case 'I':
                            case 'J':
                            case 'K':
                            case 'L':
                            case 'M':
                            case 'N':
                            case 'O':
                            case 'P':
                            case 'Q':
                            case 'R':
                            case 'S':
                            case 'T':
                            case 'U':
                            case 'V':
                            case 'W':
                            case 'X':
                            case 'Y':
                            case 'Z':
                            case '\\':
                            case '_':
                            case 'a':
                            case 'b':
                            case 'c':
                            case 'd':
                            case 'e':
                            case 'f':
                            case 'g':
                            case 'h':
                            case 'i':
                            case 'j':
                            case 'k':
                            case 'l':
                            case 'm':
                            case 'n':
                            case 'o':
                            case 'p':
                            case 'q':
                            case 'r':
                            case 's':
                            case 't':
                            case 'u':
                            case 'v':
                            case 'w':
                            case 'x':
                            case 'y':
                            case 'z':
                                $yycursor += 1;
                                $yystate  = 45;
                                break 2;
                            default:
                                $yystate = 149;
                                break 2;
                        }
                    case 149:

                        $token->opcode = Opcode::PHQL_T_ASC;
                        return 0;

                    case 150:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'W':
                            case 'w':
                                $yycursor += 1;
                                $yystate  = 196;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 151:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'E':
                            case 'e':
                                $yycursor += 1;
                                $yystate  = 197;
                                break 2;
                            case 'T':
                            case 't':
                                $yycursor += 1;
                                $yystate  = 199;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 152:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'V':
                            case 'v':
                                $yycursor += 1;
                                $yystate  = 201;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 153:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'S':
                            case 's':
                                $yycursor += 1;
                                $yystate  = 202;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 154:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'E':
                            case 'e':
                                $yycursor += 1;
                                $yystate  = 203;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 155:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'C':
                            case 'c':
                                $yycursor += 1;
                                $yystate  = 204;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 156:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'T':
                            case 't':
                                $yycursor += 1;
                                $yystate  = 206;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 157:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'E':
                            case 'e':
                                $yycursor += 1;
                                $yystate  = 207;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 158:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                            case ':':
                            case 'A':
                            case 'B':
                            case 'C':
                            case 'D':
                            case 'E':
                            case 'F':
                            case 'G':
                            case 'H':
                            case 'I':
                            case 'J':
                            case 'K':
                            case 'L':
                            case 'M':
                            case 'N':
                            case 'O':
                            case 'P':
                            case 'Q':
                            case 'R':
                            case 'S':
                            case 'T':
                            case 'U':
                            case 'V':
                            case 'W':
                            case 'X':
                            case 'Y':
                            case 'Z':
                            case '\\':
                            case '_':
                            case 'a':
                            case 'b':
                            case 'c':
                            case 'd':
                            case 'e':
                            case 'f':
                            case 'g':
                            case 'h':
                            case 'i':
                            case 'j':
                            case 'k':
                            case 'l':
                            case 'm':
                            case 'n':
                            case 'o':
                            case 'p':
                            case 'q':
                            case 'r':
                            case 's':
                            case 't':
                            case 'u':
                            case 'v':
                            case 'w':
                            case 'x':
                            case 'y':
                            case 'z':
                                $yycursor += 1;
                                $yystate  = 45;
                                break 2;
                            default:
                                $yystate = 159;
                                break 2;
                        }
                    case 159:

                        $token->opcode = Opcode::PHQL_T_END;
                        return 0;

                    case 160:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'S':
                            case 's':
                                $yycursor += 1;
                                $yystate  = 209;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 161:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'S':
                            case 's':
                                $yycursor += 1;
                                $yystate  = 210;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 162:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                            case ':':
                            case 'A':
                            case 'B':
                            case 'C':
                            case 'D':
                            case 'E':
                            case 'F':
                            case 'G':
                            case 'H':
                            case 'I':
                            case 'J':
                            case 'K':
                            case 'L':
                            case 'M':
                            case 'N':
                            case 'O':
                            case 'P':
                            case 'Q':
                            case 'R':
                            case 'S':
                            case 'T':
                            case 'U':
                            case 'V':
                            case 'W':
                            case 'X':
                            case 'Y':
                            case 'Z':
                            case '\\':
                            case '_':
                            case 'a':
                            case 'b':
                            case 'c':
                            case 'd':
                            case 'e':
                            case 'f':
                            case 'g':
                            case 'h':
                            case 'i':
                            case 'j':
                            case 'k':
                            case 'l':
                            case 'm':
                            case 'n':
                            case 'o':
                            case 'p':
                            case 'q':
                            case 'r':
                            case 's':
                            case 't':
                            case 'u':
                            case 'v':
                            case 'w':
                            case 'x':
                            case 'y':
                            case 'z':
                                $yycursor += 1;
                                $yystate  = 45;
                                break 2;
                            default:
                                $yystate = 163;
                                break 2;
                        }
                    case 163:

                        $token->opcode = Opcode::PHQL_T_FOR;
                        return 0;

                    case 164:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'M':
                            case 'm':
                                $yycursor += 1;
                                $yystate  = 211;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 165:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'L':
                            case 'l':
                                $yycursor += 1;
                                $yystate  = 213;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 166:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'U':
                            case 'u':
                                $yycursor += 1;
                                $yystate  = 215;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 167:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'I':
                            case 'i':
                                $yycursor += 1;
                                $yystate  = 216;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 168:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'K':
                            case 'k':
                                $yycursor += 1;
                                $yystate  = 217;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 169:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'E':
                            case 'e':
                                $yycursor += 1;
                                $yystate  = 218;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 170:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'E':
                            case 'e':
                                $yycursor += 1;
                                $yystate  = 219;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 171:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'O':
                            case 'o':
                                $yycursor += 1;
                                $yystate  = 220;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 172:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'N':
                            case 'n':
                                $yycursor += 1;
                                $yystate  = 222;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 173:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'T':
                            case 't':
                                $yycursor += 1;
                                $yystate  = 224;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 174:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'E':
                            case 'e':
                                $yycursor += 1;
                                $yystate  = 226;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 175:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'I':
                            case 'i':
                                $yycursor += 1;
                                $yystate  = 228;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 176:
                        $yyaccept = 2;
                        $yymarker = $yycursor;
                        $yych     = $yyinput[$yycursor];
                        switch ($yych) {
                            case ' ':
                                $yycursor += 1;
                                $yystate  = 229;
                                break 2;
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                            case ':':
                            case 'A':
                            case 'B':
                            case 'C':
                            case 'D':
                            case 'E':
                            case 'F':
                            case 'G':
                            case 'H':
                            case 'I':
                            case 'J':
                            case 'K':
                            case 'L':
                            case 'M':
                            case 'N':
                            case 'O':
                            case 'P':
                            case 'Q':
                            case 'R':
                            case 'S':
                            case 'T':
                            case 'U':
                            case 'V':
                            case 'W':
                            case 'X':
                            case 'Y':
                            case 'Z':
                            case '\\':
                            case '_':
                            case 'a':
                            case 'b':
                            case 'c':
                            case 'd':
                            case 'e':
                            case 'f':
                            case 'g':
                            case 'h':
                            case 'i':
                            case 'j':
                            case 'k':
                            case 'l':
                            case 'm':
                            case 'n':
                            case 'o':
                            case 'p':
                            case 'q':
                            case 'r':
                            case 's':
                            case 't':
                            case 'u':
                            case 'v':
                            case 'w':
                            case 'x':
                            case 'y':
                            case 'z':
                                $yycursor += 1;
                                $yystate  = 45;
                                break 2;
                            default:
                                $yystate = 177;
                                break 2;
                        }
                    case 177:

                        $token->opcode = Opcode::PHQL_T_NOT;
                        return 0;

                    case 178:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'L':
                            case 'l':
                                $yycursor += 1;
                                $yystate  = 230;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 179:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'S':
                            case 's':
                                $yycursor += 1;
                                $yystate  = 232;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 180:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'E':
                            case 'e':
                                $yycursor += 1;
                                $yystate  = 233;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 181:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'E':
                            case 'e':
                                $yycursor += 1;
                                $yystate  = 234;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 182:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'H':
                            case 'h':
                                $yycursor += 1;
                                $yystate  = 235;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 183:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'E':
                            case 'e':
                                $yycursor += 1;
                                $yystate  = 236;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 184:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                            case ':':
                            case 'A':
                            case 'B':
                            case 'C':
                            case 'D':
                            case 'E':
                            case 'F':
                            case 'G':
                            case 'H':
                            case 'I':
                            case 'J':
                            case 'K':
                            case 'L':
                            case 'M':
                            case 'N':
                            case 'O':
                            case 'P':
                            case 'Q':
                            case 'R':
                            case 'S':
                            case 'T':
                            case 'U':
                            case 'V':
                            case 'W':
                            case 'X':
                            case 'Y':
                            case 'Z':
                            case '\\':
                            case '_':
                            case 'a':
                            case 'b':
                            case 'c':
                            case 'd':
                            case 'e':
                            case 'f':
                            case 'g':
                            case 'h':
                            case 'i':
                            case 'j':
                            case 'k':
                            case 'l':
                            case 'm':
                            case 'n':
                            case 'o':
                            case 'p':
                            case 'q':
                            case 'r':
                            case 's':
                            case 't':
                            case 'u':
                            case 'v':
                            case 'w':
                            case 'x':
                            case 'y':
                            case 'z':
                                $yycursor += 1;
                                $yystate  = 45;
                                break 2;
                            default:
                                $yystate = 185;
                                break 2;
                        }
                    case 185:

                        $token->opcode = Opcode::PHQL_T_SET;
                        return 0;

                    case 186:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'N':
                            case 'n':
                                $yycursor += 1;
                                $yystate  = 237;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 187:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'E':
                            case 'e':
                                $yycursor += 1;
                                $yystate  = 239;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 188:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'A':
                            case 'a':
                                $yycursor += 1;
                                $yystate  = 241;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 189:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'N':
                            case 'n':
                                $yycursor += 1;
                                $yystate  = 242;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 190:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'U':
                            case 'u':
                                $yycursor += 1;
                                $yystate  = 243;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 191:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'N':
                            case 'n':
                                $yycursor += 1;
                                $yystate  = 244;
                                break 2;
                            case 'R':
                            case 'r':
                                $yycursor += 1;
                                $yystate  = 246;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 192:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'H':
                            case 'h':
                                $yycursor += 1;
                                $yystate  = 247;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 193:
                        $yyaccept = 3;
                        $yymarker = $yycursor;
                        $yych     = $yyinput[$yycursor];
                        switch ($yych) {
                            case 0x00:
                            case 0x01:
                            case 0x02:
                            case 0x03:
                            case 0x04:
                            case 0x05:
                            case 0x06:
                            case 0x07:
                            case 0x08:
                            case '\t':
                            case '\n':
                            case '\v':
                            case '\f':
                            case '\r':
                            case 0x0E:
                            case 0x0F:
                            case 0x10:
                            case 0x11:
                            case 0x12:
                            case 0x13:
                            case 0x14:
                            case 0x15:
                            case 0x16:
                            case 0x17:
                            case 0x18:
                            case 0x19:
                            case 0x1A:
                            case 0x1B:
                            case 0x1C:
                            case 0x1D:
                            case 0x1E:
                            case 0x1F:
                            case '[':
                            case 0x7F:
                                $yystate = 139;
                                break 2;
                            case '\\':
                                $yycursor += 1;
                                $yystate  = 137;
                                break 2;
                            case ']':
                                $yycursor += 1;
                                $yystate  = 138;
                                break 2;
                            default:
                                $yycursor += 1;
                                $yystate  = 135;
                                break 2;
                        }
                    case 194:

                        $token->opcode = Opcode::PHQL_T_BPLACEHOLDER;
                        $token->value  = estrndup(q, YYCURSOR - q - 1);
                        $token->len    = YYCURSOR - q - 1;
                        $q             = YYCURSOR;
                        return 0;

                    case 195:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'N':
                            case 'n':
                                $yycursor += 1;
                                $yystate  = 249;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 196:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'E':
                            case 'e':
                                $yycursor += 1;
                                $yystate  = 250;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 197:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                            case ':':
                            case 'A':
                            case 'B':
                            case 'C':
                            case 'D':
                            case 'E':
                            case 'F':
                            case 'G':
                            case 'H':
                            case 'I':
                            case 'J':
                            case 'K':
                            case 'L':
                            case 'M':
                            case 'N':
                            case 'O':
                            case 'P':
                            case 'Q':
                            case 'R':
                            case 'S':
                            case 'T':
                            case 'U':
                            case 'V':
                            case 'W':
                            case 'X':
                            case 'Y':
                            case 'Z':
                            case '\\':
                            case '_':
                            case 'a':
                            case 'b':
                            case 'c':
                            case 'd':
                            case 'e':
                            case 'f':
                            case 'g':
                            case 'h':
                            case 'i':
                            case 'j':
                            case 'k':
                            case 'l':
                            case 'm':
                            case 'n':
                            case 'o':
                            case 'p':
                            case 'q':
                            case 'r':
                            case 's':
                            case 't':
                            case 'u':
                            case 'v':
                            case 'w':
                            case 'x':
                            case 'y':
                            case 'z':
                                $yycursor += 1;
                                $yystate  = 45;
                                break 2;
                            default:
                                $yystate = 198;
                                break 2;
                        }
                    case 198:

                        $token->opcode = Opcode::PHQL_T_CASE;
                        return 0;

                    case 199:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                            case ':':
                            case 'A':
                            case 'B':
                            case 'C':
                            case 'D':
                            case 'E':
                            case 'F':
                            case 'G':
                            case 'H':
                            case 'I':
                            case 'J':
                            case 'K':
                            case 'L':
                            case 'M':
                            case 'N':
                            case 'O':
                            case 'P':
                            case 'Q':
                            case 'R':
                            case 'S':
                            case 'T':
                            case 'U':
                            case 'V':
                            case 'W':
                            case 'X':
                            case 'Y':
                            case 'Z':
                            case '\\':
                            case '_':
                            case 'a':
                            case 'b':
                            case 'c':
                            case 'd':
                            case 'e':
                            case 'f':
                            case 'g':
                            case 'h':
                            case 'i':
                            case 'j':
                            case 'k':
                            case 'l':
                            case 'm':
                            case 'n':
                            case 'o':
                            case 'p':
                            case 'q':
                            case 'r':
                            case 's':
                            case 't':
                            case 'u':
                            case 'v':
                            case 'w':
                            case 'x':
                            case 'y':
                            case 'z':
                                $yycursor += 1;
                                $yystate  = 45;
                                break 2;
                            default:
                                $yystate = 200;
                                break 2;
                        }
                    case 200:

                        $token->opcode = Opcode::PHQL_T_CAST;
                        return 0;

                    case 201:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'E':
                            case 'e':
                                $yycursor += 1;
                                $yystate  = 251;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 202:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'S':
                            case 's':
                                $yycursor += 1;
                                $yystate  = 252;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 203:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'T':
                            case 't':
                                $yycursor += 1;
                                $yystate  = 254;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 204:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                            case ':':
                            case 'A':
                            case 'B':
                            case 'C':
                            case 'D':
                            case 'E':
                            case 'F':
                            case 'G':
                            case 'H':
                            case 'I':
                            case 'J':
                            case 'K':
                            case 'L':
                            case 'M':
                            case 'N':
                            case 'O':
                            case 'P':
                            case 'Q':
                            case 'R':
                            case 'S':
                            case 'T':
                            case 'U':
                            case 'V':
                            case 'W':
                            case 'X':
                            case 'Y':
                            case 'Z':
                            case '\\':
                            case '_':
                            case 'a':
                            case 'b':
                            case 'c':
                            case 'd':
                            case 'e':
                            case 'f':
                            case 'g':
                            case 'h':
                            case 'i':
                            case 'j':
                            case 'k':
                            case 'l':
                            case 'm':
                            case 'n':
                            case 'o':
                            case 'p':
                            case 'q':
                            case 'r':
                            case 's':
                            case 't':
                            case 'u':
                            case 'v':
                            case 'w':
                            case 'x':
                            case 'y':
                            case 'z':
                                $yycursor += 1;
                                $yystate  = 45;
                                break 2;
                            default:
                                $yystate = 205;
                                break 2;
                        }
                    case 205:

                        $token->opcode = Opcode::PHQL_T_DESC;
                        return 0;

                    case 206:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'I':
                            case 'i':
                                $yycursor += 1;
                                $yystate  = 255;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 207:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                            case ':':
                            case 'A':
                            case 'B':
                            case 'C':
                            case 'D':
                            case 'E':
                            case 'F':
                            case 'G':
                            case 'H':
                            case 'I':
                            case 'J':
                            case 'K':
                            case 'L':
                            case 'M':
                            case 'N':
                            case 'O':
                            case 'P':
                            case 'Q':
                            case 'R':
                            case 'S':
                            case 'T':
                            case 'U':
                            case 'V':
                            case 'W':
                            case 'X':
                            case 'Y':
                            case 'Z':
                            case '\\':
                            case '_':
                            case 'a':
                            case 'b':
                            case 'c':
                            case 'd':
                            case 'e':
                            case 'f':
                            case 'g':
                            case 'h':
                            case 'i':
                            case 'j':
                            case 'k':
                            case 'l':
                            case 'm':
                            case 'n':
                            case 'o':
                            case 'p':
                            case 'q':
                            case 'r':
                            case 's':
                            case 't':
                            case 'u':
                            case 'v':
                            case 'w':
                            case 'x':
                            case 'y':
                            case 'z':
                                $yycursor += 1;
                                $yystate  = 45;
                                break 2;
                            default:
                                $yystate = 208;
                                break 2;
                        }
                    case 208:

                        $token->opcode = Opcode::PHQL_T_ELSE;
                        return 0;

                    case 209:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'T':
                            case 't':
                                $yycursor += 1;
                                $yystate  = 256;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 210:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'E':
                            case 'e':
                                $yycursor += 1;
                                $yystate  = 257;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 211:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                            case ':':
                            case 'A':
                            case 'B':
                            case 'C':
                            case 'D':
                            case 'E':
                            case 'F':
                            case 'G':
                            case 'H':
                            case 'I':
                            case 'J':
                            case 'K':
                            case 'L':
                            case 'M':
                            case 'N':
                            case 'O':
                            case 'P':
                            case 'Q':
                            case 'R':
                            case 'S':
                            case 'T':
                            case 'U':
                            case 'V':
                            case 'W':
                            case 'X':
                            case 'Y':
                            case 'Z':
                            case '\\':
                            case '_':
                            case 'a':
                            case 'b':
                            case 'c':
                            case 'd':
                            case 'e':
                            case 'f':
                            case 'g':
                            case 'h':
                            case 'i':
                            case 'j':
                            case 'k':
                            case 'l':
                            case 'm':
                            case 'n':
                            case 'o':
                            case 'p':
                            case 'q':
                            case 'r':
                            case 's':
                            case 't':
                            case 'u':
                            case 'v':
                            case 'w':
                            case 'x':
                            case 'y':
                            case 'z':
                                $yycursor += 1;
                                $yystate  = 45;
                                break 2;
                            default:
                                $yystate = 212;
                                break 2;
                        }
                    case 212:

                        $token->opcode = Opcode::PHQL_T_FROM;
                        return 0;

                    case 213:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                            case ':':
                            case 'A':
                            case 'B':
                            case 'C':
                            case 'D':
                            case 'E':
                            case 'F':
                            case 'G':
                            case 'H':
                            case 'I':
                            case 'J':
                            case 'K':
                            case 'L':
                            case 'M':
                            case 'N':
                            case 'O':
                            case 'P':
                            case 'Q':
                            case 'R':
                            case 'S':
                            case 'T':
                            case 'U':
                            case 'V':
                            case 'W':
                            case 'X':
                            case 'Y':
                            case 'Z':
                            case '\\':
                            case '_':
                            case 'a':
                            case 'b':
                            case 'c':
                            case 'd':
                            case 'e':
                            case 'f':
                            case 'g':
                            case 'h':
                            case 'i':
                            case 'j':
                            case 'k':
                            case 'l':
                            case 'm':
                            case 'n':
                            case 'o':
                            case 'p':
                            case 'q':
                            case 'r':
                            case 's':
                            case 't':
                            case 'u':
                            case 'v':
                            case 'w':
                            case 'x':
                            case 'y':
                            case 'z':
                                $yycursor += 1;
                                $yystate  = 45;
                                break 2;
                            default:
                                $yystate = 214;
                                break 2;
                        }
                    case 214:

                        $token->opcode = Opcode::PHQL_T_FULL;
                        return 0;

                    case 215:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'P':
                            case 'p':
                                $yycursor += 1;
                                $yystate  = 259;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 216:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'N':
                            case 'n':
                                $yycursor += 1;
                                $yystate  = 261;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 217:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'E':
                            case 'e':
                                $yycursor += 1;
                                $yystate  = 262;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 218:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'R':
                            case 'r':
                                $yycursor += 1;
                                $yystate  = 264;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 219:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'R':
                            case 'r':
                                $yycursor += 1;
                                $yystate  = 266;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 220:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                            case ':':
                            case 'A':
                            case 'B':
                            case 'C':
                            case 'D':
                            case 'E':
                            case 'F':
                            case 'G':
                            case 'H':
                            case 'I':
                            case 'J':
                            case 'K':
                            case 'L':
                            case 'M':
                            case 'N':
                            case 'O':
                            case 'P':
                            case 'Q':
                            case 'R':
                            case 'S':
                            case 'T':
                            case 'U':
                            case 'V':
                            case 'W':
                            case 'X':
                            case 'Y':
                            case 'Z':
                            case '\\':
                            case '_':
                            case 'a':
                            case 'b':
                            case 'c':
                            case 'd':
                            case 'e':
                            case 'f':
                            case 'g':
                            case 'h':
                            case 'i':
                            case 'j':
                            case 'k':
                            case 'l':
                            case 'm':
                            case 'n':
                            case 'o':
                            case 'p':
                            case 'q':
                            case 'r':
                            case 's':
                            case 't':
                            case 'u':
                            case 'v':
                            case 'w':
                            case 'x':
                            case 'y':
                            case 'z':
                                $yycursor += 1;
                                $yystate  = 45;
                                break 2;
                            default:
                                $yystate = 221;
                                break 2;
                        }
                    case 221:

                        $token->opcode = Opcode::PHQL_T_INTO;
                        return 0;

                    case 222:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                            case ':':
                            case 'A':
                            case 'B':
                            case 'C':
                            case 'D':
                            case 'E':
                            case 'F':
                            case 'G':
                            case 'H':
                            case 'I':
                            case 'J':
                            case 'K':
                            case 'L':
                            case 'M':
                            case 'N':
                            case 'O':
                            case 'P':
                            case 'Q':
                            case 'R':
                            case 'S':
                            case 'T':
                            case 'U':
                            case 'V':
                            case 'W':
                            case 'X':
                            case 'Y':
                            case 'Z':
                            case '\\':
                            case '_':
                            case 'a':
                            case 'b':
                            case 'c':
                            case 'd':
                            case 'e':
                            case 'f':
                            case 'g':
                            case 'h':
                            case 'i':
                            case 'j':
                            case 'k':
                            case 'l':
                            case 'm':
                            case 'n':
                            case 'o':
                            case 'p':
                            case 'q':
                            case 'r':
                            case 's':
                            case 't':
                            case 'u':
                            case 'v':
                            case 'w':
                            case 'x':
                            case 'y':
                            case 'z':
                                $yycursor += 1;
                                $yystate  = 45;
                                break 2;
                            default:
                                $yystate = 223;
                                break 2;
                        }
                    case 223:

                        $token->opcode = Opcode::PHQL_T_JOIN;
                        return 0;

                    case 224:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                            case ':':
                            case 'A':
                            case 'B':
                            case 'C':
                            case 'D':
                            case 'E':
                            case 'F':
                            case 'G':
                            case 'H':
                            case 'I':
                            case 'J':
                            case 'K':
                            case 'L':
                            case 'M':
                            case 'N':
                            case 'O':
                            case 'P':
                            case 'Q':
                            case 'R':
                            case 'S':
                            case 'T':
                            case 'U':
                            case 'V':
                            case 'W':
                            case 'X':
                            case 'Y':
                            case 'Z':
                            case '\\':
                            case '_':
                            case 'a':
                            case 'b':
                            case 'c':
                            case 'd':
                            case 'e':
                            case 'f':
                            case 'g':
                            case 'h':
                            case 'i':
                            case 'j':
                            case 'k':
                            case 'l':
                            case 'm':
                            case 'n':
                            case 'o':
                            case 'p':
                            case 'q':
                            case 'r':
                            case 's':
                            case 't':
                            case 'u':
                            case 'v':
                            case 'w':
                            case 'x':
                            case 'y':
                            case 'z':
                                $yycursor += 1;
                                $yystate  = 45;
                                break 2;
                            default:
                                $yystate = 225;
                                break 2;
                        }
                    case 225:

                        $token->opcode = Opcode::PHQL_T_LEFT;
                        return 0;

                    case 226:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                            case ':':
                            case 'A':
                            case 'B':
                            case 'C':
                            case 'D':
                            case 'E':
                            case 'F':
                            case 'G':
                            case 'H':
                            case 'I':
                            case 'J':
                            case 'K':
                            case 'L':
                            case 'M':
                            case 'N':
                            case 'O':
                            case 'P':
                            case 'Q':
                            case 'R':
                            case 'S':
                            case 'T':
                            case 'U':
                            case 'V':
                            case 'W':
                            case 'X':
                            case 'Y':
                            case 'Z':
                            case '\\':
                            case '_':
                            case 'a':
                            case 'b':
                            case 'c':
                            case 'd':
                            case 'e':
                            case 'f':
                            case 'g':
                            case 'h':
                            case 'i':
                            case 'j':
                            case 'k':
                            case 'l':
                            case 'm':
                            case 'n':
                            case 'o':
                            case 'p':
                            case 'q':
                            case 'r':
                            case 's':
                            case 't':
                            case 'u':
                            case 'v':
                            case 'w':
                            case 'x':
                            case 'y':
                            case 'z':
                                $yycursor += 1;
                                $yystate  = 45;
                                break 2;
                            default:
                                $yystate = 227;
                                break 2;
                        }
                    case 227:

                        $token->opcode = Opcode::PHQL_T_LIKE;
                        return 0;

                    case 228:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'T':
                            case 't':
                                $yycursor += 1;
                                $yystate  = 267;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 229:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'B':
                            case 'b':
                                $yycursor += 1;
                                $yystate  = 269;
                                break 2;
                            default:
                                $yystate = 69;
                                break 2;
                        }
                    case 230:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                            case ':':
                            case 'A':
                            case 'B':
                            case 'C':
                            case 'D':
                            case 'E':
                            case 'F':
                            case 'G':
                            case 'H':
                            case 'I':
                            case 'J':
                            case 'K':
                            case 'L':
                            case 'M':
                            case 'N':
                            case 'O':
                            case 'P':
                            case 'Q':
                            case 'R':
                            case 'S':
                            case 'T':
                            case 'U':
                            case 'V':
                            case 'W':
                            case 'X':
                            case 'Y':
                            case 'Z':
                            case '\\':
                            case '_':
                            case 'a':
                            case 'b':
                            case 'c':
                            case 'd':
                            case 'e':
                            case 'f':
                            case 'g':
                            case 'h':
                            case 'i':
                            case 'j':
                            case 'k':
                            case 'l':
                            case 'm':
                            case 'n':
                            case 'o':
                            case 'p':
                            case 'q':
                            case 'r':
                            case 's':
                            case 't':
                            case 'u':
                            case 'v':
                            case 'w':
                            case 'x':
                            case 'y':
                            case 'z':
                                $yycursor += 1;
                                $yystate  = 45;
                                break 2;
                            default:
                                $yystate = 231;
                                break 2;
                        }
                    case 231:

                        $token->opcode = Opcode::PHQL_T_NULL;
                        return 0;

                    case 232:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'E':
                            case 'e':
                                $yycursor += 1;
                                $yystate  = 270;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 233:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'R':
                            case 'r':
                                $yycursor += 1;
                                $yystate  = 271;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 234:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'R':
                            case 'r':
                                $yycursor += 1;
                                $yystate  = 273;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 235:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'T':
                            case 't':
                                $yycursor += 1;
                                $yystate  = 275;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 236:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'C':
                            case 'c':
                                $yycursor += 1;
                                $yystate  = 277;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 237:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                            case ':':
                            case 'A':
                            case 'B':
                            case 'C':
                            case 'D':
                            case 'E':
                            case 'F':
                            case 'G':
                            case 'H':
                            case 'I':
                            case 'J':
                            case 'K':
                            case 'L':
                            case 'M':
                            case 'N':
                            case 'O':
                            case 'P':
                            case 'Q':
                            case 'R':
                            case 'S':
                            case 'T':
                            case 'U':
                            case 'V':
                            case 'W':
                            case 'X':
                            case 'Y':
                            case 'Z':
                            case '\\':
                            case '_':
                            case 'a':
                            case 'b':
                            case 'c':
                            case 'd':
                            case 'e':
                            case 'f':
                            case 'g':
                            case 'h':
                            case 'i':
                            case 'j':
                            case 'k':
                            case 'l':
                            case 'm':
                            case 'n':
                            case 'o':
                            case 'p':
                            case 'q':
                            case 'r':
                            case 's':
                            case 't':
                            case 'u':
                            case 'v':
                            case 'w':
                            case 'x':
                            case 'y':
                            case 'z':
                                $yycursor += 1;
                                $yystate  = 45;
                                break 2;
                            default:
                                $yystate = 238;
                                break 2;
                        }
                    case 238:

                        $token->opcode = Opcode::PHQL_T_THEN;
                        return 0;

                    case 239:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                            case ':':
                            case 'A':
                            case 'B':
                            case 'C':
                            case 'D':
                            case 'E':
                            case 'F':
                            case 'G':
                            case 'H':
                            case 'I':
                            case 'J':
                            case 'K':
                            case 'L':
                            case 'M':
                            case 'N':
                            case 'O':
                            case 'P':
                            case 'Q':
                            case 'R':
                            case 'S':
                            case 'T':
                            case 'U':
                            case 'V':
                            case 'W':
                            case 'X':
                            case 'Y':
                            case 'Z':
                            case '\\':
                            case '_':
                            case 'a':
                            case 'b':
                            case 'c':
                            case 'd':
                            case 'e':
                            case 'f':
                            case 'g':
                            case 'h':
                            case 'i':
                            case 'j':
                            case 'k':
                            case 'l':
                            case 'm':
                            case 'n':
                            case 'o':
                            case 'p':
                            case 'q':
                            case 'r':
                            case 's':
                            case 't':
                            case 'u':
                            case 'v':
                            case 'w':
                            case 'x':
                            case 'y':
                            case 'z':
                                $yycursor += 1;
                                $yystate  = 45;
                                break 2;
                            default:
                                $yystate = 240;
                                break 2;
                        }
                    case 240:

                        $token->opcode = Opcode::PHQL_T_TRUE;
                        return 0;

                    case 241:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'T':
                            case 't':
                                $yycursor += 1;
                                $yystate  = 278;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 242:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'G':
                            case 'g':
                                $yycursor += 1;
                                $yystate  = 279;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 243:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'E':
                            case 'e':
                                $yycursor += 1;
                                $yystate  = 281;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 244:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                            case ':':
                            case 'A':
                            case 'B':
                            case 'C':
                            case 'D':
                            case 'E':
                            case 'F':
                            case 'G':
                            case 'H':
                            case 'I':
                            case 'J':
                            case 'K':
                            case 'L':
                            case 'M':
                            case 'N':
                            case 'O':
                            case 'P':
                            case 'Q':
                            case 'R':
                            case 'S':
                            case 'T':
                            case 'U':
                            case 'V':
                            case 'W':
                            case 'X':
                            case 'Y':
                            case 'Z':
                            case '\\':
                            case '_':
                            case 'a':
                            case 'b':
                            case 'c':
                            case 'd':
                            case 'e':
                            case 'f':
                            case 'g':
                            case 'h':
                            case 'i':
                            case 'j':
                            case 'k':
                            case 'l':
                            case 'm':
                            case 'n':
                            case 'o':
                            case 'p':
                            case 'q':
                            case 'r':
                            case 's':
                            case 't':
                            case 'u':
                            case 'v':
                            case 'w':
                            case 'x':
                            case 'y':
                            case 'z':
                                $yycursor += 1;
                                $yystate  = 45;
                                break 2;
                            default:
                                $yystate = 245;
                                break 2;
                        }
                    case 245:

                        $token->opcode = Opcode::PHQL_T_WHEN;
                        return 0;

                    case 246:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'E':
                            case 'e':
                                $yycursor += 1;
                                $yystate  = 282;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 247:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                            case ':':
                            case 'A':
                            case 'B':
                            case 'C':
                            case 'D':
                            case 'E':
                            case 'F':
                            case 'G':
                            case 'H':
                            case 'I':
                            case 'J':
                            case 'K':
                            case 'L':
                            case 'M':
                            case 'N':
                            case 'O':
                            case 'P':
                            case 'Q':
                            case 'R':
                            case 'S':
                            case 'T':
                            case 'U':
                            case 'V':
                            case 'W':
                            case 'X':
                            case 'Y':
                            case 'Z':
                            case '\\':
                            case '_':
                            case 'a':
                            case 'b':
                            case 'c':
                            case 'd':
                            case 'e':
                            case 'f':
                            case 'g':
                            case 'h':
                            case 'i':
                            case 'j':
                            case 'k':
                            case 'l':
                            case 'm':
                            case 'n':
                            case 'o':
                            case 'p':
                            case 'q':
                            case 'r':
                            case 's':
                            case 't':
                            case 'u':
                            case 'v':
                            case 'w':
                            case 'x':
                            case 'y':
                            case 'z':
                                $yycursor += 1;
                                $yystate  = 45;
                                break 2;
                            default:
                                $yystate = 248;
                                break 2;
                        }
                    case 248:

                        $token->opcode = Opcode::PHQL_T_WITH;
                        return 0;

                    case 249:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'S':
                            case 's':
                                $yycursor += 1;
                                $yystate  = 284;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 250:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'E':
                            case 'e':
                                $yycursor += 1;
                                $yystate  = 285;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 251:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'R':
                            case 'r':
                                $yycursor += 1;
                                $yystate  = 286;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 252:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                            case ':':
                            case 'A':
                            case 'B':
                            case 'C':
                            case 'D':
                            case 'E':
                            case 'F':
                            case 'G':
                            case 'H':
                            case 'I':
                            case 'J':
                            case 'K':
                            case 'L':
                            case 'M':
                            case 'N':
                            case 'O':
                            case 'P':
                            case 'Q':
                            case 'R':
                            case 'S':
                            case 'T':
                            case 'U':
                            case 'V':
                            case 'W':
                            case 'X':
                            case 'Y':
                            case 'Z':
                            case '\\':
                            case '_':
                            case 'a':
                            case 'b':
                            case 'c':
                            case 'd':
                            case 'e':
                            case 'f':
                            case 'g':
                            case 'h':
                            case 'i':
                            case 'j':
                            case 'k':
                            case 'l':
                            case 'm':
                            case 'n':
                            case 'o':
                            case 'p':
                            case 'q':
                            case 'r':
                            case 's':
                            case 't':
                            case 'u':
                            case 'v':
                            case 'w':
                            case 'x':
                            case 'y':
                            case 'z':
                                $yycursor += 1;
                                $yystate  = 45;
                                break 2;
                            default:
                                $yystate = 253;
                                break 2;
                        }
                    case 253:

                        $token->opcode = Opcode::PHQL_T_CROSS;
                        return 0;

                    case 254:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'E':
                            case 'e':
                                $yycursor += 1;
                                $yystate  = 287;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 255:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'N':
                            case 'n':
                                $yycursor += 1;
                                $yystate  = 289;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 256:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'S':
                            case 's':
                                $yycursor += 1;
                                $yystate  = 290;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 257:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                            case ':':
                            case 'A':
                            case 'B':
                            case 'C':
                            case 'D':
                            case 'E':
                            case 'F':
                            case 'G':
                            case 'H':
                            case 'I':
                            case 'J':
                            case 'K':
                            case 'L':
                            case 'M':
                            case 'N':
                            case 'O':
                            case 'P':
                            case 'Q':
                            case 'R':
                            case 'S':
                            case 'T':
                            case 'U':
                            case 'V':
                            case 'W':
                            case 'X':
                            case 'Y':
                            case 'Z':
                            case '\\':
                            case '_':
                            case 'a':
                            case 'b':
                            case 'c':
                            case 'd':
                            case 'e':
                            case 'f':
                            case 'g':
                            case 'h':
                            case 'i':
                            case 'j':
                            case 'k':
                            case 'l':
                            case 'm':
                            case 'n':
                            case 'o':
                            case 'p':
                            case 'q':
                            case 'r':
                            case 's':
                            case 't':
                            case 'u':
                            case 'v':
                            case 'w':
                            case 'x':
                            case 'y':
                            case 'z':
                                $yycursor += 1;
                                $yystate  = 45;
                                break 2;
                            default:
                                $yystate = 258;
                                break 2;
                        }
                    case 258:

                        $token->opcode = Opcode::PHQL_T_FALSE;
                        return 0;

                    case 259:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                            case ':':
                            case 'A':
                            case 'B':
                            case 'C':
                            case 'D':
                            case 'E':
                            case 'F':
                            case 'G':
                            case 'H':
                            case 'I':
                            case 'J':
                            case 'K':
                            case 'L':
                            case 'M':
                            case 'N':
                            case 'O':
                            case 'P':
                            case 'Q':
                            case 'R':
                            case 'S':
                            case 'T':
                            case 'U':
                            case 'V':
                            case 'W':
                            case 'X':
                            case 'Y':
                            case 'Z':
                            case '\\':
                            case '_':
                            case 'a':
                            case 'b':
                            case 'c':
                            case 'd':
                            case 'e':
                            case 'f':
                            case 'g':
                            case 'h':
                            case 'i':
                            case 'j':
                            case 'k':
                            case 'l':
                            case 'm':
                            case 'n':
                            case 'o':
                            case 'p':
                            case 'q':
                            case 'r':
                            case 's':
                            case 't':
                            case 'u':
                            case 'v':
                            case 'w':
                            case 'x':
                            case 'y':
                            case 'z':
                                $yycursor += 1;
                                $yystate  = 45;
                                break 2;
                            default:
                                $yystate = 260;
                                break 2;
                        }
                    case 260:

                        $token->opcode = Opcode::PHQL_T_GROUP;
                        return 0;

                    case 261:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'G':
                            case 'g':
                                $yycursor += 1;
                                $yystate  = 292;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 262:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                            case ':':
                            case 'A':
                            case 'B':
                            case 'C':
                            case 'D':
                            case 'E':
                            case 'F':
                            case 'G':
                            case 'H':
                            case 'I':
                            case 'J':
                            case 'K':
                            case 'L':
                            case 'M':
                            case 'N':
                            case 'O':
                            case 'P':
                            case 'Q':
                            case 'R':
                            case 'S':
                            case 'T':
                            case 'U':
                            case 'V':
                            case 'W':
                            case 'X':
                            case 'Y':
                            case 'Z':
                            case '\\':
                            case '_':
                            case 'a':
                            case 'b':
                            case 'c':
                            case 'd':
                            case 'e':
                            case 'f':
                            case 'g':
                            case 'h':
                            case 'i':
                            case 'j':
                            case 'k':
                            case 'l':
                            case 'm':
                            case 'n':
                            case 'o':
                            case 'p':
                            case 'q':
                            case 'r':
                            case 's':
                            case 't':
                            case 'u':
                            case 'v':
                            case 'w':
                            case 'x':
                            case 'y':
                            case 'z':
                                $yycursor += 1;
                                $yystate  = 45;
                                break 2;
                            default:
                                $yystate = 263;
                                break 2;
                        }
                    case 263:

                        $token->opcode = Opcode::PHQL_T_ILIKE;
                        return 0;

                    case 264:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                            case ':':
                            case 'A':
                            case 'B':
                            case 'C':
                            case 'D':
                            case 'E':
                            case 'F':
                            case 'G':
                            case 'H':
                            case 'I':
                            case 'J':
                            case 'K':
                            case 'L':
                            case 'M':
                            case 'N':
                            case 'O':
                            case 'P':
                            case 'Q':
                            case 'R':
                            case 'S':
                            case 'T':
                            case 'U':
                            case 'V':
                            case 'W':
                            case 'X':
                            case 'Y':
                            case 'Z':
                            case '\\':
                            case '_':
                            case 'a':
                            case 'b':
                            case 'c':
                            case 'd':
                            case 'e':
                            case 'f':
                            case 'g':
                            case 'h':
                            case 'i':
                            case 'j':
                            case 'k':
                            case 'l':
                            case 'm':
                            case 'n':
                            case 'o':
                            case 'p':
                            case 'q':
                            case 'r':
                            case 's':
                            case 't':
                            case 'u':
                            case 'v':
                            case 'w':
                            case 'x':
                            case 'y':
                            case 'z':
                                $yycursor += 1;
                                $yystate  = 45;
                                break 2;
                            default:
                                $yystate = 265;
                                break 2;
                        }
                    case 265:

                        $token->opcode = Opcode::PHQL_T_INNER;
                        return 0;

                    case 266:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'T':
                            case 't':
                                $yycursor += 1;
                                $yystate  = 294;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 267:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                            case ':':
                            case 'A':
                            case 'B':
                            case 'C':
                            case 'D':
                            case 'E':
                            case 'F':
                            case 'G':
                            case 'H':
                            case 'I':
                            case 'J':
                            case 'K':
                            case 'L':
                            case 'M':
                            case 'N':
                            case 'O':
                            case 'P':
                            case 'Q':
                            case 'R':
                            case 'S':
                            case 'T':
                            case 'U':
                            case 'V':
                            case 'W':
                            case 'X':
                            case 'Y':
                            case 'Z':
                            case '\\':
                            case '_':
                            case 'a':
                            case 'b':
                            case 'c':
                            case 'd':
                            case 'e':
                            case 'f':
                            case 'g':
                            case 'h':
                            case 'i':
                            case 'j':
                            case 'k':
                            case 'l':
                            case 'm':
                            case 'n':
                            case 'o':
                            case 'p':
                            case 'q':
                            case 'r':
                            case 's':
                            case 't':
                            case 'u':
                            case 'v':
                            case 'w':
                            case 'x':
                            case 'y':
                            case 'z':
                                $yycursor += 1;
                                $yystate  = 45;
                                break 2;
                            default:
                                $yystate = 268;
                                break 2;
                        }
                    case 268:

                        $token->opcode = Opcode::PHQL_T_LIMIT;
                        return 0;

                    case 269:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'E':
                            case 'e':
                                $yycursor += 1;
                                $yystate  = 296;
                                break 2;
                            default:
                                $yystate = 69;
                                break 2;
                        }
                    case 270:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'T':
                            case 't':
                                $yycursor += 1;
                                $yystate  = 297;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 271:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                            case ':':
                            case 'A':
                            case 'B':
                            case 'C':
                            case 'D':
                            case 'E':
                            case 'F':
                            case 'G':
                            case 'H':
                            case 'I':
                            case 'J':
                            case 'K':
                            case 'L':
                            case 'M':
                            case 'N':
                            case 'O':
                            case 'P':
                            case 'Q':
                            case 'R':
                            case 'S':
                            case 'T':
                            case 'U':
                            case 'V':
                            case 'W':
                            case 'X':
                            case 'Y':
                            case 'Z':
                            case '\\':
                            case '_':
                            case 'a':
                            case 'b':
                            case 'c':
                            case 'd':
                            case 'e':
                            case 'f':
                            case 'g':
                            case 'h':
                            case 'i':
                            case 'j':
                            case 'k':
                            case 'l':
                            case 'm':
                            case 'n':
                            case 'o':
                            case 'p':
                            case 'q':
                            case 'r':
                            case 's':
                            case 't':
                            case 'u':
                            case 'v':
                            case 'w':
                            case 'x':
                            case 'y':
                            case 'z':
                                $yycursor += 1;
                                $yystate  = 45;
                                break 2;
                            default:
                                $yystate = 272;
                                break 2;
                        }
                    case 272:

                        $token->opcode = Opcode::PHQL_T_ORDER;
                        return 0;

                    case 273:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                            case ':':
                            case 'A':
                            case 'B':
                            case 'C':
                            case 'D':
                            case 'E':
                            case 'F':
                            case 'G':
                            case 'H':
                            case 'I':
                            case 'J':
                            case 'K':
                            case 'L':
                            case 'M':
                            case 'N':
                            case 'O':
                            case 'P':
                            case 'Q':
                            case 'R':
                            case 'S':
                            case 'T':
                            case 'U':
                            case 'V':
                            case 'W':
                            case 'X':
                            case 'Y':
                            case 'Z':
                            case '\\':
                            case '_':
                            case 'a':
                            case 'b':
                            case 'c':
                            case 'd':
                            case 'e':
                            case 'f':
                            case 'g':
                            case 'h':
                            case 'i':
                            case 'j':
                            case 'k':
                            case 'l':
                            case 'm':
                            case 'n':
                            case 'o':
                            case 'p':
                            case 'q':
                            case 'r':
                            case 's':
                            case 't':
                            case 'u':
                            case 'v':
                            case 'w':
                            case 'x':
                            case 'y':
                            case 'z':
                                $yycursor += 1;
                                $yystate  = 45;
                                break 2;
                            default:
                                $yystate = 274;
                                break 2;
                        }
                    case 274:

                        $token->opcode = Opcode::PHQL_T_OUTER;
                        return 0;

                    case 275:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                            case ':':
                            case 'A':
                            case 'B':
                            case 'C':
                            case 'D':
                            case 'E':
                            case 'F':
                            case 'G':
                            case 'H':
                            case 'I':
                            case 'J':
                            case 'K':
                            case 'L':
                            case 'M':
                            case 'N':
                            case 'O':
                            case 'P':
                            case 'Q':
                            case 'R':
                            case 'S':
                            case 'T':
                            case 'U':
                            case 'V':
                            case 'W':
                            case 'X':
                            case 'Y':
                            case 'Z':
                            case '\\':
                            case '_':
                            case 'a':
                            case 'b':
                            case 'c':
                            case 'd':
                            case 'e':
                            case 'f':
                            case 'g':
                            case 'h':
                            case 'i':
                            case 'j':
                            case 'k':
                            case 'l':
                            case 'm':
                            case 'n':
                            case 'o':
                            case 'p':
                            case 'q':
                            case 'r':
                            case 's':
                            case 't':
                            case 'u':
                            case 'v':
                            case 'w':
                            case 'x':
                            case 'y':
                            case 'z':
                                $yycursor += 1;
                                $yystate  = 45;
                                break 2;
                            default:
                                $yystate = 276;
                                break 2;
                        }
                    case 276:

                        $token->opcode = Opcode::PHQL_T_RIGHT;
                        return 0;

                    case 277:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'T':
                            case 't':
                                $yycursor += 1;
                                $yystate  = 299;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 278:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'E':
                            case 'e':
                                $yycursor += 1;
                                $yystate  = 301;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 279:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                            case ':':
                            case 'A':
                            case 'B':
                            case 'C':
                            case 'D':
                            case 'E':
                            case 'F':
                            case 'G':
                            case 'H':
                            case 'I':
                            case 'J':
                            case 'K':
                            case 'L':
                            case 'M':
                            case 'N':
                            case 'O':
                            case 'P':
                            case 'Q':
                            case 'R':
                            case 'S':
                            case 'T':
                            case 'U':
                            case 'V':
                            case 'W':
                            case 'X':
                            case 'Y':
                            case 'Z':
                            case '\\':
                            case '_':
                            case 'a':
                            case 'b':
                            case 'c':
                            case 'd':
                            case 'e':
                            case 'f':
                            case 'g':
                            case 'h':
                            case 'i':
                            case 'j':
                            case 'k':
                            case 'l':
                            case 'm':
                            case 'n':
                            case 'o':
                            case 'p':
                            case 'q':
                            case 'r':
                            case 's':
                            case 't':
                            case 'u':
                            case 'v':
                            case 'w':
                            case 'x':
                            case 'y':
                            case 'z':
                                $yycursor += 1;
                                $yystate  = 45;
                                break 2;
                            default:
                                $yystate = 280;
                                break 2;
                        }
                    case 280:

                        $token->opcode = Opcode::PHQL_T_USING;
                        return 0;

                    case 281:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'S':
                            case 's':
                                $yycursor += 1;
                                $yystate  = 303;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 282:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                            case ':':
                            case 'A':
                            case 'B':
                            case 'C':
                            case 'D':
                            case 'E':
                            case 'F':
                            case 'G':
                            case 'H':
                            case 'I':
                            case 'J':
                            case 'K':
                            case 'L':
                            case 'M':
                            case 'N':
                            case 'O':
                            case 'P':
                            case 'Q':
                            case 'R':
                            case 'S':
                            case 'T':
                            case 'U':
                            case 'V':
                            case 'W':
                            case 'X':
                            case 'Y':
                            case 'Z':
                            case '\\':
                            case '_':
                            case 'a':
                            case 'b':
                            case 'c':
                            case 'd':
                            case 'e':
                            case 'f':
                            case 'g':
                            case 'h':
                            case 'i':
                            case 'j':
                            case 'k':
                            case 'l':
                            case 'm':
                            case 'n':
                            case 'o':
                            case 'p':
                            case 'q':
                            case 'r':
                            case 's':
                            case 't':
                            case 'u':
                            case 'v':
                            case 'w':
                            case 'x':
                            case 'y':
                            case 'z':
                                $yycursor += 1;
                                $yystate  = 45;
                                break 2;
                            default:
                                $yystate = 283;
                                break 2;
                        }
                    case 283:

                        $token->opcode = Opcode::PHQL_T_WHERE;
                        return 0;

                    case 284:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'T':
                            case 't':
                                $yycursor += 1;
                                $yystate  = 305;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 285:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'N':
                            case 'n':
                                $yycursor += 1;
                                $yystate  = 307;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 286:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'T':
                            case 't':
                                $yycursor += 1;
                                $yystate  = 309;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 287:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                            case ':':
                            case 'A':
                            case 'B':
                            case 'C':
                            case 'D':
                            case 'E':
                            case 'F':
                            case 'G':
                            case 'H':
                            case 'I':
                            case 'J':
                            case 'K':
                            case 'L':
                            case 'M':
                            case 'N':
                            case 'O':
                            case 'P':
                            case 'Q':
                            case 'R':
                            case 'S':
                            case 'T':
                            case 'U':
                            case 'V':
                            case 'W':
                            case 'X':
                            case 'Y':
                            case 'Z':
                            case '\\':
                            case '_':
                            case 'a':
                            case 'b':
                            case 'c':
                            case 'd':
                            case 'e':
                            case 'f':
                            case 'g':
                            case 'h':
                            case 'i':
                            case 'j':
                            case 'k':
                            case 'l':
                            case 'm':
                            case 'n':
                            case 'o':
                            case 'p':
                            case 'q':
                            case 'r':
                            case 's':
                            case 't':
                            case 'u':
                            case 'v':
                            case 'w':
                            case 'x':
                            case 'y':
                            case 'z':
                                $yycursor += 1;
                                $yystate  = 45;
                                break 2;
                            default:
                                $yystate = 288;
                                break 2;
                        }
                    case 288:

                        $token->opcode = Opcode::PHQL_T_DELETE;
                        return 0;

                    case 289:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'C':
                            case 'c':
                                $yycursor += 1;
                                $yystate  = 311;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 290:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                            case ':':
                            case 'A':
                            case 'B':
                            case 'C':
                            case 'D':
                            case 'E':
                            case 'F':
                            case 'G':
                            case 'H':
                            case 'I':
                            case 'J':
                            case 'K':
                            case 'L':
                            case 'M':
                            case 'N':
                            case 'O':
                            case 'P':
                            case 'Q':
                            case 'R':
                            case 'S':
                            case 'T':
                            case 'U':
                            case 'V':
                            case 'W':
                            case 'X':
                            case 'Y':
                            case 'Z':
                            case '\\':
                            case '_':
                            case 'a':
                            case 'b':
                            case 'c':
                            case 'd':
                            case 'e':
                            case 'f':
                            case 'g':
                            case 'h':
                            case 'i':
                            case 'j':
                            case 'k':
                            case 'l':
                            case 'm':
                            case 'n':
                            case 'o':
                            case 'p':
                            case 'q':
                            case 'r':
                            case 's':
                            case 't':
                            case 'u':
                            case 'v':
                            case 'w':
                            case 'x':
                            case 'y':
                            case 'z':
                                $yycursor += 1;
                                $yystate  = 45;
                                break 2;
                            default:
                                $yystate = 291;
                                break 2;
                        }
                    case 291:

                        $token->opcode = Opcode::PHQL_T_EXISTS;
                        return 0;

                    case 292:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                            case ':':
                            case 'A':
                            case 'B':
                            case 'C':
                            case 'D':
                            case 'E':
                            case 'F':
                            case 'G':
                            case 'H':
                            case 'I':
                            case 'J':
                            case 'K':
                            case 'L':
                            case 'M':
                            case 'N':
                            case 'O':
                            case 'P':
                            case 'Q':
                            case 'R':
                            case 'S':
                            case 'T':
                            case 'U':
                            case 'V':
                            case 'W':
                            case 'X':
                            case 'Y':
                            case 'Z':
                            case '\\':
                            case '_':
                            case 'a':
                            case 'b':
                            case 'c':
                            case 'd':
                            case 'e':
                            case 'f':
                            case 'g':
                            case 'h':
                            case 'i':
                            case 'j':
                            case 'k':
                            case 'l':
                            case 'm':
                            case 'n':
                            case 'o':
                            case 'p':
                            case 'q':
                            case 'r':
                            case 's':
                            case 't':
                            case 'u':
                            case 'v':
                            case 'w':
                            case 'x':
                            case 'y':
                            case 'z':
                                $yycursor += 1;
                                $yystate  = 45;
                                break 2;
                            default:
                                $yystate = 293;
                                break 2;
                        }
                    case 293:

                        $token->opcode = Opcode::PHQL_T_HAVING;
                        return 0;

                    case 294:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                            case ':':
                            case 'A':
                            case 'B':
                            case 'C':
                            case 'D':
                            case 'E':
                            case 'F':
                            case 'G':
                            case 'H':
                            case 'I':
                            case 'J':
                            case 'K':
                            case 'L':
                            case 'M':
                            case 'N':
                            case 'O':
                            case 'P':
                            case 'Q':
                            case 'R':
                            case 'S':
                            case 'T':
                            case 'U':
                            case 'V':
                            case 'W':
                            case 'X':
                            case 'Y':
                            case 'Z':
                            case '\\':
                            case '_':
                            case 'a':
                            case 'b':
                            case 'c':
                            case 'd':
                            case 'e':
                            case 'f':
                            case 'g':
                            case 'h':
                            case 'i':
                            case 'j':
                            case 'k':
                            case 'l':
                            case 'm':
                            case 'n':
                            case 'o':
                            case 'p':
                            case 'q':
                            case 'r':
                            case 's':
                            case 't':
                            case 'u':
                            case 'v':
                            case 'w':
                            case 'x':
                            case 'y':
                            case 'z':
                                $yycursor += 1;
                                $yystate  = 45;
                                break 2;
                            default:
                                $yystate = 295;
                                break 2;
                        }
                    case 295:

                        $token->opcode = Opcode::PHQL_T_INSERT;
                        return 0;

                    case 296:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'T':
                            case 't':
                                $yycursor += 1;
                                $yystate  = 312;
                                break 2;
                            default:
                                $yystate = 69;
                                break 2;
                        }
                    case 297:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                            case ':':
                            case 'A':
                            case 'B':
                            case 'C':
                            case 'D':
                            case 'E':
                            case 'F':
                            case 'G':
                            case 'H':
                            case 'I':
                            case 'J':
                            case 'K':
                            case 'L':
                            case 'M':
                            case 'N':
                            case 'O':
                            case 'P':
                            case 'Q':
                            case 'R':
                            case 'S':
                            case 'T':
                            case 'U':
                            case 'V':
                            case 'W':
                            case 'X':
                            case 'Y':
                            case 'Z':
                            case '\\':
                            case '_':
                            case 'a':
                            case 'b':
                            case 'c':
                            case 'd':
                            case 'e':
                            case 'f':
                            case 'g':
                            case 'h':
                            case 'i':
                            case 'j':
                            case 'k':
                            case 'l':
                            case 'm':
                            case 'n':
                            case 'o':
                            case 'p':
                            case 'q':
                            case 'r':
                            case 's':
                            case 't':
                            case 'u':
                            case 'v':
                            case 'w':
                            case 'x':
                            case 'y':
                            case 'z':
                                $yycursor += 1;
                                $yystate  = 45;
                                break 2;
                            default:
                                $yystate = 298;
                                break 2;
                        }
                    case 298:

                        $token->opcode = Opcode::PHQL_T_OFFSET;
                        return 0;

                    case 299:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                            case ':':
                            case 'A':
                            case 'B':
                            case 'C':
                            case 'D':
                            case 'E':
                            case 'F':
                            case 'G':
                            case 'H':
                            case 'I':
                            case 'J':
                            case 'K':
                            case 'L':
                            case 'M':
                            case 'N':
                            case 'O':
                            case 'P':
                            case 'Q':
                            case 'R':
                            case 'S':
                            case 'T':
                            case 'U':
                            case 'V':
                            case 'W':
                            case 'X':
                            case 'Y':
                            case 'Z':
                            case '\\':
                            case '_':
                            case 'a':
                            case 'b':
                            case 'c':
                            case 'd':
                            case 'e':
                            case 'f':
                            case 'g':
                            case 'h':
                            case 'i':
                            case 'j':
                            case 'k':
                            case 'l':
                            case 'm':
                            case 'n':
                            case 'o':
                            case 'p':
                            case 'q':
                            case 'r':
                            case 's':
                            case 't':
                            case 'u':
                            case 'v':
                            case 'w':
                            case 'x':
                            case 'y':
                            case 'z':
                                $yycursor += 1;
                                $yystate  = 45;
                                break 2;
                            default:
                                $yystate = 300;
                                break 2;
                        }
                    case 300:

                        $token->opcode = Opcode::PHQL_T_SELECT;
                        return 0;

                    case 301:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                            case ':':
                            case 'A':
                            case 'B':
                            case 'C':
                            case 'D':
                            case 'E':
                            case 'F':
                            case 'G':
                            case 'H':
                            case 'I':
                            case 'J':
                            case 'K':
                            case 'L':
                            case 'M':
                            case 'N':
                            case 'O':
                            case 'P':
                            case 'Q':
                            case 'R':
                            case 'S':
                            case 'T':
                            case 'U':
                            case 'V':
                            case 'W':
                            case 'X':
                            case 'Y':
                            case 'Z':
                            case '\\':
                            case '_':
                            case 'a':
                            case 'b':
                            case 'c':
                            case 'd':
                            case 'e':
                            case 'f':
                            case 'g':
                            case 'h':
                            case 'i':
                            case 'j':
                            case 'k':
                            case 'l':
                            case 'm':
                            case 'n':
                            case 'o':
                            case 'p':
                            case 'q':
                            case 'r':
                            case 's':
                            case 't':
                            case 'u':
                            case 'v':
                            case 'w':
                            case 'x':
                            case 'y':
                            case 'z':
                                $yycursor += 1;
                                $yystate  = 45;
                                break 2;
                            default:
                                $yystate = 302;
                                break 2;
                        }
                    case 302:

                        $token->opcode = Opcode::PHQL_T_UPDATE;
                        return 0;

                    case 303:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                            case ':':
                            case 'A':
                            case 'B':
                            case 'C':
                            case 'D':
                            case 'E':
                            case 'F':
                            case 'G':
                            case 'H':
                            case 'I':
                            case 'J':
                            case 'K':
                            case 'L':
                            case 'M':
                            case 'N':
                            case 'O':
                            case 'P':
                            case 'Q':
                            case 'R':
                            case 'S':
                            case 'T':
                            case 'U':
                            case 'V':
                            case 'W':
                            case 'X':
                            case 'Y':
                            case 'Z':
                            case '\\':
                            case '_':
                            case 'a':
                            case 'b':
                            case 'c':
                            case 'd':
                            case 'e':
                            case 'f':
                            case 'g':
                            case 'h':
                            case 'i':
                            case 'j':
                            case 'k':
                            case 'l':
                            case 'm':
                            case 'n':
                            case 'o':
                            case 'p':
                            case 'q':
                            case 'r':
                            case 's':
                            case 't':
                            case 'u':
                            case 'v':
                            case 'w':
                            case 'x':
                            case 'y':
                            case 'z':
                                $yycursor += 1;
                                $yystate  = 45;
                                break 2;
                            default:
                                $yystate = 304;
                                break 2;
                        }
                    case 304:

                        $token->opcode = Opcode::PHQL_T_VALUES;
                        return 0;

                    case 305:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                            case ':':
                            case 'A':
                            case 'B':
                            case 'C':
                            case 'D':
                            case 'E':
                            case 'F':
                            case 'G':
                            case 'H':
                            case 'I':
                            case 'J':
                            case 'K':
                            case 'L':
                            case 'M':
                            case 'N':
                            case 'O':
                            case 'P':
                            case 'Q':
                            case 'R':
                            case 'S':
                            case 'T':
                            case 'U':
                            case 'V':
                            case 'W':
                            case 'X':
                            case 'Y':
                            case 'Z':
                            case '\\':
                            case '_':
                            case 'a':
                            case 'b':
                            case 'c':
                            case 'd':
                            case 'e':
                            case 'f':
                            case 'g':
                            case 'h':
                            case 'i':
                            case 'j':
                            case 'k':
                            case 'l':
                            case 'm':
                            case 'n':
                            case 'o':
                            case 'p':
                            case 'q':
                            case 'r':
                            case 's':
                            case 't':
                            case 'u':
                            case 'v':
                            case 'w':
                            case 'x':
                            case 'y':
                            case 'z':
                                $yycursor += 1;
                                $yystate  = 45;
                                break 2;
                            default:
                                $yystate = 306;
                                break 2;
                        }
                    case 306:

                        $token->opcode = Opcode::PHQL_T_AGAINST;
                        return 0;

                    case 307:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                            case ':':
                            case 'A':
                            case 'B':
                            case 'C':
                            case 'D':
                            case 'E':
                            case 'F':
                            case 'G':
                            case 'H':
                            case 'I':
                            case 'J':
                            case 'K':
                            case 'L':
                            case 'M':
                            case 'N':
                            case 'O':
                            case 'P':
                            case 'Q':
                            case 'R':
                            case 'S':
                            case 'T':
                            case 'U':
                            case 'V':
                            case 'W':
                            case 'X':
                            case 'Y':
                            case 'Z':
                            case '\\':
                            case '_':
                            case 'a':
                            case 'b':
                            case 'c':
                            case 'd':
                            case 'e':
                            case 'f':
                            case 'g':
                            case 'h':
                            case 'i':
                            case 'j':
                            case 'k':
                            case 'l':
                            case 'm':
                            case 'n':
                            case 'o':
                            case 'p':
                            case 'q':
                            case 'r':
                            case 's':
                            case 't':
                            case 'u':
                            case 'v':
                            case 'w':
                            case 'x':
                            case 'y':
                            case 'z':
                                $yycursor += 1;
                                $yystate  = 45;
                                break 2;
                            default:
                                $yystate = 308;
                                break 2;
                        }
                    case 308:

                        $token->opcode = Opcode::PHQL_T_BETWEEN;
                        return 0;

                    case 309:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                            case ':':
                            case 'A':
                            case 'B':
                            case 'C':
                            case 'D':
                            case 'E':
                            case 'F':
                            case 'G':
                            case 'H':
                            case 'I':
                            case 'J':
                            case 'K':
                            case 'L':
                            case 'M':
                            case 'N':
                            case 'O':
                            case 'P':
                            case 'Q':
                            case 'R':
                            case 'S':
                            case 'T':
                            case 'U':
                            case 'V':
                            case 'W':
                            case 'X':
                            case 'Y':
                            case 'Z':
                            case '\\':
                            case '_':
                            case 'a':
                            case 'b':
                            case 'c':
                            case 'd':
                            case 'e':
                            case 'f':
                            case 'g':
                            case 'h':
                            case 'i':
                            case 'j':
                            case 'k':
                            case 'l':
                            case 'm':
                            case 'n':
                            case 'o':
                            case 'p':
                            case 'q':
                            case 'r':
                            case 's':
                            case 't':
                            case 'u':
                            case 'v':
                            case 'w':
                            case 'x':
                            case 'y':
                            case 'z':
                                $yycursor += 1;
                                $yystate  = 45;
                                break 2;
                            default:
                                $yystate = 310;
                                break 2;
                        }
                    case 310:

                        $token->opcode = Opcode::PHQL_T_CONVERT;
                        return 0;

                    case 311:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'T':
                            case 't':
                                $yycursor += 1;
                                $yystate  = 313;
                                break 2;
                            default:
                                $yystate = 46;
                                break 2;
                        }
                    case 312:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'W':
                            case 'w':
                                $yycursor += 1;
                                $yystate  = 315;
                                break 2;
                            default:
                                $yystate = 69;
                                break 2;
                        }
                    case 313:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':
                            case '7':
                            case '8':
                            case '9':
                            case ':':
                            case 'A':
                            case 'B':
                            case 'C':
                            case 'D':
                            case 'E':
                            case 'F':
                            case 'G':
                            case 'H':
                            case 'I':
                            case 'J':
                            case 'K':
                            case 'L':
                            case 'M':
                            case 'N':
                            case 'O':
                            case 'P':
                            case 'Q':
                            case 'R':
                            case 'S':
                            case 'T':
                            case 'U':
                            case 'V':
                            case 'W':
                            case 'X':
                            case 'Y':
                            case 'Z':
                            case '\\':
                            case '_':
                            case 'a':
                            case 'b':
                            case 'c':
                            case 'd':
                            case 'e':
                            case 'f':
                            case 'g':
                            case 'h':
                            case 'i':
                            case 'j':
                            case 'k':
                            case 'l':
                            case 'm':
                            case 'n':
                            case 'o':
                            case 'p':
                            case 'q':
                            case 'r':
                            case 's':
                            case 't':
                            case 'u':
                            case 'v':
                            case 'w':
                            case 'x':
                            case 'y':
                            case 'z':
                                $yycursor += 1;
                                $yystate  = 45;
                                break 2;
                            default:
                                $yystate = 314;
                                break 2;
                        }
                    case 314:

                        $token->opcode = Opcode::PHQL_T_DISTINCT;
                        return 0;

                    case 315:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'E':
                            case 'e':
                                $yycursor += 1;
                                $yystate  = 316;
                                break 2;
                            default:
                                $yystate = 69;
                                break 2;
                        }
                    case 316:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'E':
                            case 'e':
                                $yycursor += 1;
                                $yystate  = 317;
                                break 2;
                            default:
                                $yystate = 69;
                                break 2;
                        }
                    case 317:
                        $yych = $yyinput[$yycursor];
                        switch ($yych) {
                            case 'N':
                            case 'n':
                                $yycursor += 1;
                                $yystate  = 318;
                                break 2;
                            default:
                                $yystate = 69;
                                break 2;
                        }
                    case 318:

                        $token->opcode = Opcode::PHQL_T_BETWEEN_NOT;
                        return 0;

                    default:
                        throw new Exception("internal lexer error");
                }
            }
        }

        return $status;
    }
}
