<?php

declare(strict_types=1);

namespace Phalcon\Phql\Parser;

use Phalcon\Phql\Exception;
use Phalcon\Phql\Scanner\Opcode;
use Phalcon\Phql\Scanner\Scanner;
use Phalcon\Phql\Scanner\State;
use Phalcon\Phql\Scanner\Token;
use phql_Parser;

class Parser
{
    private ?Token $token = null;

    private string $debugFile = 'phql.txt';

    public function __construct(private readonly bool $debug = false)
    {
    }

    public function parse(string $phql): array
    {
        if (strlen($phql) === 0) {
            return [];
        }

        $debug = null;
        if ($this->debug) {
            $debug = fopen($this->debugFile, 'w+');
        }

        $codeLength = strlen($phql);
        $parserState = new State($phql);
        $parserStatus = new Status($parserState);
        $scanner = new Scanner($parserStatus->getState());

        $parser = new phql_Parser($parserStatus);
        $parser->phql_Trace($debug);

        $state = $parserStatus->getState();
        while (0 <= $scannerStatus = $scanner->scanForToken()) {
            $this->token = $scanner->getToken();
            $parserStatus->setToken($this->token);
            $state->setStartLength($codeLength - $state->getCursor());

            $opcode = $this->token->getOpcode();
            $state->setActiveToken($this->token);

            switch ($opcode) {
                case Opcode::PHQL_T_IGNORE:
                    break;

                case Opcode::PHQL_T_ADD:
                    $parser->phql_(phql_Parser::PHQL_PLUS);
                    break;

                case Opcode::PHQL_T_SUB:
                    $parser->phql_(phql_Parser::PHQL_MINUS);
                    break;

                case Opcode::PHQL_T_MUL:
                    $parser->phql_(phql_Parser::PHQL_TIMES);
                    break;

                case Opcode::PHQL_T_DIV:
                    $parser->phql_(phql_Parser::PHQL_DIVIDE);
                    break;

                case Opcode::PHQL_T_MOD:
                    $parser->phql_(phql_Parser::PHQL_MOD);
                    break;

                case Opcode::PHQL_T_AND:
                    $parser->phql_(phql_Parser::PHQL_AND);
                    break;

                case Opcode::PHQL_T_OR:
                    $parser->phql_(phql_Parser::PHQL_OR);
                    break;
                case Opcode::PHQL_T_EQUALS:
                    $parser->phql_(phql_Parser::PHQL_EQUALS);
                    break;
                case Opcode::PHQL_T_NOTEQUALS:
                    $parser->phql_(phql_Parser::PHQL_NOTEQUALS);
                    break;
                case Opcode::PHQL_T_LESS:
                    $parser->phql_(phql_Parser::PHQL_LESS);
                    break;
                case Opcode::PHQL_T_GREATER:
                    $parser->phql_(phql_Parser::PHQL_GREATER);
                    break;
                case Opcode::PHQL_T_GREATEREQUAL:
                    $parser->phql_(phql_Parser::PHQL_GREATEREQUAL);
                    break;
                case Opcode::PHQL_T_LESSEQUAL:
                    $parser->phql_(phql_Parser::PHQL_LESSEQUAL);
                    break;
                case Opcode::PHQL_T_IDENTIFIER:
                    $this->phqlParseWithToken($parser, Opcode::PHQL_T_IDENTIFIER, phql_Parser::PHQL_IDENTIFIER);
                    break;

                case Opcode::PHQL_T_DOT:
                    $parser->phql_(phql_Parser::PHQL_DOT);
                    break;
                case Opcode::PHQL_T_COMMA:
                    $parser->phql_(phql_Parser::PHQL_COMMA);
                    break;

                case Opcode::PHQL_T_PARENTHESES_OPEN:
                    $parser->phql_(phql_Parser::PHQL_PARENTHESES_OPEN);
                    break;
                case Opcode::PHQL_T_PARENTHESES_CLOSE:
                    $parser->phql_(phql_Parser::PHQL_PARENTHESES_CLOSE);
                    break;

                case Opcode::PHQL_T_LIKE:
                    $parser->phql_(phql_Parser::PHQL_LIKE);
                    break;
                case Opcode::PHQL_T_ILIKE:
                    $parser->phql_(phql_Parser::PHQL_ILIKE);
                    break;
                case Opcode::PHQL_T_NOT:
                    $parser->phql_(phql_Parser::PHQL_NOT);
                    break;
                case Opcode::PHQL_T_BITWISE_AND:
                    $parser->phql_(phql_Parser::PHQL_BITWISE_AND);
                    break;
                case Opcode::PHQL_T_BITWISE_OR:
                    $parser->phql_(phql_Parser::PHQL_BITWISE_OR);
                    break;
                case Opcode::PHQL_T_BITWISE_NOT:
                    $parser->phql_(phql_Parser::PHQL_BITWISE_NOT);
                    break;
                case Opcode::PHQL_T_BITWISE_XOR:
                    $parser->phql_(phql_Parser::PHQL_BITWISE_XOR);
                    break;
                case Opcode::PHQL_T_AGAINST:
                    $parser->phql_(phql_Parser::PHQL_AGAINST);
                    break;
                case Opcode::PHQL_T_CASE:
                    $parser->phql_(phql_Parser::PHQL_CASE);
                    break;
                case Opcode::PHQL_T_WHEN:
                    $parser->phql_(phql_Parser::PHQL_WHEN);
                    break;
                case Opcode::PHQL_T_THEN:
                    $parser->phql_(phql_Parser::PHQL_THEN);
                    break;
                case Opcode::PHQL_T_END:
                    $parser->phql_(phql_Parser::PHQL_END);
                    break;
                case Opcode::PHQL_T_ELSE:
                    $parser->phql_(phql_Parser::PHQL_ELSE);
                    break;
                case Opcode::PHQL_T_FOR:
                    $parser->phql_(phql_Parser::PHQL_FOR);
                    break;
                case Opcode::PHQL_T_WITH:
                    $parser->phql_(phql_Parser::PHQL_WITH);
                    break;

                case Opcode::PHQL_T_INTEGER:
                    if ($parserStatus->getEnableLiterals()) {
                        $this->phqlParseWithToken($parser, Opcode::PHQL_T_INTEGER, phql_Parser::PHQL_INTEGER);
                    } else {
                        $parserStatus->setSyntaxError("Literals are disabled in PHQL statements");
                        $parserStatus->setStatus(Status::PHQL_PARSING_FAILED);
                    }
                    break;
                case Opcode::PHQL_T_DOUBLE:
                    if ($parserStatus->getEnableLiterals()) {
                        $this->phqlParseWithToken($parser, Opcode::PHQL_T_DOUBLE, phql_Parser::PHQL_DOUBLE);
                    } else {
                        $parserStatus->setSyntaxError("Literals are disabled in PHQL statements");
                        $parserStatus->setStatus(Status::PHQL_PARSING_FAILED);
                    }
                    break;
                case Opcode::PHQL_T_STRING:
                    if ($parserStatus->getEnableLiterals()) {
                        $this->phqlParseWithToken($parser, Opcode::PHQL_T_STRING, phql_Parser::PHQL_STRING);
                    } else {
                        $parserStatus->setSyntaxError("Literals are disabled in PHQL statements");
                        $parserStatus->setStatus(Status::PHQL_PARSING_FAILED);
				    }
                    break;
                case Opcode::PHQL_T_TRUE:
                    if ($parserStatus->getEnableLiterals()) {
                        $parser->phql_(phql_Parser::PHQL_TRUE);
                    } else {
                        $parserStatus->setSyntaxError("Literals are disabled in PHQL statements");
                        $parserStatus->setStatus(Status::PHQL_PARSING_FAILED);
				}
                    break;
                case Opcode::PHQL_T_FALSE:
                    if ($parserStatus->getEnableLiterals()) {
                        $parser->phql_(phql_Parser::PHQL_FALSE);
                    } else {
                        $parserStatus->setSyntaxError("Literals are disabled in PHQL statements");
                        $parserStatus->setStatus(Status::PHQL_PARSING_FAILED);
                    }
                    break;
                case Opcode::PHQL_T_HINTEGER:
                    if ($parserStatus->getEnableLiterals()) {
                        $this->phqlParseWithToken($parser, Opcode::PHQL_T_HINTEGER, phql_Parser::PHQL_HINTEGER);
                    } else {
                        $parserStatus->setSyntaxError("Integers are disabled in PHQL statements");
                        $parserStatus->setStatus(Status::PHQL_PARSING_FAILED);
                    }
                    break;

                case Opcode::PHQL_T_NPLACEHOLDER:
                    $this->phqlParseWithToken($parser, Opcode::PHQL_T_NPLACEHOLDER, phql_Parser::PHQL_NPLACEHOLDER);
                    break;
                case Opcode::PHQL_T_SPLACEHOLDER:
                    $this->phqlParseWithToken($parser, Opcode::PHQL_T_SPLACEHOLDER, phql_Parser::PHQL_SPLACEHOLDER);
                    break;
                case Opcode::PHQL_T_BPLACEHOLDER:
                    $this->phqlParseWithToken($parser, Opcode::PHQL_T_BPLACEHOLDER, phql_Parser::PHQL_BPLACEHOLDER);
                    break;

                case Opcode::PHQL_T_FROM:
                    $parser->phql_(phql_Parser::PHQL_FROM);
                    break;
                case Opcode::PHQL_T_UPDATE:
                    $parser->phql_(phql_Parser::PHQL_UPDATE);
                    break;
                case Opcode::PHQL_T_SET:
                    $parser->phql_(phql_Parser::PHQL_SET);
                    break;
                case Opcode::PHQL_T_WHERE:
                    $parser->phql_(phql_Parser::PHQL_WHERE);
                    break;
                case Opcode::PHQL_T_DELETE:
                    $parser->phql_(phql_Parser::PHQL_DELETE);
                    break;
                case Opcode::PHQL_T_INSERT:
                    $parser->phql_(phql_Parser::PHQL_INSERT);
                    break;
                case Opcode::PHQL_T_INTO:
                    $parser->phql_(phql_Parser::PHQL_INTO);
                    break;
                case Opcode::PHQL_T_VALUES:
                    $parser->phql_(phql_Parser::PHQL_VALUES);
                    break;
                case Opcode::PHQL_T_SELECT:
                    $parser->phql_(phql_Parser::PHQL_SELECT);
                    break;
                case Opcode::PHQL_T_AS:
                    $parser->phql_(phql_Parser::PHQL_AS);
                    break;
                case Opcode::PHQL_T_ORDER:
                    $parser->phql_(phql_Parser::PHQL_ORDER);
                    break;
                case Opcode::PHQL_T_BY:
                    $parser->phql_(phql_Parser::PHQL_BY);
                    break;
                case Opcode::PHQL_T_LIMIT:
                    $parser->phql_(phql_Parser::PHQL_LIMIT);
                    break;
                case Opcode::PHQL_T_OFFSET:
                    $parser->phql_(phql_Parser::PHQL_OFFSET);
                    break;
                case Opcode::PHQL_T_GROUP:
                    $parser->phql_(phql_Parser::PHQL_GROUP);
                    break;
                case Opcode::PHQL_T_HAVING:
                    $parser->phql_(phql_Parser::PHQL_HAVING);
                    break;
                case Opcode::PHQL_T_ASC:
                    $parser->phql_(phql_Parser::PHQL_ASC);
                    break;
                case Opcode::PHQL_T_DESC:
                    $parser->phql_(phql_Parser::PHQL_DESC);
                    break;
                case Opcode::PHQL_T_IN:
                    $parser->phql_(phql_Parser::PHQL_IN);
                    break;
                case Opcode::PHQL_T_ON:
                    $parser->phql_(phql_Parser::PHQL_ON);
                    break;
                case Opcode::PHQL_T_INNER:
                    $parser->phql_(phql_Parser::PHQL_INNER);
                    break;
                case Opcode::PHQL_T_JOIN:
                    $parser->phql_(phql_Parser::PHQL_JOIN);
                    break;
                case Opcode::PHQL_T_LEFT:
                    $parser->phql_(phql_Parser::PHQL_LEFT);
                    break;
                case Opcode::PHQL_T_RIGHT:
                    $parser->phql_(phql_Parser::PHQL_RIGHT);
                    break;
                case Opcode::PHQL_T_CROSS:
                    $parser->phql_(phql_Parser::PHQL_CROSS);
                    break;
                case Opcode::PHQL_T_FULL:
                    $parser->phql_(phql_Parser::PHQL_FULL);
                    break;
                case Opcode::PHQL_T_OUTER:
                    $parser->phql_(phql_Parser::PHQL_OUTER);
                    break;
                case Opcode::PHQL_T_IS:
                    $parser->phql_(phql_Parser::PHQL_IS);
                    break;
                case Opcode::PHQL_T_NULL:
                    $parser->phql_(phql_Parser::PHQL_NULL);
                    break;
                case Opcode::PHQL_T_BETWEEN:
                    $parser->phql_(phql_Parser::PHQL_BETWEEN);
                    break;
                case Opcode::PHQL_T_BETWEEN_NOT:
                    $parser->phql_(phql_Parser::PHQL_BETWEEN_NOT);
                    break;
                case Opcode::PHQL_T_DISTINCT:
                    $parser->phql_(phql_Parser::PHQL_DISTINCT);
                    break;
                case Opcode::PHQL_T_ALL:
                    $parser->phql_(phql_Parser::PHQL_ALL);
                    break;
                case Opcode::PHQL_T_CAST:
                    $parser->phql_(phql_Parser::PHQL_CAST);
                    break;
                case Opcode::PHQL_T_CONVERT:
                    $parser->phql_(phql_Parser::PHQL_CONVERT);
                    break;
                case Opcode::PHQL_T_USING:
                    $parser->phql_(phql_Parser::PHQL_USING);
                    break;
                case Opcode::PHQL_T_EXISTS:
                    $parser->phql_(phql_Parser::PHQL_EXISTS);
                    break;

                default:
                    $parserStatus->setStatus(Status::PHQL_PARSING_FAILED);
                    $parserStatus->setSyntaxError("Scanner: Unknown opcode %d" . $opcode);
				break;
            }

            if ($parserStatus->getStatus() === Status::PHQL_PARSING_FAILED) {
                break;
            }

            $state->setEnd($state->getStart());
        }

        if ($scannerStatus === Scanner::PHQL_SCANNER_RETCODE_ERR || $scannerStatus === Scanner::PHQL_SCANNER_RETCODE_IMPOSSIBLE) {
            throw new Exception($parserStatus->getSyntaxError());
        } elseif ($scannerStatus === Scanner::PHQL_SCANNER_RETCODE_EOF) {
            $parser->phql_(0);
        }

        /**
         *  Set a unique id for the parsed ast
         * /
         * if (phalcon_globals_ptr->orm.cache_level >= 1) {
         * if (Z_TYPE_P(&parser_status->ret) == IS_ARRAY) {
         * add_assoc_long(&parser_status->ret, "id", phalcon_globals_ptr->orm.unique_cache_id++);
         * }
         * }
         *
         * ZVAL_ZVAL(*result, &parser_status->ret, 1, 1);
         *
         * /**
         *  Store the parsed definition in the cache
         * /
         * if (cache_level >= 0) {
         *
         * if (!phalcon_globals_ptr->orm.parser_cache) {
         * ALLOC_HASHTABLE(phalcon_globals_ptr->orm.parser_cache);
         * zend_hash_init(phalcon_globals_ptr->orm.parser_cache, 0, NULL, ZVAL_PTR_DTOR, 0);
         * }
         *
         * Z_TRY_ADDREF_P(*result);
         *
         * zend_hash_index_update(
         * phalcon_globals_ptr->orm.parser_cache,
         * phql_key,
         * result
         * );
         * }
         *
         * }
         * }
         * }
         */

        $state->setStartLength(0);
        $state->setActiveToken(0);

        if ($parserStatus->getStatus() !== Status::PHQL_PARSING_OK) {
            throw new Exception($parserStatus->getSyntaxError());
        }

        return $parser->getOutput();
    }

    private function phqlParseWithToken(
        phql_Parser $parser,
        int $opcode,
        int $parserCode,
    ): void {
        var_dump($this->token->getValue());
        $newToken = new Token();
        $newToken->setOpcode($opcode);
        $newToken->setValue($this->token->getValue());

        $this->token = $newToken;

        $parser->phql_($parserCode, $newToken);
    }
}
