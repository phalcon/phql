<?php
# vim:ts=2:sw=2:et:
/* Driver template for the LEMON parser generator.
** The author disclaims copyright to this source code.
*/
/* First off, code is included which follows the "include" declaration
** in the input file. */

#line 30 "c/parser.php.lemon"

#include "parser.php.inc.h"
#line 13 "c/parser.php.php"

/* The following structure represents a single element of the
** parser's stack.  Information stored includes:
**
**   +  The state number for the parser at this level of the stack.
**
**   +  The value of the token stored at this level of the stack.
**      (In other words, the "major" token.)
**
**   +  The semantic value stored at this level of the stack.  This is
**      the information used by the action routines in the grammar.
**      It is sometimes called the "minor" token.
*/

use Phalcon\Phql\Parser\Status;
use Phalcon\Phql\Scanner\Opcode;
use Phalcon\Phql\Scanner\Token;
use Phalcon\Phql\Tokens;

class phql_Parser
{
    protected mixed $output = [];

    public function getOutput(): mixed
    {
        return $this->output;
    }

    const PHQL_AGAINST = 1;                    /* Index of top element in stack */
    const PHQL_ALL = 39;                 /* Shifts left before out of the error */
    // phql_ARG_SDECL                /* A place to hold %extra_argument */
    /* The parser's stack */
    const PHQL_AND = 14; /* of YYSTACKDEPTH elements */
    const PHQL_AS = 42;
    const PHQL_ASC = 63;


    /* Next is all token values, in a form suitable for use by makeheaders.
** This section will be null unless lemon is run with the -m switch.
*/
    /*
** These constants (all generated automatically by the parser generator)
** specify the various kinds of tokens (terminals) that the parser
** understands.
**
** Each symbol here is a terminal symbol in the grammar.
*/
    const PHQL_BETWEEN = 2;
    const PHQL_BETWEEN_NOT = 3;
    const PHQL_BITWISE_AND = 18;
    const PHQL_BITWISE_NOT = 30;
    const PHQL_BITWISE_OR = 19;
    const PHQL_BITWISE_XOR = 20;
    const PHQL_BPLACEHOLDER = 74;
    const PHQL_BY = 62;
    const PHQL_CASE = 79;
    const PHQL_CAST = 76;
    const PHQL_COMMA = 35;
    const PHQL_CONVERT = 77;
    const PHQL_CROSS = 45;
    const PHQL_DELETE = 58;
    const PHQL_DESC = 64;
    const PHQL_DISTINCT = 38;
    const PHQL_DIVIDE = 21;
    const PHQL_DOT = 41;
    const PHQL_DOUBLE = 86;
    const PHQL_ELSE = 83;
    const PHQL_END = 80;
    const PHQL_EQUALS = 4;
    const PHQL_EXISTS = 75;
    const PHQL_FALSE = 88;
    const PHQL_FOR = 67;
    const PHQL_FROM = 37;
    const PHQL_FULL = 49;
    const PHQL_GREATER = 7;
    const PHQL_GREATEREQUAL = 8;
    const PHQL_GROUP = 65;
    const PHQL_HAVING = 66;
    const PHQL_HINTEGER = 71;
    const PHQL_IDENTIFIER = 40;
    const PHQL_ILIKE = 17;
    const PHQL_IN = 28;
    const PHQL_INNER = 43;
    const PHQL_INSERT = 51;
    const PHQL_INTEGER = 70;
    const PHQL_INTO = 52;
    const PHQL_IS = 27;
    const PHQL_JOIN = 44;
    const PHQL_LEFT = 46;
    const PHQL_LESS = 6;
    const PHQL_LESSEQUAL = 9;
    const PHQL_LIKE = 16;
    const PHQL_LIMIT = 68;
    const PHQL_MINUS = 25;
    const PHQL_MOD = 23;
    const PHQL_NOT = 29;
    const PHQL_NOTEQUALS = 5;
    const PHQL_NPLACEHOLDER = 72;
    const PHQL_NULL = 84;
    const PHQL_OFFSET = 69;
    const PHQL_ON = 50;
    const PHQL_OP_CONCAT = 26;
    const PHQL_OP_CONTAINED = 12;
    const PHQL_OP_CONTAINS = 11;
    const PHQL_OP_JSON_GET = 31;
    const PHQL_OP_JSON_GET_TEXT = 32;
    const PHQL_OP_JSON_PATH = 33;
    const PHQL_OP_JSON_PATH_TEXT = 34;
    const PHQL_OP_MATCHES = 10;
    const PHQL_OP_OVERLAPS = 13;
    const PHQL_OR = 15;
    const PHQL_ORDER = 61;
    const PHQL_OUTER = 47;
    const PHQL_PARENTHESES_CLOSE = 55;
    const PHQL_PARENTHESES_OPEN = 54;
    const PHQL_PLUS = 24;
    const PHQL_RIGHT = 48;
    const PHQL_SELECT = 36;
    const PHQL_SET = 57;
    const PHQL_SPLACEHOLDER = 73;
    const PHQL_STRING = 85;
    const PHQL_THEN = 82;
    const PHQL_TIMES = 22;
    const PHQL_TRUE = 87;
    const PHQL_UPDATE = 56;
    const PHQL_USING = 78;
    const PHQL_VALUES = 53;
    const PHQL_WHEN = 81;
    const PHQL_WHERE = 60;
    const PHQL_WITH = 59;
    const YYERRORSYMBOL = 89;
    const YYNOCODE   = 144;
    const YYWILDCARD = 144; /* No %wildcard in this grammar; use YYNOCODE so the wildcard path never matches */
    const YYNRULE = 171;
    const YYNSTATE = 314;
    const YYSTACKDEPTH = 100;
    /* The next thing included is series of defines which control
** various aspects of the generated parser.
**    YYCODETYPE         is the data type used for storing terminal
**                       and nonterminal numbers.  "unsigned char" is
**                       used if there are fewer than 250 terminals
**                       and nonterminals.  "int" is used otherwise.
**    YYNOCODE           is a number of type YYCODETYPE which corresponds
**                       to no legal terminal or nonterminal number.  This
**                       number is used to fill in empty slots of the hash
**                       table.
**    YYFALLBACK         If defined, this indicates that one or more tokens
**                       have fall-back values which should be used if the
**                       original value of the token will not parse.
**    YYACTIONTYPE       is the data type used for storing terminal
**                       and nonterminal numbers.  "unsigned char" is
**                       used if there are fewer than 250 rules and
**                       states combined.  "int" is used otherwise.
**    phql_TOKENTYPE     is the data type used for minor tokens given
**                       directly to the parser from the tokenizer.
**    YYMINORTYPE        is the data type used for all minor tokens.
**                       This is typically a union of many types, one of
**                       which is phql_TOKENTYPE.  The entry in the union
**                       for base tokens is called "yy0".
**    YYSTACKDEPTH       is the maximum depth of the parser's stack.
**    phql_ARG_SDECL     A static variable declaration for the %extra_argument
**    phql_ARG_PDECL     A parameter declaration for the %extra_argument
**    phql_ARG_STORE     Code to store %extra_argument into yypParser
**    phql_ARG_FETCH     Code to extract %extra_argument from yypParser
**    YYNSTATE           the combined number of states.
**    YYNRULE            the number of rules in the grammar
**    YYERRORSYMBOL      is the code number of the error symbol.  If not
**                       defined, then do no error processing.
*/
    const YY_REDUCE_MAX = 313;
#define phql_TOKENTYPE phql_parser_token*
    const YY_REDUCE_USE_DFLT = -1;
    const YY_SHIFT_MAX = 313;
    const YY_SHIFT_USE_DFLT = -2;
    var $YY_ACCEPT_ACTION;

    /* since we cant use expressions to initialize these as class
   * constants, we do so during parser init. */
    var $YY_ERROR_ACTION;
    var $YY_NO_ACTION;
    static $yyFallback = [];

    /* Next are that tables used to determine what action to take based on the
** current state and lookahead token.  These tables are used to implement
** functions that take a state number and lookahead value and return an
** action integer.
**
** Suppose the action integer is N.  Then the action is determined as
** follows
**
**   0 <= N < YYNSTATE                  Shift N.  That is, push the lookahead
**                                      token onto the stack and goto state N.
**
**   YYNSTATE <= N < YYNSTATE+YYNRULE   Reduce by rule N-YYNSTATE.
**
**   N == YYNSTATE+YYNRULE              A syntax error has occurred.
**
**   N == YYNSTATE+YYNRULE+1            The parser accepts its input.
**
**   N == YYNSTATE+YYNRULE+2            No such action.  Denotes unused
**                                      slots in the yy_action[] table.
**
** The action table is constructed as a single large table named yy_action[].
** Given state S and lookahead X, the action is computed as
**
**      yy_action[ yy_shift_ofst[S] + X ]
**
** If the index value yy_shift_ofst[S]+X is out of range or if the value
** yy_lookahead[yy_shift_ofst[S]+X] is not equal to X or if yy_shift_ofst[S]
** is equal to YY_SHIFT_USE_DFLT, it means that the action is not in the table
** and that yy_default[S] should be used instead.
**
** The formula above is for computing the action when the lookahead is
** a terminal symbol.  If the lookahead is a non-terminal (as occurs after
** a reduce action) then the yy_reduce_ofst[] array is used in place of
** the yy_shift_ofst[] array and YY_REDUCE_USE_DFLT is used in place of
** YY_SHIFT_USE_DFLT.
**
** The following are the tables generated in this section:
**
**  yy_action[]        A single table containing all actions.
**  yy_lookahead[]     A table containing the lookahead for each entry in
**                     yy_action.  Used to detect hash collisions.
**  yy_shift_ofst[]    For each state, the offset into yy_action for
**                     shifting terminals.
**  yy_reduce_ofst[]   For each state, the offset into yy_action for
**                     shifting non-terminals after a reduce.
**  yy_default[]       Default action for each state.
*/
    /** The following table contains information about every rule that
     * is used during the reduce.
     * Rather than pollute memory with a large number of arrays,
     * we store both data points in the same array, indexing by
     * rule number * 2.
     *   static const struct {
     *     YYCODETYPE lhs;         // Symbol on the left-hand side of the rule
     *     unsigned char nrhs;     // Number of right-hand side symbols in the rule
     *   } yyRuleInfo[] = {
     */
        static $yyRuleInfo = [
        90,
        1,
        91,
        1,
        91,
        1,
        91,
        1,
        91,
        1,
        92,
        7,
        96,
        6,
        103,
        1,
        103,
        1,
        103,
        0,
        104,
        3,
        104,
        1,
        107,
        1,
        107,
        3,
        107,
        3,
        107,
        2,
        107,
        1,
        105,
        3,
        105,
        1,
        106,
        1,
        106,
        0,
        110,
        2,
        110,
        1,
        111,
        1,
        112,
        4,
        115,
        2,
        115,
        1,
        115,
        0,
        113,
        2,
        113,
        2,
        113,
        3,
        113,
        2,
        113,
        3,
        113,
        2,
        113,
        3,
        113,
        2,
        113,
        1,
        116,
        2,
        116,
        0,
        93,
        7,
        93,
        10,
        117,
        3,
        117,
        1,
        120,
        1,
        118,
        3,
        118,
        1,
        121,
        1,
        94,
        3,
        122,
        5,
        124,
        3,
        124,
        1,
        125,
        3,
        127,
        1,
        95,
        3,
        128,
        3,
        109,
        3,
        109,
        2,
        109,
        1,
        109,
        5,
        109,
        7,
        109,
        6,
        109,
        4,
        109,
        5,
        109,
        3,
        130,
        3,
        130,
        1,
        129,
        1,
        114,
        1,
        97,
        2,
        97,
        0,
        100,
        3,
        100,
        0,
        131,
        3,
        131,
        1,
        132,
        1,
        132,
        2,
        132,
        2,
        98,
        3,
        98,
        0,
        133,
        3,
        133,
        1,
        134,
        1,
        99,
        2,
        99,
        0,
        102,
        2,
        102,
        0,
        101,
        2,
        101,
        4,
        101,
        4,
        101,
        0,
        123,
        2,
        123,
        0,
        135,
        1,
        135,
        1,
        135,
        1,
        135,
        1,
        135,
        1,
        108,
        2,
        108,
        3,
        108,
        3,
        108,
        3,
        108,
        3,
        108,
        3,
        108,
        3,
        108,
        3,
        108,
        3,
        108,
        3,
        108,
        3,
        108,
        3,
        108,
        3,
        108,
        3,
        108,
        3,
        108,
        3,
        108,
        3,
        108,
        3,
        108,
        3,
        108,
        3,
        108,
        3,
        108,
        3,
        108,
        3,
        108,
        3,
        108,
        3,
        108,
        3,
        108,
        3,
        108,
        4,
        108,
        3,
        108,
        4,
        108,
        5,
        108,
        6,
        108,
        3,
        108,
        5,
        108,
        6,
        108,
        4,
        108,
        3,
        108,
        6,
        108,
        6,
        108,
        4,
        137,
        2,
        137,
        1,
        138,
        4,
        138,
        2,
        108,
        1,
        139,
        5,
        140,
        1,
        140,
        0,
        141,
        1,
        141,
        0,
        136,
        3,
        136,
        1,
        142,
        1,
        142,
        1,
        108,
        3,
        108,
        4,
        108,
        3,
        108,
        3,
        108,
        2,
        108,
        2,
        108,
        3,
        108,
        1,
        108,
        1,
        108,
        1,
        108,
        1,
        108,
        1,
        108,
        1,
        108,
        1,
        108,
        1,
        108,
        1,
        108,
        1,
        108,
        1,
        126,
        3,
        126,
        1,
    ];
        static $yyRuleName = [
        /*   0 */
        "program ::= query_language",
        /*   1 */
        "query_language ::= select_statement",
        /*   2 */
        "query_language ::= insert_statement",
        /*   3 */
        "query_language ::= update_statement",
        /*   4 */
        "query_language ::= delete_statement",
        /*   5 */
        "select_statement ::= select_clause where_clause group_clause having_clause order_clause select_limit_clause for_update_clause",
        /*   6 */
        "select_clause ::= SELECT distinct_all column_list FROM associated_name_list join_list_or_null",
        /*   7 */
        "distinct_all ::= DISTINCT",
        /*   8 */
        "distinct_all ::= ALL",
        /*   9 */
        "distinct_all ::=",
        /*  10 */
        "column_list ::= column_list COMMA column_item",
        /*  11 */
        "column_list ::= column_item",
        /*  12 */
        "column_item ::= TIMES",
        /*  13 */
        "column_item ::= IDENTIFIER DOT TIMES",
        /*  14 */
        "column_item ::= expr AS IDENTIFIER",
        /*  15 */
        "column_item ::= expr IDENTIFIER",
        /*  16 */
        "column_item ::= expr",
        /*  17 */
        "associated_name_list ::= associated_name_list COMMA associated_name",
        /*  18 */
        "associated_name_list ::= associated_name",
        /*  19 */
        "join_list_or_null ::= join_list",
        /*  20 */
        "join_list_or_null ::=",
        /*  21 */
        "join_list ::= join_list join_item",
        /*  22 */
        "join_list ::= join_item",
        /*  23 */
        "join_item ::= join_clause",
        /*  24 */
        "join_clause ::= join_type aliased_or_qualified_name join_associated_name join_conditions",
        /*  25 */
        "join_associated_name ::= AS IDENTIFIER",
        /*  26 */
        "join_associated_name ::= IDENTIFIER",
        /*  27 */
        "join_associated_name ::=",
        /*  28 */
        "join_type ::= INNER JOIN",
        /*  29 */
        "join_type ::= CROSS JOIN",
        /*  30 */
        "join_type ::= LEFT OUTER JOIN",
        /*  31 */
        "join_type ::= LEFT JOIN",
        /*  32 */
        "join_type ::= RIGHT OUTER JOIN",
        /*  33 */
        "join_type ::= RIGHT JOIN",
        /*  34 */
        "join_type ::= FULL OUTER JOIN",
        /*  35 */
        "join_type ::= FULL JOIN",
        /*  36 */
        "join_type ::= JOIN",
        /*  37 */
        "join_conditions ::= ON expr",
        /*  38 */
        "join_conditions ::=",
        /*  39 */
        "insert_statement ::= INSERT INTO aliased_or_qualified_name VALUES PARENTHESES_OPEN values_list PARENTHESES_CLOSE",
        /*  40 */
        "insert_statement ::= INSERT INTO aliased_or_qualified_name PARENTHESES_OPEN field_list PARENTHESES_CLOSE VALUES PARENTHESES_OPEN values_list PARENTHESES_CLOSE",
        /*  41 */
        "values_list ::= values_list COMMA value_item",
        /*  42 */
        "values_list ::= value_item",
        /*  43 */
        "value_item ::= expr",
        /*  44 */
        "field_list ::= field_list COMMA field_item",
        /*  45 */
        "field_list ::= field_item",
        /*  46 */
        "field_item ::= IDENTIFIER",
        /*  47 */
        "update_statement ::= update_clause where_clause limit_clause",
        /*  48 */
        "update_clause ::= UPDATE associated_name join_list_or_null SET update_item_list",
        /*  49 */
        "update_item_list ::= update_item_list COMMA update_item",
        /*  50 */
        "update_item_list ::= update_item",
        /*  51 */
        "update_item ::= qualified_name EQUALS new_value",
        /*  52 */
        "new_value ::= expr",
        /*  53 */
        "delete_statement ::= delete_clause where_clause limit_clause",
        /*  54 */
        "delete_clause ::= DELETE FROM associated_name",
        /*  55 */
        "associated_name ::= aliased_or_qualified_name AS IDENTIFIER",
        /*  56 */
        "associated_name ::= aliased_or_qualified_name IDENTIFIER",
        /*  57 */
        "associated_name ::= aliased_or_qualified_name",
        /*  58 */
        "associated_name ::= aliased_or_qualified_name AS IDENTIFIER WITH with_item",
        /*  59 */
        "associated_name ::= aliased_or_qualified_name AS IDENTIFIER WITH PARENTHESES_OPEN with_list PARENTHESES_CLOSE",
        /*  60 */
        "associated_name ::= aliased_or_qualified_name IDENTIFIER WITH PARENTHESES_OPEN with_list PARENTHESES_CLOSE",
        /*  61 */
        "associated_name ::= aliased_or_qualified_name IDENTIFIER WITH with_item",
        /*  62 */
        "associated_name ::= aliased_or_qualified_name WITH PARENTHESES_OPEN with_list PARENTHESES_CLOSE",
        /*  63 */
        "associated_name ::= aliased_or_qualified_name WITH with_item",
        /*  64 */
        "with_list ::= with_list COMMA with_item",
        /*  65 */
        "with_list ::= with_item",
        /*  66 */
        "with_item ::= IDENTIFIER",
        /*  67 */
        "aliased_or_qualified_name ::= qualified_name",
        /*  68 */
        "where_clause ::= WHERE expr",
        /*  69 */
        "where_clause ::=",
        /*  70 */
        "order_clause ::= ORDER BY order_list",
        /*  71 */
        "order_clause ::=",
        /*  72 */
        "order_list ::= order_list COMMA order_item",
        /*  73 */
        "order_list ::= order_item",
        /*  74 */
        "order_item ::= expr",
        /*  75 */
        "order_item ::= expr ASC",
        /*  76 */
        "order_item ::= expr DESC",
        /*  77 */
        "group_clause ::= GROUP BY group_list",
        /*  78 */
        "group_clause ::=",
        /*  79 */
        "group_list ::= group_list COMMA group_item",
        /*  80 */
        "group_list ::= group_item",
        /*  81 */
        "group_item ::= expr",
        /*  82 */
        "having_clause ::= HAVING expr",
        /*  83 */
        "having_clause ::=",
        /*  84 */
        "for_update_clause ::= FOR UPDATE",
        /*  85 */
        "for_update_clause ::=",
        /*  86 */
        "select_limit_clause ::= LIMIT integer_or_placeholder",
        /*  87 */
        "select_limit_clause ::= LIMIT integer_or_placeholder COMMA integer_or_placeholder",
        /*  88 */
        "select_limit_clause ::= LIMIT integer_or_placeholder OFFSET integer_or_placeholder",
        /*  89 */
        "select_limit_clause ::=",
        /*  90 */
        "limit_clause ::= LIMIT integer_or_placeholder",
        /*  91 */
        "limit_clause ::=",
        /*  92 */
        "integer_or_placeholder ::= INTEGER",
        /*  93 */
        "integer_or_placeholder ::= HINTEGER",
        /*  94 */
        "integer_or_placeholder ::= NPLACEHOLDER",
        /*  95 */
        "integer_or_placeholder ::= SPLACEHOLDER",
        /*  96 */
        "integer_or_placeholder ::= BPLACEHOLDER",
        /*  97 */
        "expr ::= MINUS expr",
        /*  98 */
        "expr ::= expr MINUS expr",
        /*  99 */
        "expr ::= expr PLUS expr",
        /* 100 */
        "expr ::= expr TIMES expr",
        /* 101 */
        "expr ::= expr DIVIDE expr",
        /* 102 */
        "expr ::= expr MOD expr",
        /* 103 */
        "expr ::= expr AND expr",
        /* 104 */
        "expr ::= expr OR expr",
        /* 105 */
        "expr ::= expr BITWISE_AND expr",
        /* 106 */
        "expr ::= expr BITWISE_OR expr",
        /* 107 */
        "expr ::= expr BITWISE_XOR expr",
        /* 108 */
        "expr ::= expr OP_MATCHES expr",
        /* 109 */
        "expr ::= expr OP_CONTAINS expr",
        /* 110 */
        "expr ::= expr OP_CONTAINED expr",
        /* 111 */
        "expr ::= expr OP_OVERLAPS expr",
        /* 112 */
        "expr ::= expr OP_CONCAT expr",
        /* 113 */
        "expr ::= expr OP_JSON_GET expr",
        /* 114 */
        "expr ::= expr OP_JSON_GET_TEXT expr",
        /* 115 */
        "expr ::= expr OP_JSON_PATH expr",
        /* 116 */
        "expr ::= expr OP_JSON_PATH_TEXT expr",
        /* 117 */
        "expr ::= expr EQUALS expr",
        /* 118 */
        "expr ::= expr NOTEQUALS expr",
        /* 119 */
        "expr ::= expr LESS expr",
        /* 120 */
        "expr ::= expr GREATER expr",
        /* 121 */
        "expr ::= expr GREATEREQUAL expr",
        /* 122 */
        "expr ::= expr LESSEQUAL expr",
        /* 123 */
        "expr ::= expr LIKE expr",
        /* 124 */
        "expr ::= expr NOT LIKE expr",
        /* 125 */
        "expr ::= expr ILIKE expr",
        /* 126 */
        "expr ::= expr NOT ILIKE expr",
        /* 127 */
        "expr ::= expr IN PARENTHESES_OPEN argument_list PARENTHESES_CLOSE",
        /* 128 */
        "expr ::= expr NOT IN PARENTHESES_OPEN argument_list PARENTHESES_CLOSE",
        /* 129 */
        "expr ::= PARENTHESES_OPEN select_statement PARENTHESES_CLOSE",
        /* 130 */
        "expr ::= expr IN PARENTHESES_OPEN select_statement PARENTHESES_CLOSE",
        /* 131 */
        "expr ::= expr NOT IN PARENTHESES_OPEN select_statement PARENTHESES_CLOSE",
        /* 132 */
        "expr ::= EXISTS PARENTHESES_OPEN select_statement PARENTHESES_CLOSE",
        /* 133 */
        "expr ::= expr AGAINST expr",
        /* 134 */
        "expr ::= CAST PARENTHESES_OPEN expr AS IDENTIFIER PARENTHESES_CLOSE",
        /* 135 */
        "expr ::= CONVERT PARENTHESES_OPEN expr USING IDENTIFIER PARENTHESES_CLOSE",
        /* 136 */
        "expr ::= CASE expr when_clauses END",
        /* 137 */
        "when_clauses ::= when_clauses when_clause",
        /* 138 */
        "when_clauses ::= when_clause",
        /* 139 */
        "when_clause ::= WHEN expr THEN expr",
        /* 140 */
        "when_clause ::= ELSE expr",
        /* 141 */
        "expr ::= function_call",
        /* 142 */
        "function_call ::= IDENTIFIER PARENTHESES_OPEN distinct_or_null argument_list_or_null PARENTHESES_CLOSE",
        /* 143 */
        "distinct_or_null ::= DISTINCT",
        /* 144 */
        "distinct_or_null ::=",
        /* 145 */
        "argument_list_or_null ::= argument_list",
        /* 146 */
        "argument_list_or_null ::=",
        /* 147 */
        "argument_list ::= argument_list COMMA argument_item",
        /* 148 */
        "argument_list ::= argument_item",
        /* 149 */
        "argument_item ::= TIMES",
        /* 150 */
        "argument_item ::= expr",
        /* 151 */
        "expr ::= expr IS NULL",
        /* 152 */
        "expr ::= expr IS NOT NULL",
        /* 153 */
        "expr ::= expr BETWEEN expr",
        /* 154 */
        "expr ::= expr BETWEEN_NOT expr",
        /* 155 */
        "expr ::= NOT expr",
        /* 156 */
        "expr ::= BITWISE_NOT expr",
        /* 157 */
        "expr ::= PARENTHESES_OPEN expr PARENTHESES_CLOSE",
        /* 158 */
        "expr ::= qualified_name",
        /* 159 */
        "expr ::= INTEGER",
        /* 160 */
        "expr ::= HINTEGER",
        /* 161 */
        "expr ::= STRING",
        /* 162 */
        "expr ::= DOUBLE",
        /* 163 */
        "expr ::= NULL",
        /* 164 */
        "expr ::= TRUE",
        /* 165 */
        "expr ::= FALSE",
        /* 166 */
        "expr ::= NPLACEHOLDER",
        /* 167 */
        "expr ::= SPLACEHOLDER",
        /* 168 */
        "expr ::= BPLACEHOLDER",
        /* 169 */
        "qualified_name ::= IDENTIFIER DOT IDENTIFIER",
        /* 170 */
        "qualified_name ::= IDENTIFIER",
    ];
        static $yyTokenName = [
        '$',
        'AGAINST',
        'BETWEEN',
        'BETWEEN_NOT',
        'EQUALS',
        'NOTEQUALS',
        'LESS',
        'GREATER',
        'GREATEREQUAL',
        'LESSEQUAL',
        'OP_MATCHES',
        'OP_CONTAINS',
        'OP_CONTAINED',
        'OP_OVERLAPS',
        'AND',
        'OR',
        'LIKE',
        'ILIKE',
        'BITWISE_AND',
        'BITWISE_OR',
        'BITWISE_XOR',
        'DIVIDE',
        'TIMES',
        'MOD',
        'PLUS',
        'MINUS',
        'OP_CONCAT',
        'IS',
        'IN',
        'NOT',
        'BITWISE_NOT',
        'OP_JSON_GET',
        'OP_JSON_GET_TEXT',
        'OP_JSON_PATH',
        'OP_JSON_PATH_TEXT',
        'COMMA',
        'SELECT',
        'FROM',
        'DISTINCT',
        'ALL',
        'IDENTIFIER',
        'DOT',
        'AS',
        'INNER',
        'JOIN',
        'CROSS',
        'LEFT',
        'OUTER',
        'RIGHT',
        'FULL',
        'ON',
        'INSERT',
        'INTO',
        'VALUES',
        'PARENTHESES_OPEN',
        'PARENTHESES_CLOSE',
        'UPDATE',
        'SET',
        'DELETE',
        'WITH',
        'WHERE',
        'ORDER',
        'BY',
        'ASC',
        'DESC',
        'GROUP',
        'HAVING',
        'FOR',
        'LIMIT',
        'OFFSET',
        'INTEGER',
        'HINTEGER',
        'NPLACEHOLDER',
        'SPLACEHOLDER',
        'BPLACEHOLDER',
        'EXISTS',
        'CAST',
        'CONVERT',
        'USING',
        'CASE',
        'END',
        'WHEN',
        'THEN',
        'ELSE',
        'NULL',
        'STRING',
        'DOUBLE',
        'TRUE',
        'FALSE',
        'error',
        'program',
        'query_language',
        'select_statement',
        'insert_statement',
        'update_statement',
        'delete_statement',
        'select_clause',
        'where_clause',
        'group_clause',
        'having_clause',
        'order_clause',
        'select_limit_clause',
        'for_update_clause',
        'distinct_all',
        'column_list',
        'associated_name_list',
        'join_list_or_null',
        'column_item',
        'expr',
        'associated_name',
        'join_list',
        'join_item',
        'join_clause',
        'join_type',
        'aliased_or_qualified_name',
        'join_associated_name',
        'join_conditions',
        'values_list',
        'field_list',
        'value_list',
        'value_item',
        'field_item',
        'update_clause',
        'limit_clause',
        'update_item_list',
        'update_item',
        'qualified_name',
        'new_value',
        'delete_clause',
        'with_item',
        'with_list',
        'order_list',
        'order_item',
        'group_list',
        'group_item',
        'integer_or_placeholder',
        'argument_list',
        'when_clauses',
        'when_clause',
        'function_call',
        'distinct_or_null',
        'argument_list_or_null',
        'argument_item',
    ];
    public mixed $yyTraceFILE   = null;
    public ?string $yyTracePrompt = '';
        static $yy_action = [
        /*     0 */
        138,
        144,
        146,
        73,
        75,
        77,
        79,
        81,
        83,
        55,
        /*    10 */
        57,
        59,
        61,
        45,
        47,
        85,
        90,
        49,
        51,
        53,
        /*    20 */
        41,
        39,
        43,
        37,
        34,
        63,
        140,
        92,
        87,
        314,
        /*    30 */
        65,
        67,
        69,
        71,
        45,
        47,
        85,
        90,
        49,
        51,
        /*    40 */
        53,
        41,
        39,
        43,
        37,
        34,
        63,
        140,
        92,
        87,
        /*    50 */
        315,
        65,
        67,
        69,
        71,
        212,
        228,
        214,
        216,
        17,
        /*    60 */
        220,
        224,
        32,
        33,
        138,
        144,
        146,
        73,
        75,
        77,
        /*    70 */
        79,
        81,
        83,
        55,
        57,
        59,
        61,
        45,
        47,
        85,
        /*    80 */
        90,
        49,
        51,
        53,
        41,
        39,
        43,
        37,
        34,
        63,
        /*    90 */
        140,
        92,
        87,
        24,
        65,
        67,
        69,
        71,
        138,
        144,
        /*   100 */
        146,
        73,
        75,
        77,
        79,
        81,
        83,
        55,
        57,
        59,
        /*   110 */
        61,
        45,
        47,
        85,
        90,
        49,
        51,
        53,
        41,
        39,
        /*   120 */
        43,
        37,
        34,
        63,
        140,
        92,
        87,
        267,
        65,
        67,
        /*   130 */
        69,
        71,
        19,
        20,
        21,
        22,
        23,
        240,
        87,
        238,
        /*   140 */
        65,
        67,
        69,
        71,
        174,
        231,
        206,
        97,
        138,
        144,
        /*   150 */
        146,
        73,
        75,
        77,
        79,
        81,
        83,
        55,
        57,
        59,
        /*   160 */
        61,
        45,
        47,
        85,
        90,
        49,
        51,
        53,
        41,
        39,
        /*   170 */
        43,
        37,
        34,
        63,
        140,
        92,
        87,
        316,
        65,
        67,
        /*   180 */
        69,
        71,
        85,
        90,
        49,
        51,
        53,
        41,
        39,
        43,
        /*   190 */
        37,
        34,
        63,
        140,
        92,
        87,
        317,
        65,
        67,
        69,
        /*   200 */
        71,
        318,
        150,
        7,
        138,
        144,
        146,
        73,
        75,
        77,
        /*   210 */
        79,
        81,
        83,
        55,
        57,
        59,
        61,
        45,
        47,
        85,
        /*   220 */
        90,
        49,
        51,
        53,
        41,
        39,
        43,
        37,
        34,
        63,
        /*   230 */
        140,
        92,
        87,
        127,
        65,
        67,
        69,
        71,
        130,
        131,
        /*   240 */
        132,
        172,
        174,
        260,
        206,
        160,
        180,
        138,
        144,
        146,
        /*   250 */
        73,
        75,
        77,
        79,
        81,
        83,
        55,
        57,
        59,
        61,
        /*   260 */
        45,
        47,
        85,
        90,
        49,
        51,
        53,
        41,
        39,
        43,
        /*   270 */
        37,
        34,
        63,
        140,
        92,
        87,
        258,
        65,
        67,
        69,
        /*   280 */
        71,
        138,
        144,
        146,
        73,
        75,
        77,
        79,
        81,
        83,
        /*   290 */
        55,
        57,
        59,
        61,
        45,
        47,
        85,
        90,
        49,
        51,
        /*   300 */
        53,
        41,
        39,
        43,
        37,
        34,
        63,
        140,
        92,
        87,
        /*   310 */
        8,
        65,
        67,
        69,
        71,
        140,
        92,
        87,
        9,
        65,
        /*   320 */
        67,
        69,
        71,
        211,
        166,
        209,
        138,
        144,
        146,
        73,
        /*   330 */
        75,
        77,
        79,
        81,
        83,
        55,
        57,
        59,
        61,
        45,
        /*   340 */
        47,
        85,
        90,
        49,
        51,
        53,
        41,
        39,
        43,
        37,
        /*   350 */
        34,
        63,
        140,
        92,
        87,
        142,
        65,
        67,
        69,
        71,
        /*   360 */
        242,
        243,
        176,
        73,
        75,
        77,
        79,
        81,
        83,
        55,
        /*   370 */
        57,
        59,
        61,
        45,
        47,
        85,
        90,
        49,
        51,
        53,
        /*   380 */
        41,
        39,
        43,
        37,
        34,
        63,
        140,
        92,
        87,
        94,
        /*   390 */
        65,
        67,
        69,
        71,
        188,
        88,
        248,
        35,
        114,
        155,
        /*   400 */
        103,
        189,
        191,
        6,
        269,
        100,
        182,
        250,
        94,
        296,
        /*   410 */
        141,
        311,
        179,
        212,
        228,
        214,
        216,
        120,
        220,
        224,
        /*   420 */
        188,
        110,
        112,
        35,
        193,
        112,
        148,
        189,
        191,
        486,
        /*   430 */
        1,
        2,
        3,
        4,
        5,
        6,
        107,
        178,
        179,
        116,
        /*   440 */
        187,
        109,
        194,
        195,
        201,
        202,
        203,
        153,
        157,
        163,
        /*   450 */
        26,
        169,
        148,
        460,
        171,
        208,
        198,
        196,
        197,
        199,
        /*   460 */
        200,
        291,
        300,
        307,
        303,
        113,
        108,
        308,
        194,
        195,
        /*   470 */
        201,
        202,
        203,
        153,
        157,
        163,
        10,
        169,
        15,
        182,
        /*   480 */
        113,
        117,
        198,
        196,
        197,
        199,
        200,
        212,
        228,
        214,
        /*   490 */
        216,
        233,
        220,
        224,
        35,
        110,
        98,
        193,
        189,
        191,
        /*   500 */
        230,
        334,
        110,
        31,
        112,
        102,
        235,
        185,
        13,
        234,
        /*   510 */
        178,
        101,
        183,
        204,
        11,
        118,
        102,
        125,
        121,
        180,
        /*   520 */
        35,
        193,
        123,
        148,
        189,
        191,
        28,
        257,
        125,
        12,
        /*   530 */
        219,
        94,
        265,
        217,
        178,
        179,
        113,
        122,
        14,
        194,
        /*   540 */
        195,
        201,
        202,
        203,
        153,
        157,
        163,
        133,
        169,
        148,
        /*   550 */
        193,
        272,
        280,
        198,
        196,
        197,
        199,
        200,
        264,
        125,
        /*   560 */
        246,
        271,
        27,
        178,
        6,
        194,
        195,
        201,
        202,
        203,
        /*   570 */
        153,
        157,
        163,
        125,
        169,
        223,
        182,
        16,
        221,
        198,
        /*   580 */
        196,
        197,
        199,
        200,
        227,
        297,
        18,
        225,
        188,
        96,
        /*   590 */
        102,
        35,
        241,
        237,
        193,
        189,
        191,
        232,
        237,
        186,
        /*   600 */
        265,
        25,
        125,
        29,
        244,
        236,
        179,
        178,
        99,
        93,
        /*   610 */
        204,
        193,
        129,
        229,
        131,
        132,
        193,
        35,
        193,
        459,
        /*   620 */
        148,
        189,
        191,
        128,
        178,
        262,
        266,
        95,
        126,
        178,
        /*   630 */
        306,
        178,
        179,
        302,
        303,
        104,
        194,
        195,
        201,
        202,
        /*   640 */
        203,
        153,
        157,
        163,
        281,
        169,
        148,
        290,
        193,
        305,
        /*   650 */
        198,
        196,
        197,
        199,
        200,
        313,
        105,
        106,
        111,
        115,
        /*   660 */
        102,
        178,
        194,
        195,
        201,
        202,
        203,
        153,
        157,
        163,
        /*   670 */
        112,
        169,
        125,
        119,
        124,
        127,
        198,
        196,
        197,
        199,
        /*   680 */
        200,
        49,
        51,
        53,
        41,
        39,
        43,
        37,
        34,
        63,
        /*   690 */
        140,
        92,
        87,
        134,
        65,
        67,
        69,
        71,
        41,
        39,
        /*   700 */
        43,
        37,
        34,
        63,
        140,
        92,
        87,
        128,
        65,
        67,
        /*   710 */
        69,
        71,
        37,
        34,
        63,
        140,
        92,
        87,
        136,
        65,
        /*   720 */
        67,
        69,
        71,
        135,
        278,
        254,
        278,
        31,
        405,
        6,
        /*   730 */
        151,
        143,
        94,
        274,
        6,
        285,
        279,
        154,
        279,
        152,
        /*   740 */
        156,
        182,
        193,
        298,
        193,
        193,
        149,
        129,
        229,
        131,
        /*   750 */
        132,
        30,
        256,
        158,
        161,
        178,
        186,
        178,
        178,
        193,
        /*   760 */
        36,
        278,
        38,
        40,
        193,
        162,
        164,
        173,
        168,
        252,
        /*   770 */
        193,
        205,
        178,
        277,
        167,
        204,
        245,
        178,
        193,
        193,
        /*   780 */
        193,
        193,
        186,
        178,
        184,
        181,
        42,
        44,
        46,
        48,
        /*   790 */
        50,
        178,
        178,
        178,
        178,
        52,
        294,
        54,
        56,
        213,
        /*   800 */
        210,
        342,
        253,
        343,
        193,
        193,
        193,
        193,
        193,
        58,
        /*   810 */
        215,
        218,
        60,
        193,
        62,
        193,
        193,
        178,
        178,
        178,
        /*   820 */
        178,
        178,
        344,
        64,
        66,
        345,
        178,
        193,
        178,
        178,
        /*   830 */
        193,
        68,
        193,
        70,
        72,
        74,
        222,
        346,
        76,
        78,
        /*   840 */
        178,
        193,
        193,
        178,
        347,
        178,
        226,
        348,
        349,
        193,
        /*   850 */
        80,
        193,
        193,
        193,
        178,
        178,
        193,
        193,
        82,
        84,
        /*   860 */
        86,
        89,
        178,
        276,
        178,
        178,
        178,
        91,
        193,
        178,
        /*   870 */
        178,
        350,
        239,
        251,
        287,
        276,
        193,
        193,
        193,
        193,
        /*   880 */
        247,
        178,
        137,
        275,
        139,
        193,
        145,
        147,
        159,
        178,
        /*   890 */
        178,
        178,
        178,
        255,
        282,
        286,
        165,
        261,
        178,
        263,
        /*   900 */
        193,
        270,
        193,
        170,
        193,
        193,
        193,
        175,
        273,
        353,
        /*   910 */
        289,
        283,
        354,
        178,
        193,
        178,
        284,
        178,
        178,
        178,
        /*   920 */
        292,
        193,
        177,
        190,
        192,
        193,
        207,
        178,
        249,
        259,
        /*   930 */
        268,
        288,
        293,
        361,
        178,
        295,
        404,
        299,
        178,
        301,
        /*   940 */
        193,
        193,
        193,
        304,
        193,
        309,
        193,
        193,
        193,
        310,
        /*   950 */
        367,
        312,
        316,
        178,
        178,
        178,
        316,
        178,
        316,
        178,
        /*   960 */
        178,
        178,
    ];
        static $yy_default = [
        /*     0 */
        485,
        485,
        485,
        485,
        485,
        485,
        383,
        392,
        397,
        385,
        /*    10 */
        403,
        399,
        319,
        485,
        398,
        485,
        400,
        485,
        401,
        406,
        /*    20 */
        407,
        408,
        409,
        410,
        485,
        402,
        485,
        485,
        384,
        485,
        /*    30 */
        386,
        388,
        389,
        390,
        485,
        485,
        411,
        485,
        413,
        485,
        /*    40 */
        414,
        485,
        415,
        485,
        416,
        485,
        417,
        485,
        418,
        485,
        /*    50 */
        419,
        485,
        420,
        485,
        421,
        485,
        422,
        485,
        423,
        485,
        /*    60 */
        424,
        485,
        425,
        485,
        426,
        485,
        427,
        485,
        428,
        485,
        /*    70 */
        429,
        485,
        430,
        485,
        431,
        485,
        432,
        485,
        433,
        485,
        /*    80 */
        434,
        485,
        435,
        485,
        436,
        485,
        437,
        485,
        485,
        438,
        /*    90 */
        485,
        439,
        485,
        485,
        323,
        485,
        485,
        485,
        334,
        320,
        /*   100 */
        485,
        331,
        371,
        485,
        369,
        485,
        372,
        485,
        485,
        373,
        /*   110 */
        485,
        378,
        380,
        379,
        370,
        485,
        485,
        485,
        374,
        375,
        /*   120 */
        485,
        485,
        485,
        376,
        377,
        381,
        484,
        485,
        483,
        333,
        /*   130 */
        335,
        337,
        485,
        341,
        352,
        338,
        485,
        351,
        485,
        447,
        /*   140 */
        485,
        465,
        485,
        466,
        485,
        467,
        485,
        468,
        485,
        485,
        /*   150 */
        471,
        485,
        443,
        485,
        485,
        485,
        446,
        485,
        485,
        485,
        /*   160 */
        485,
        485,
        448,
        485,
        485,
        485,
        485,
        485,
        449,
        485,
        /*   170 */
        485,
        485,
        450,
        451,
        485,
        485,
        485,
        453,
        455,
        484,
        /*   180 */
        458,
        485,
        464,
        485,
        456,
        485,
        485,
        461,
        463,
        485,
        /*   190 */
        469,
        485,
        470,
        472,
        473,
        474,
        475,
        476,
        477,
        478,
        /*   200 */
        479,
        480,
        481,
        482,
        462,
        457,
        485,
        454,
        452,
        485,
        /*   210 */
        339,
        340,
        485,
        485,
        485,
        485,
        485,
        485,
        485,
        485,
        /*   220 */
        485,
        485,
        485,
        485,
        485,
        485,
        485,
        485,
        485,
        336,
        /*   230 */
        332,
        485,
        324,
        326,
        484,
        485,
        327,
        330,
        485,
        328,
        /*   240 */
        329,
        325,
        321,
        322,
        485,
        441,
        485,
        444,
        485,
        440,
        /*   250 */
        485,
        485,
        485,
        442,
        485,
        445,
        412,
        387,
        485,
        396,
        /*   260 */
        485,
        485,
        391,
        485,
        393,
        395,
        394,
        485,
        382,
        485,
        /*   270 */
        485,
        485,
        485,
        485,
        485,
        485,
        485,
        355,
        357,
        356,
        /*   280 */
        485,
        485,
        485,
        485,
        485,
        485,
        485,
        485,
        358,
        360,
        /*   290 */
        359,
        383,
        485,
        485,
        485,
        485,
        485,
        485,
        485,
        485,
        /*   300 */
        362,
        485,
        363,
        485,
        485,
        365,
        366,
        364,
        383,
        485,
        /*   310 */
        485,
        485,
        485,
        368,
    ];
        static $yy_lookahead = [
        /*     0 */
        1,
        2,
        3,
        4,
        5,
        6,
        7,
        8,
        9,
        10,
        /*    10 */
        11,
        12,
        13,
        14,
        15,
        16,
        17,
        18,
        19,
        20,
        /*    20 */
        21,
        22,
        23,
        24,
        25,
        26,
        27,
        28,
        29,
        0,
        /*    30 */
        31,
        32,
        33,
        34,
        14,
        15,
        16,
        17,
        18,
        19,
        /*    40 */
        20,
        21,
        22,
        23,
        24,
        25,
        26,
        27,
        28,
        29,
        /*    50 */
        0,
        31,
        32,
        33,
        34,
        43,
        44,
        45,
        46,
        35,
        /*    60 */
        48,
        49,
        63,
        64,
        1,
        2,
        3,
        4,
        5,
        6,
        /*    70 */
        7,
        8,
        9,
        10,
        11,
        12,
        13,
        14,
        15,
        16,
        /*    80 */
        17,
        18,
        19,
        20,
        21,
        22,
        23,
        24,
        25,
        26,
        /*    90 */
        27,
        28,
        29,
        69,
        31,
        32,
        33,
        34,
        1,
        2,
        /*   100 */
        3,
        4,
        5,
        6,
        7,
        8,
        9,
        10,
        11,
        12,
        /*   110 */
        13,
        14,
        15,
        16,
        17,
        18,
        19,
        20,
        21,
        22,
        /*   120 */
        23,
        24,
        25,
        26,
        27,
        28,
        29,
        60,
        31,
        32,
        /*   130 */
        33,
        34,
        70,
        71,
        72,
        73,
        74,
        40,
        29,
        42,
        /*   140 */
        31,
        32,
        33,
        34,
        81,
        35,
        83,
        37,
        1,
        2,
        /*   150 */
        3,
        4,
        5,
        6,
        7,
        8,
        9,
        10,
        11,
        12,
        /*   160 */
        13,
        14,
        15,
        16,
        17,
        18,
        19,
        20,
        21,
        22,
        /*   170 */
        23,
        24,
        25,
        26,
        27,
        28,
        29,
        0,
        31,
        32,
        /*   180 */
        33,
        34,
        16,
        17,
        18,
        19,
        20,
        21,
        22,
        23,
        /*   190 */
        24,
        25,
        26,
        27,
        28,
        29,
        0,
        31,
        32,
        33,
        /*   200 */
        34,
        0,
        55,
        97,
        1,
        2,
        3,
        4,
        5,
        6,
        /*   210 */
        7,
        8,
        9,
        10,
        11,
        12,
        13,
        14,
        15,
        16,
        /*   220 */
        17,
        18,
        19,
        20,
        21,
        22,
        23,
        24,
        25,
        26,
        /*   230 */
        27,
        28,
        29,
        41,
        31,
        32,
        33,
        34,
        111,
        112,
        /*   240 */
        113,
        80,
        81,
        65,
        83,
        42,
        54,
        1,
        2,
        3,
        /*   250 */
        4,
        5,
        6,
        7,
        8,
        9,
        10,
        11,
        12,
        13,
        /*   260 */
        14,
        15,
        16,
        17,
        18,
        19,
        20,
        21,
        22,
        23,
        /*   270 */
        24,
        25,
        26,
        27,
        28,
        29,
        66,
        31,
        32,
        33,
        /*   280 */
        34,
        1,
        2,
        3,
        4,
        5,
        6,
        7,
        8,
        9,
        /*   290 */
        10,
        11,
        12,
        13,
        14,
        15,
        16,
        17,
        18,
        19,
        /*   300 */
        20,
        21,
        22,
        23,
        24,
        25,
        26,
        27,
        28,
        29,
        /*   310 */
        98,
        31,
        32,
        33,
        34,
        27,
        28,
        29,
        99,
        31,
        /*   320 */
        32,
        33,
        34,
        40,
        78,
        42,
        1,
        2,
        3,
        4,
        /*   330 */
        5,
        6,
        7,
        8,
        9,
        10,
        11,
        12,
        13,
        14,
        /*   340 */
        15,
        16,
        17,
        18,
        19,
        20,
        21,
        22,
        23,
        24,
        /*   350 */
        25,
        26,
        27,
        28,
        29,
        29,
        31,
        32,
        33,
        34,
        /*   360 */
        38,
        39,
        82,
        4,
        5,
        6,
        7,
        8,
        9,
        10,
        /*   370 */
        11,
        12,
        13,
        14,
        15,
        16,
        17,
        18,
        19,
        20,
        /*   380 */
        21,
        22,
        23,
        24,
        25,
        26,
        27,
        28,
        29,
        36,
        /*   390 */
        31,
        32,
        33,
        34,
        22,
        16,
        17,
        25,
        40,
        92,
        /*   400 */
        42,
        29,
        30,
        96,
        51,
        35,
        108,
        28,
        36,
        56,
        /*   410 */
        84,
        58,
        40,
        43,
        44,
        45,
        46,
        59,
        48,
        49,
        /*   420 */
        22,
        35,
        40,
        25,
        126,
        40,
        54,
        29,
        30,
        90,
        /*   430 */
        91,
        92,
        93,
        94,
        95,
        96,
        54,
        139,
        40,
        54,
        /*   440 */
        142,
        55,
        70,
        71,
        72,
        73,
        74,
        75,
        76,
        77,
        /*   450 */
        61,
        79,
        54,
        55,
        137,
        138,
        84,
        85,
        86,
        87,
        /*   460 */
        88,
        122,
        124,
        125,
        126,
        129,
        130,
        128,
        70,
        71,
        /*   470 */
        72,
        73,
        74,
        75,
        76,
        77,
        100,
        79,
        68,
        108,
        /*   480 */
        129,
        130,
        84,
        85,
        86,
        87,
        88,
        43,
        44,
        45,
        /*   490 */
        46,
        22,
        48,
        49,
        25,
        35,
        105,
        126,
        29,
        30,
        /*   500 */
        109,
        57,
        35,
        108,
        40,
        114,
        41,
        136,
        67,
        40,
        /*   510 */
        139,
        109,
        141,
        142,
        101,
        55,
        114,
        126,
        54,
        54,
        /*   520 */
        25,
        126,
        55,
        54,
        29,
        30,
        131,
        132,
        126,
        102,
        /*   530 */
        44,
        36,
        108,
        47,
        139,
        40,
        129,
        130,
        56,
        70,
        /*   540 */
        71,
        72,
        73,
        74,
        75,
        76,
        77,
        114,
        79,
        54,
        /*   550 */
        126,
        53,
        54,
        84,
        85,
        86,
        87,
        88,
        134,
        126,
        /*   560 */
        92,
        114,
        62,
        139,
        96,
        70,
        71,
        72,
        73,
        74,
        /*   570 */
        75,
        76,
        77,
        126,
        79,
        44,
        108,
        135,
        47,
        84,
        /*   580 */
        85,
        86,
        87,
        88,
        44,
        109,
        135,
        47,
        22,
        104,
        /*   590 */
        114,
        25,
        107,
        108,
        126,
        29,
        30,
        107,
        108,
        35,
        /*   600 */
        108,
        135,
        126,
        35,
        136,
        22,
        40,
        139,
        106,
        54,
        /*   610 */
        142,
        126,
        110,
        111,
        112,
        113,
        126,
        25,
        126,
        55,
        /*   620 */
        54,
        29,
        30,
        40,
        139,
        133,
        134,
        103,
        40,
        139,
        /*   630 */
        108,
        139,
        40,
        125,
        126,
        40,
        70,
        71,
        72,
        73,
        /*   640 */
        74,
        75,
        76,
        77,
        118,
        79,
        54,
        121,
        126,
        127,
        /*   650 */
        84,
        85,
        86,
        87,
        88,
        109,
        59,
        129,
        129,
        59,
        /*   660 */
        114,
        139,
        70,
        71,
        72,
        73,
        74,
        75,
        76,
        77,
        /*   670 */
        40,
        79,
        126,
        129,
        129,
        41,
        84,
        85,
        86,
        87,
        /*   680 */
        88,
        18,
        19,
        20,
        21,
        22,
        23,
        24,
        25,
        26,
        /*   690 */
        27,
        28,
        29,
        115,
        31,
        32,
        33,
        34,
        21,
        22,
        /*   700 */
        23,
        24,
        25,
        26,
        27,
        28,
        29,
        40,
        31,
        32,
        /*   710 */
        33,
        34,
        24,
        25,
        26,
        27,
        28,
        29,
        50,
        31,
        /*   720 */
        32,
        33,
        34,
        116,
        108,
        92,
        108,
        108,
        0,
        96,
        /*   730 */
        92,
        84,
        36,
        117,
        96,
        117,
        120,
        54,
        120,
        55,
        /*   740 */
        55,
        108,
        126,
        106,
        126,
        126,
        108,
        110,
        111,
        112,
        /*   750 */
        113,
        132,
        108,
        54,
        40,
        139,
        35,
        139,
        139,
        126,
        /*   760 */
        108,
        108,
        108,
        108,
        126,
        55,
        54,
        138,
        55,
        136,
        /*   770 */
        126,
        38,
        139,
        120,
        40,
        142,
        55,
        139,
        126,
        126,
        /*   780 */
        126,
        126,
        35,
        139,
        55,
        140,
        108,
        108,
        108,
        108,
        /*   790 */
        108,
        139,
        139,
        139,
        139,
        108,
        68,
        108,
        108,
        44,
        /*   800 */
        40,
        40,
        55,
        40,
        126,
        126,
        126,
        126,
        126,
        108,
        /*   810 */
        44,
        44,
        108,
        126,
        108,
        126,
        126,
        139,
        139,
        139,
        /*   820 */
        139,
        139,
        40,
        108,
        108,
        40,
        139,
        126,
        139,
        139,
        /*   830 */
        126,
        108,
        126,
        108,
        108,
        108,
        44,
        40,
        108,
        108,
        /*   840 */
        139,
        126,
        126,
        139,
        40,
        139,
        44,
        40,
        40,
        126,
        /*   850 */
        108,
        126,
        126,
        126,
        139,
        139,
        126,
        126,
        108,
        108,
        /*   860 */
        108,
        108,
        139,
        35,
        139,
        139,
        139,
        108,
        126,
        139,
        /*   870 */
        139,
        40,
        40,
        54,
        35,
        35,
        126,
        126,
        126,
        126,
        /*   880 */
        55,
        139,
        108,
        55,
        108,
        126,
        108,
        108,
        108,
        139,
        /*   890 */
        139,
        139,
        139,
        55,
        55,
        55,
        108,
        62,
        139,
        35,
        /*   900 */
        126,
        52,
        126,
        108,
        126,
        126,
        126,
        108,
        54,
        0,
        /*   910 */
        40,
        53,
        0,
        139,
        126,
        139,
        54,
        139,
        139,
        139,
        /*   920 */
        97,
        126,
        108,
        108,
        108,
        126,
        108,
        139,
        108,
        108,
        /*   930 */
        108,
        121,
        123,
        0,
        139,
        135,
        0,
        57,
        139,
        35,
        /*   940 */
        126,
        126,
        126,
        4,
        126,
        97,
        126,
        126,
        126,
        123,
        /*   950 */
        0,
        37,
        143,
        139,
        139,
        139,
        143,
        139,
        143,
        139,
        /*   960 */
        139,
        139,
    ];
        static $yy_reduce_ofst = [
        /*     0 */
        339,
        -1,
        -1,
        -1,
        -1,
        -1,
        106,
        212,
        219,
        376,
        /*    10 */
        413,
        427,
        -1,
        -1,
        -1,
        442,
        -1,
        451,
        -1,
        -1,
        /*    20 */
        -1,
        -1,
        -1,
        -1,
        466,
        -1,
        -1,
        395,
        -1,
        619,
        /*    30 */
        -1,
        -1,
        -1,
        -1,
        644,
        652,
        -1,
        654,
        -1,
        655,
        /*    40 */
        -1,
        678,
        -1,
        679,
        -1,
        680,
        -1,
        681,
        -1,
        682,
        /*    50 */
        -1,
        687,
        -1,
        689,
        -1,
        690,
        -1,
        701,
        -1,
        704,
        /*    60 */
        -1,
        706,
        -1,
        715,
        -1,
        716,
        -1,
        723,
        -1,
        725,
        /*    70 */
        -1,
        726,
        -1,
        727,
        -1,
        730,
        -1,
        731,
        -1,
        742,
        /*    80 */
        -1,
        750,
        -1,
        751,
        -1,
        752,
        -1,
        -1,
        753,
        -1,
        /*    90 */
        759,
        -1,
        -1,
        468,
        524,
        485,
        -1,
        391,
        502,
        -1,
        /*   100 */
        402,
        -1,
        -1,
        -1,
        -1,
        528,
        -1,
        336,
        -1,
        -1,
        /*   110 */
        529,
        -1,
        -1,
        -1,
        -1,
        544,
        351,
        -1,
        -1,
        -1,
        /*   120 */
        545,
        407,
        -1,
        -1,
        -1,
        -1,
        -1,
        -1,
        -1,
        127,
        /*   130 */
        -1,
        -1,
        433,
        578,
        607,
        -1,
        774,
        -1,
        776,
        -1,
        /*   140 */
        -1,
        -1,
        -1,
        -1,
        778,
        -1,
        779,
        -1,
        638,
        -1,
        /*   150 */
        -1,
        -1,
        -1,
        -1,
        307,
        -1,
        -1,
        -1,
        780,
        -1,
        /*   160 */
        -1,
        -1,
        -1,
        -1,
        788,
        -1,
        -1,
        -1,
        -1,
        795,
        /*   170 */
        317,
        629,
        -1,
        -1,
        799,
        -1,
        814,
        -1,
        -1,
        -1,
        /*   180 */
        645,
        371,
        -1,
        -1,
        -1,
        -1,
        298,
        -1,
        -1,
        815,
        /*   190 */
        -1,
        816,
        -1,
        -1,
        -1,
        -1,
        -1,
        -1,
        -1,
        -1,
        /*   200 */
        -1,
        -1,
        -1,
        -1,
        -1,
        -1,
        818,
        -1,
        -1,
        -1,
        /*   210 */
        -1,
        -1,
        -1,
        -1,
        -1,
        -1,
        -1,
        -1,
        -1,
        -1,
        /*   220 */
        -1,
        -1,
        -1,
        -1,
        -1,
        -1,
        -1,
        -1,
        -1,
        -1,
        /*   230 */
        -1,
        490,
        -1,
        -1,
        -1,
        -1,
        -1,
        -1,
        -1,
        -1,
        /*   240 */
        -1,
        -1,
        -1,
        -1,
        -1,
        -1,
        -1,
        -1,
        820,
        -1,
        /*   250 */
        -1,
        633,
        -1,
        -1,
        -1,
        -1,
        -1,
        -1,
        821,
        -1,
        /*   260 */
        -1,
        492,
        -1,
        424,
        -1,
        -1,
        -1,
        822,
        -1,
        -1,
        /*   270 */
        447,
        -1,
        -1,
        616,
        -1,
        -1,
        653,
        -1,
        -1,
        -1,
        /*   280 */
        526,
        -1,
        -1,
        -1,
        618,
        -1,
        -1,
        810,
        -1,
        -1,
        /*   290 */
        -1,
        823,
        809,
        -1,
        800,
        -1,
        476,
        637,
        -1,
        338,
        /*   300 */
        -1,
        508,
        -1,
        -1,
        522,
        -1,
        -1,
        -1,
        848,
        826,
        /*   310 */
        -1,
        -1,
        546,
        -1,
    ];

    /* The next table maps tokens into fallback tokens.  If a construct
** like the following:
**
**      %fallback ID X Y Z.
**
** appears in the grammer, then ID becomes a fallback token for X, Y,
** and Z.  Whenever one of the tokens X, Y, or Z is input to the parser
** but it does not parse, the type of the token is changed to ID and
** the parse is retried before an error is thrown.
*/
        static $yy_shift_ofst = [
        /*     0 */
        353,
        29,
        50,
        177,
        196,
        201,
        67,
        178,
        210,
        389,
        /*    10 */
        410,
        441,
        -2,
        482,
        -2,
        62,
        24,
        62,
        -2,
        -2,
        /*    20 */
        -2,
        -2,
        -2,
        -2,
        62,
        -2,
        500,
        592,
        568,
        592,
        /*    30 */
        -2,
        -1,
        -2,
        -2,
        592,
        592,
        288,
        592,
        288,
        592,
        /*    40 */
        688,
        592,
        688,
        592,
        688,
        592,
        166,
        592,
        166,
        592,
        /*    50 */
        677,
        592,
        677,
        592,
        677,
        592,
        20,
        592,
        20,
        592,
        /*    60 */
        20,
        592,
        20,
        592,
        288,
        592,
        -2,
        592,
        -2,
        592,
        /*    70 */
        -2,
        592,
        -2,
        592,
        20,
        592,
        20,
        592,
        20,
        592,
        /*    80 */
        20,
        592,
        20,
        592,
        20,
        592,
        663,
        379,
        592,
        109,
        /*    90 */
        592,
        663,
        555,
        372,
        322,
        469,
        110,
        588,
        370,
        -2,
        /*   100 */
        588,
        -2,
        358,
        595,
        597,
        382,
        -2,
        630,
        386,
        -2,
        /*   110 */
        630,
        -2,
        -2,
        -2,
        600,
        385,
        630,
        460,
        -2,
        -2,
        /*   120 */
        464,
        630,
        467,
        -2,
        -2,
        -2,
        634,
        667,
        -2,
        12,
        /*   130 */
        -2,
        -2,
        588,
        283,
        668,
        -2,
        592,
        325,
        592,
        325,
        /*   140 */
        326,
        -2,
        647,
        -2,
        592,
        359,
        592,
        359,
        495,
        147,
        /*   150 */
        -2,
        684,
        -2,
        683,
        696,
        685,
        -2,
        699,
        592,
        203,
        /*   160 */
        714,
        710,
        -2,
        712,
        592,
        246,
        734,
        713,
        -2,
        592,
        /*   170 */
        63,
        161,
        -2,
        -2,
        592,
        280,
        592,
        325,
        -2,
        192,
        /*   180 */
        733,
        398,
        325,
        729,
        -2,
        564,
        566,
        -2,
        -2,
        592,
        /*   190 */
        109,
        592,
        109,
        -2,
        -2,
        -2,
        -2,
        -2,
        -2,
        -2,
        /*   200 */
        -2,
        -2,
        -2,
        -2,
        -2,
        -2,
        592,
        325,
        -2,
        760,
        /*   210 */
        -2,
        -2,
        755,
        761,
        766,
        763,
        486,
        767,
        782,
        785,
        /*   220 */
        531,
        792,
        797,
        804,
        540,
        802,
        807,
        808,
        831,
        -2,
        /*   230 */
        -2,
        469,
        -2,
        -2,
        465,
        583,
        -2,
        97,
        832,
        -2,
        /*   240 */
        -2,
        -2,
        -2,
        -2,
        721,
        -2,
        825,
        -2,
        592,
        109,
        /*   250 */
        819,
        372,
        747,
        -2,
        838,
        -2,
        288,
        -2,
        592,
        325,
        /*   260 */
        835,
        592,
        864,
        592,
        -2,
        325,
        -2,
        592,
        325,
        849,
        /*   270 */
        588,
        498,
        854,
        592,
        828,
        909,
        592,
        -2,
        325,
        -2,
        /*   280 */
        870,
        839,
        858,
        862,
        592,
        840,
        912,
        870,
        -2,
        -2,
        /*   290 */
        -2,
        67,
        728,
        933,
        62,
        936,
        588,
        444,
        880,
        588,
        /*   300 */
        904,
        588,
        -2,
        939,
        592,
        -2,
        325,
        -2,
        67,
        728,
        /*   310 */
        950,
        914,
        588,
        -2,
    ];

    /*
** Turn parser tracing on by giving a stream to which to write the trace
** and a prompt to preface each trace message.  Tracing is turned off
** by making either argument NULL
**
** Inputs:
** <ul>
** <li> A FILE* to which trace output should be written.
**      If NULL, then tracing is turned off.
** <li> A prefix string written at the beginning of every
**      line of trace output.  If NULL, then tracing is
**      turned off.
** </ul>
**
** Outputs:
** None.
*/
    public int $yyerrcnt = -1;

    /* For tracing shifts, the names of all terminals and nonterminals
** are required.  The following table supplies these names */
    public int $yyidx = -1;

    /* For tracing reduce actions, the names of all rules are required.
*/
    public array $yystack = [];

    /*
** This function returns the symbolic name associated with a token
** value.
*/

    public function __construct(private readonly Status $status)
    {
    }

    function __destruct()
    {
        while ($this->yyidx >= 0) {
            $this->yy_pop_parser_stack();
        }
    }

    /* The following function deletes the value associated with a
** symbol.  The symbol can be either a terminal or nonterminal.
** "yymajor" is the symbol code, and "yypminor" is a pointer to
** the value.
*/

    public function phql_(
        $yymajor,                 /* The major token code number */
        $yyminor = null           /* The value for the token */
    ): void
    {
        $yyendofinput = ($yymajor == 0);     /* True if we are at the end of input */
        $yyerrorhit = 0;   /* True if yymajor has invoked an error */

        /* (re)initialize the parser, if necessary */
        if ($this->yyidx < 0) {
            $this->yyidx = 0;
            $this->yyerrcnt = -1;
            $ent = new phql_yyStackEntry;
            $ent->stateno = 0;
            $ent->major = 0;
            $this->yystack = [0 => $ent];

            $this->YY_NO_ACTION = self::YYNSTATE + self::YYNRULE + 2;
            $this->YY_ACCEPT_ACTION = self::YYNSTATE + self::YYNRULE + 1;
            $this->YY_ERROR_ACTION = self::YYNSTATE + self::YYNRULE;
        }

        if ($this->yyTraceFILE) {
            fwrite($this->yyTraceFILE, sprintf(
                "%sInput %s\n",
                $this->yyTracePrompt,
                self::$yyTokenName[$yymajor]
            ));
        }

        do {
            $yyact = $this->yy_find_shift_action($yymajor);
            if ($yyact < self::YYNSTATE) {
                $this->yy_shift($yyact, $yymajor, $yyminor);
                $this->yyerrcnt--;
                if ($yyendofinput && $this->yyidx >= 0) {
                    $yymajor = 0;
                } else {
                    $yymajor = self::YYNOCODE;
                }
            } else {
                if ($yyact < self::YYNSTATE + self::YYNRULE) {
                    $this->yy_reduce($yyact - self::YYNSTATE);
                } else {
                    if ($yyact == $this->YY_ERROR_ACTION) {
                        if ($this->yyTraceFILE) {
                            fwrite($this->yyTraceFILE, sprintf("%sSyntax Error!\n", $this->yyTracePrompt));
                        }
                        if (self::YYERRORSYMBOL) {
                            /**
                             * A syntax error has occurred.
                             * The response to an error depends upon whether or not the
                             * grammar defines an error token "ERROR".
                             *
                             * This is what we do if the grammar does define ERROR:
                             *
                             *  * Call the %syntax_error function.
                             *
                             *  * Begin popping the stack until we enter a state where
                             *    it is legal to shift the error symbol, then shift
                             *    the error symbol.
                             *
                             *  * Set the error count to three.
                             *
                             *  * Begin accepting and shifting new tokens.  No new error
                             *    processing will occur until three tokens have been
                             *    shifted successfully.
                             **
                             */
                            if ($this->yyerrcnt < 0) {
                                $this->yy_syntax_error();
                            }
                            $yymx = $this->yystack[$this->yyidx]->major;
                            if ($yymx == self::YYERRORSYMBOL || $yyerrorhit) {
                                if ($this->yyTraceFILE) {
                                    fwrite($this->yyTraceFILE, sprintf(
                                        "%sDiscard input token %s\n",
                                        $this->yyTracePrompt,
                                        self::$yyTokenName[$yymajor]
                                    ));
                                }
                                $this->yy_destructor($yymajor, $yyminor);
                                $yymajor = self::YYNOCODE;
                            } else {
                                while (
                                    $this->yyidx >= 0 &&
                                    $yymx != self::YYERRORSYMBOL &&
                                    ($yyact = $this->yy_find_reduce_action(
                                        $this->yystack[$this->yyidx]->stateno,
                                        self::YYERRORSYMBOL
                                    )) >= self::YYNSTATE
                                ) {
                                    $this->yy_pop_parser_stack();
                                }
                                if ($this->yyidx < 0 || $yymajor == 0) {
                                    $this->yy_destructor($yymajor, $yyminor);
                                    $this->yy_parse_failed();
                                    $yymajor = self::YYNOCODE;
                                } else {
                                    if ($yymx != self::YYERRORSYMBOL) {
                                        $this->yy_shift($yyact, self::YYERRORSYMBOL, 0);
                                    }
                                }
                            }
                            $this->yyerrcnt = 3;
                            $yyerrorhit = 1;
                        } else {  /* YYERRORSYMBOL is not defined */
                            /* This is what we do if the grammar does not define ERROR:
                             *
                             *  * Report an error message, and throw away the input token.
                             *
                             *  * If the input token is $, then fail the parse.
                             *
                             * As before, subsequent error messages are suppressed until
                             * three input tokens have been successfully shifted.
                             */
                            if ($this->yyerrcnt <= 0) {
                                $this->yy_syntax_error();
                            }
                            $this->yyerrcnt = 3;
                            $this->yy_destructor($yymajor, $yyminor);
                            if ($yyendofinput) {
                                $this->yy_parse_failed();
                            }
                            $yymajor = self::YYNOCODE;
                        }
                    } else {
                        $this->yy_accept();
                        $yymajor = self::YYNOCODE;
                    }
                }
            }
        } while ($yymajor != self::YYNOCODE && $this->yyidx >= 0);
    }

    /*
** Pop the parser's stack once.
**
** If there is a destructor routine associated with the token which
** is popped from the stack, then call it.
**
** Return the major token number for the symbol popped.
*/

    function phql_TokenName(/* int */ $tokenType)
    {
        if (isset(self::$yyTokenName[$tokenType])) {
            return self::$yyTokenName[$tokenType];
        }

        return "Unknown";
    }

    /*
** Deallocate and destroy a parser.  Destructors are all called for
** all stack elements before shutting the parser down.
**
** Inputs:
** <ul>
** <li>  A pointer to the parser.  This should be a pointer
**       obtained from phql_Alloc.
** <li>  A pointer to a function used to reclaim memory obtained
**       from malloc.
** </ul>
*/

    function phql_Trace($TraceFILE, $zTracePrompt = ''): void
    {
        $this->yyTraceFILE = $TraceFILE;
        $this->yyTracePrompt = $zTracePrompt;
        if ($this->yyTraceFILE === null) {
            $this->yyTracePrompt = null;
        } elseif ($this->yyTracePrompt === null) {
            $this->yyTraceFILE = null;
        }
    }

    /*
** Find the appropriate action for a parser given the non-terminal
** look-ahead token iLookAhead.
**
** If the look-ahead token is YYNOCODE, then check to see if the action is
** independent of the look-ahead.  If it is, return the action, otherwise
** return YY_NO_ACTION.
*/

    private function yy_accept()
    {
        if ($this->yyTraceFILE) {
            fwrite($this->yyTraceFILE, sprintf("%sAccept!\n", $this->yyTracePrompt));
        }
        while ($this->yyidx >= 0) {
            $this->yy_pop_parser_stack();
        }
        /* Here code is inserted which will be executed whenever the
  ** parser accepts */
    }

    /*
** Perform a shift action.
*/

    private function yy_destructor($yymajor, $yypminor)
    {
        switch ($yymajor) {
            /* Here is inserted the actions which take place when a
    ** terminal or non-terminal is destroyed.  This can happen
    ** when the symbol is popped from the stack during a
    ** reduce or during error processing or when a parser is
    ** being destroyed before it is finished parsing.
    **
    ** Note: during a reduce, the only symbols destroyed are those
    ** which appear on the RHS of the rule, but which are not used
    ** inside the C code.
    */
            case 1:
            case 2:
            case 3:
            case 4:
            case 5:
            case 6:
            case 7:
            case 8:
            case 9:
            case 10:
            case 11:
            case 12:
            case 13:
            case 14:
            case 15:
            case 16:
            case 17:
            case 18:
            case 19:
            case 20:
            case 21:
            case 22:
            case 23:
            case 24:
            case 25:
            case 26:
            case 27:
            case 28:
            case 29:
            case 30:
            case 31:
            case 32:
            case 33:
            case 34:
            case 35:
            case 36:
            case 37:
            case 38:
            case 39:
            case 40:
            case 41:
            case 42:
            case 43:
            case 44:
            case 45:
            case 46:
            case 47:
            case 48:
            case 49:
            case 50:
            case 51:
            case 52:
            case 53:
            case 54:
            case 55:
            case 56:
            case 57:
            case 58:
            case 59:
            case 60:
            case 61:
            case 62:
            case 63:
            case 64:
            case 65:
            case 66:
            case 67:
            case 68:
            case 69:
            case 70:
            case 71:
            case 72:
            case 73:
            case 74:
            case 75:
            case 76:
            case 77:
            case 78:
            case 79:
            case 80:
            case 81:
            case 82:
            case 83:
            case 84:
            case 85:
            case 86:
            case 87:
            case 88:
                if (isset($yypminor->yy0)) {
                    if (($yypminor->yy0)->free_flag) {
                        unset(($yypminor->yy0)->token);
                    }
                    unset($yypminor->yy0);
                }
                break;
            case 91:
            case 92:
            case 93:
            case 94:
            case 95:
            case 96:
            case 98:
            case 104:
            case 105:
            case 107:
            case 108:
            case 109:
            case 110:
            case 111:
            case 112:
            case 113:
            case 114:
            case 118:
            case 119:
            case 121:
            case 122:
            case 124:
            case 125:
            case 126:
            case 128:
            case 129:
            case 130:
            case 131:
            case 132:
            case 133:
            case 134:
            case 136:
            case 139:
            case 142:
                unset($yypminor->yy202);
                break;
            case 97:
            case 99:
            case 100:
            case 101:
            case 102:
            case 103:
            case 115:
            case 116:
            case 123:
            case 140:
            case 141:
                unset($yypminor->yy202);
                break;
            default:
                break;   /* If no destructor action specified: do nothing */
        }
    }

    private function yy_find_reduce_action(
        $stateno,              /* Current state number */
        $iLookAhead     /* The look-ahead token */
    )
    {
        if (
            $stateno > self::YY_REDUCE_MAX ||
            ($i = self::$yy_reduce_ofst[$stateno]) == self::YY_REDUCE_USE_DFLT
        ) {
            return self::$yy_default[$stateno];
        }
        if ($iLookAhead == self::YYNOCODE) {
            return $this->YY_NO_ACTION;
        }
        $i += $iLookAhead;
        if ($i < 0 || $i >= count(self::$yy_action) || self::$yy_lookahead[$i] != $iLookAhead) {
            return self::$yy_default[$stateno];
        }

        return self::$yy_action[$i];
    }

    private function yy_find_shift_action($iLookAhead)
    {
        $stateno = $this->yystack[$this->yyidx]->stateno;

        if ($stateno > self::YY_SHIFT_MAX ||
            ($i = self::$yy_shift_ofst[$stateno]) == self::YY_SHIFT_USE_DFLT) {
            return self::$yy_default[$stateno];
        }
        if ($iLookAhead == self::YYNOCODE) {
            return $this->YY_NO_ACTION;
        }
        $i += $iLookAhead;
        if ($i < 0 || $i >= count(self::$yy_action) || self::$yy_lookahead[$i] != $iLookAhead) {
            if ($iLookAhead > 0) {
                if (
                    isset(self::$yyFallback[$iLookAhead]) &&
                    ($iFallback = self::$yyFallback[$iLookAhead]) != 0
                ) {
                    if ($this->yyTraceFILE) {
                        fwrite($this->yyTraceFILE, sprintf(
                            "%sFALLBACK %s => %s\n",
                            $this->yyTracePrompt,
                            self::$yyTokenName[$iLookAhead],
                            self::$yyTokenName[$iFallback]
                        ));
                    }
                    return $this->yy_find_shift_action($iFallback);
                }
                {
                    $j = $i - $iLookAhead + self::YYWILDCARD;
                    if ($j >= 0 && $j < count(self::$yy_action) && self::$yy_lookahead[$j] == self::YYWILDCARD) {
                        if ($this->yyTraceFILE) {
                            fwrite($this->yyTraceFILE, sprintf(
                                "%sWILDCARD %s => %s\n",
                                $this->yyTracePrompt,
                                self::$yyTokenName[$iLookAhead],
                                self::$yyTokenName[self::YYWILDCARD]
                            ));
                        }
                        return self::$yy_action[$j];
                    }
                }
            }
            return self::$yy_default[$stateno];
        }

        return self::$yy_action[$i];
    }

    private function yy_parse_failed(): void
    {
        if ($this->yyTraceFILE) {
            fwrite($this->yyTraceFILE, sprintf("%sFail!\n", $this->yyTracePrompt));
        }
        while ($this->yyidx >= 0) {
            $this->yy_pop_parser_stack();
        }
    }

    private function yy_pop_parser_stack()
    {
        if ($this->yyidx < 0) {
            return 0;
        }

        $yytos = $this->yystack[$this->yyidx];

        if ($this->yyTraceFILE) {
            fwrite($this->yyTraceFILE, sprintf(
                "%sPopping %s\n",
                $this->yyTracePrompt,
                self::$yyTokenName[$yytos->major]
            ));
        }

        $this->yy_destructor($yytos->major, $yytos->minor);
        unset($this->yystack[$this->yyidx]);
        $this->yyidx--;

        return $yytos->major;
    }

    /**
     * Perform a reduce action and the shift that must immediately
     * follow the reduce.
     *
     * @param int $yyruleno Number of the rule by which to reduce
     */
    private function yy_reduce(int $yyruleno): void
    {
        $yygotominor = [];        /* The LHS of the rule reduced */
        if ($this->yyTraceFILE && isset(self::$yyRuleName[$yyruleno])) {
            fwrite($this->yyTraceFILE, sprintf(
                "%sReduce [%s].\n",
                $this->yyTracePrompt,
                self::$yyRuleName[$yyruleno]
            ));
        }

        switch ($yyruleno) {
            /** Beginning here are the reduction cases.  A typical example:
             *
             *   case 0:
             *  #line <lineno> <grammarfile>
             *     { ... }           // User supplied code
             *  #line <lineno> <thisfile>
             *     break;
             */
            case 0:
                $yygotominor = $this->yystack[$this->yyidx + 0]->minor;
                $this->status->setAst($this->yystack[$this->yyidx + 0]->minor);
                break;
            case 1:
            case 2:
            case 3:
            case 4:
            case 18:
            case 19:
            case 22:
            case 23:
            case 43:
            case 50:
            case 52:
            case 65:
            case 67:
            case 73:
            case 80:
            case 81:
            case 141:
            case 145:
            case 150:
            case 158:
                $yygotominor = $this->yystack[$this->yyidx + 0]->minor;
                break;
            case 5:
                phql_ret_select_statement(
                    $yygotominor,
                    $this->yystack[$this->yyidx + -6]->minor,
                    $this->yystack[$this->yyidx + -5]->minor,
                    $this->yystack[$this->yyidx + -2]->minor,
                    $this->yystack[$this->yyidx + -4]->minor,
                    $this->yystack[$this->yyidx + -3]->minor,
                    $this->yystack[$this->yyidx + -1]->minor,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                break;
            case 6:
                {
                    phql_ret_select_clause(
                        $yygotominor,
                        $this->yystack[$this->yyidx + -4]->minor,
                        $this->yystack[$this->yyidx + -3]->minor,
                        $this->yystack[$this->yyidx + -1]->minor,
                        $this->yystack[$this->yyidx + 0]->minor
                    );
                    $this->yy_destructor(36, $this->yystack[$this->yyidx + -5]->minor);
                    $this->yy_destructor(37, $this->yystack[$this->yyidx + -2]->minor);
                }
                break;
            case 7:
                $yygotominor = 1;
                $this->yy_destructor(38, $this->yystack[$this->yyidx + 0]->minor);
                break;
            case 8:
                $yygotominor = 0;
                $this->yy_destructor(39, $this->yystack[$this->yyidx + 0]->minor);
                break;
            case 9:
                // distinct_all ::= (no DISTINCT or ALL keyword) - no distinct
                $yygotominor = null;
                break;
            case 144:
                // distinct_or_null ::= (no DISTINCT keyword in function call)
                $yygotominor = null;
                break;
            case 69:
            case 71:
            case 78:
            case 83:
            case 85:
            case 89:
            case 91:
            case 146:
                // Empty optional clause (where, limit, order, group, having, for_update, etc.)
                // Matches cphalcon ZVAL_UNDEF — must be null so phql_ret_*_statement skips adding the key
                $yygotominor = null;
                break;
            case 20:
                $yygotominor = [];
                break;
            case 27:
                // join_associated_name ::= (empty) - no alias
                // Matches cphalcon ZVAL_UNDEF so phql_ret_join_item skips the key
                $yygotominor = null;
                break;
            case 38:
                $yygotominor = null;
                break;
            case 10:
            case 17:
            case 41:
            case 44:
            case 49:
            case 64:
            case 72:
            case 79:
            case 147:
                phql_ret_zval_list(
                    $yygotominor,
                    $this->yystack[$this->yyidx + -2]->minor,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                $this->yy_destructor(35, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 11:
            case 42:
            case 45:
            case 138:
            case 148:
                phql_ret_zval_list($yygotominor, $this->yystack[$this->yyidx + 0]->minor);
                break;
            case 12:
            case 149:
                phql_ret_column_item($yygotominor, Opcode::STARALL->value);
                $this->yy_destructor(22, $this->yystack[$this->yyidx + 0]->minor);
                break;
            case 13:
                phql_ret_column_item(
                    $yygotominor,
                    Opcode::DOMAINALL->value,
                    null,
                    $this->yystack[$this->yyidx + -2]->minor,
                );
                $this->yy_destructor(41, $this->yystack[$this->yyidx + -1]->minor);
                $this->yy_destructor(22, $this->yystack[$this->yyidx + 0]->minor);
                break;
            case 14:
                phql_ret_column_item(
                    $yygotominor,
                    Opcode::EXPR->value,
                    $this->yystack[$this->yyidx + -2]->minor,
                    null,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                $this->yy_destructor(42, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 15:
                phql_ret_column_item(
                    $yygotominor,
                    Opcode::EXPR->value,
                    $this->yystack[$this->yyidx + -1]->minor,
                    null,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                break;
            case 16:
                phql_ret_column_item(
                    $yygotominor,
                    Opcode::EXPR->value,
                    $this->yystack[$this->yyidx + 0]->minor,
                );
                break;
            case 21:
            case 137:
                phql_ret_zval_list(
                    $yygotominor,
                    $this->yystack[$this->yyidx + -1]->minor,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                break;
            case 24:
                phql_ret_join_item(
                    $yygotominor,
                    $this->yystack[$this->yyidx + -3]->minor,
                    $this->yystack[$this->yyidx + -2]->minor,
                    $this->yystack[$this->yyidx + -1]->minor,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                break;
            case 25:
                phql_ret_qualified_name($yygotominor, null, null, $this->yystack[$this->yyidx + 0]->minor);
                $this->yy_destructor(42, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 26:
            case 46:
            case 66:
            case 170:
                phql_ret_qualified_name($yygotominor, null, null, $this->yystack[$this->yyidx + 0]->minor);
                break;
            case 28:
                $yygotominor = Opcode::INNERJOIN->value;
                $this->yy_destructor(43, $this->yystack[$this->yyidx + -1]->minor);
                $this->yy_destructor(44, $this->yystack[$this->yyidx + 0]->minor);
                break;
            case 29:
                $yygotominor = Opcode::CROSSJOIN->value;
                $this->yy_destructor(45, $this->yystack[$this->yyidx + -1]->minor);
                $this->yy_destructor(44, $this->yystack[$this->yyidx + 0]->minor);
                break;
            case 30:
                $yygotominor = Opcode::LEFTJOIN->value;
                $this->yy_destructor(46, $this->yystack[$this->yyidx + -2]->minor);
                $this->yy_destructor(47, $this->yystack[$this->yyidx + -1]->minor);
                $this->yy_destructor(44, $this->yystack[$this->yyidx + 0]->minor);
                break;
            case 31:
                $yygotominor = Opcode::LEFTJOIN->value;
                $this->yy_destructor(46, $this->yystack[$this->yyidx + -1]->minor);
                $this->yy_destructor(44, $this->yystack[$this->yyidx + 0]->minor);
                break;
            case 32:
                $yygotominor = Opcode::RIGHTJOIN->value;
                $this->yy_destructor(48, $this->yystack[$this->yyidx + -2]->minor);
                $this->yy_destructor(47, $this->yystack[$this->yyidx + -1]->minor);
                $this->yy_destructor(44, $this->yystack[$this->yyidx + 0]->minor);
                break;
            case 33:
                $yygotominor = Opcode::RIGHTJOIN->value;
                $this->yy_destructor(48, $this->yystack[$this->yyidx + -1]->minor);
                $this->yy_destructor(44, $this->yystack[$this->yyidx + 0]->minor);
                break;
            case 34:
                $yygotominor = Opcode::FULLJOIN->value;
                $this->yy_destructor(49, $this->yystack[$this->yyidx + -2]->minor);
                $this->yy_destructor(47, $this->yystack[$this->yyidx + -1]->minor);
                $this->yy_destructor(44, $this->yystack[$this->yyidx + 0]->minor);
                break;
            case 35:
                $yygotominor = Opcode::FULLJOIN->value;
                $this->yy_destructor(49, $this->yystack[$this->yyidx + -1]->minor);
                $this->yy_destructor(44, $this->yystack[$this->yyidx + 0]->minor);
                break;
            case 36:
                $yygotominor = Opcode::INNERJOIN->value;
                $this->yy_destructor(44, $this->yystack[$this->yyidx + 0]->minor);
                break;
            case 37:
                $yygotominor = $this->yystack[$this->yyidx + 0]->minor;
                $this->yy_destructor(50, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 39:
                phql_ret_insert_statement(
                    $yygotominor,
                    $this->yystack[$this->yyidx + -4]->minor,
                    null,
                    $this->yystack[$this->yyidx + -1]->minor
                );
                $this->yy_destructor(51, $this->yystack[$this->yyidx + -6]->minor);
                $this->yy_destructor(52, $this->yystack[$this->yyidx + -5]->minor);
                $this->yy_destructor(53, $this->yystack[$this->yyidx + -3]->minor);
                $this->yy_destructor(54, $this->yystack[$this->yyidx + -2]->minor);
                $this->yy_destructor(55, $this->yystack[$this->yyidx + 0]->minor);
                break;
            case 40:
                phql_ret_insert_statement(
                    $yygotominor,
                    $this->yystack[$this->yyidx + -7]->minor,
                    $this->yystack[$this->yyidx + -5]->minor,
                    $this->yystack[$this->yyidx + -1]->minor
                );
                $this->yy_destructor(51, $this->yystack[$this->yyidx + -9]->minor);
                $this->yy_destructor(52, $this->yystack[$this->yyidx + -8]->minor);
                $this->yy_destructor(54, $this->yystack[$this->yyidx + -6]->minor);
                $this->yy_destructor(55, $this->yystack[$this->yyidx + -4]->minor);
                $this->yy_destructor(53, $this->yystack[$this->yyidx + -3]->minor);
                $this->yy_destructor(54, $this->yystack[$this->yyidx + -2]->minor);
                $this->yy_destructor(55, $this->yystack[$this->yyidx + 0]->minor);
                break;
            case 47:
                phql_ret_update_statement(
                    $yygotominor,
                    $this->yystack[$this->yyidx + -2]->minor,
                    $this->yystack[$this->yyidx + -1]->minor,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                break;
            case 48:
                phql_ret_update_clause(
                    $yygotominor,
                    $this->yystack[$this->yyidx + -3]->minor,
                    $this->yystack[$this->yyidx + -2]->minor,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                $this->yy_destructor(56, $this->yystack[$this->yyidx + -4]->minor);
                $this->yy_destructor(57, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 51:
                phql_ret_update_item(
                    $yygotominor,
                    $this->yystack[$this->yyidx + -2]->minor,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                $this->yy_destructor(4, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 53:
                phql_ret_delete_statement(
                    $yygotominor,
                    $this->yystack[$this->yyidx + -2]->minor,
                    $this->yystack[$this->yyidx + -1]->minor,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                break;
            case 54:
                phql_ret_delete_clause($yygotominor, $this->yystack[$this->yyidx + 0]->minor);
                $this->yy_destructor(58, $this->yystack[$this->yyidx + -2]->minor);
                $this->yy_destructor(37, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 55:
                phql_ret_assoc_name(
                    $yygotominor,
                    $this->yystack[$this->yyidx + -2]->minor,
                    $this->yystack[$this->yyidx + 0]->minor,
                );
                $this->yy_destructor(42, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 56:
                phql_ret_assoc_name(
                    $yygotominor,
                    $this->yystack[$this->yyidx + -1]->minor,
                    $this->yystack[$this->yyidx + 0]->minor,
                );
                break;
            case 57:
                phql_ret_assoc_name($yygotominor, $this->yystack[$this->yyidx + 0]->minor);
                break;
            case 58:
                phql_ret_assoc_name(
                    $yygotominor,
                    $this->yystack[$this->yyidx + -4]->minor,
                    $this->yystack[$this->yyidx + -2]->minor,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                $this->yy_destructor(42, $this->yystack[$this->yyidx + -3]->minor);
                $this->yy_destructor(59, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 59:
                phql_ret_assoc_name(
                    $yygotominor,
                    $this->yystack[$this->yyidx + -6]->minor,
                    $this->yystack[$this->yyidx + -4]->minor,
                    $this->yystack[$this->yyidx + -1]->minor
                );
                $this->yy_destructor(42, $this->yystack[$this->yyidx + -5]->minor);
                $this->yy_destructor(59, $this->yystack[$this->yyidx + -3]->minor);
                $this->yy_destructor(54, $this->yystack[$this->yyidx + -2]->minor);
                $this->yy_destructor(55, $this->yystack[$this->yyidx + 0]->minor);
                break;
            case 60:
                phql_ret_assoc_name(
                    $yygotominor,
                    $this->yystack[$this->yyidx + -5]->minor,
                    $this->yystack[$this->yyidx + -4]->minor,
                    $this->yystack[$this->yyidx + -1]->minor
                );
                $this->yy_destructor(59, $this->yystack[$this->yyidx + -3]->minor);
                $this->yy_destructor(54, $this->yystack[$this->yyidx + -2]->minor);
                $this->yy_destructor(55, $this->yystack[$this->yyidx + 0]->minor);
                break;
            case 61:
                phql_ret_assoc_name(
                    $yygotominor,
                    $this->yystack[$this->yyidx + -3]->minor,
                    $this->yystack[$this->yyidx + -2]->minor,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                $this->yy_destructor(59, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 62:
                phql_ret_assoc_name(
                    $yygotominor,
                    $this->yystack[$this->yyidx + -4]->minor,
                    null,
                    $this->yystack[$this->yyidx + -1]->minor
                );
                $this->yy_destructor(59, $this->yystack[$this->yyidx + -3]->minor);
                $this->yy_destructor(54, $this->yystack[$this->yyidx + -2]->minor);
                $this->yy_destructor(55, $this->yystack[$this->yyidx + 0]->minor);
                break;
            case 63:
                phql_ret_assoc_name(
                    $yygotominor,
                    $this->yystack[$this->yyidx + -2]->minor,
                    null,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                $this->yy_destructor(59, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 68:
                $yygotominor = $this->yystack[$this->yyidx + 0]->minor;
                $this->yy_destructor(60, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 70:
                $yygotominor = $this->yystack[$this->yyidx + 0]->minor;
                $this->yy_destructor(61, $this->yystack[$this->yyidx + -2]->minor);
                $this->yy_destructor(62, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 74:
                phql_ret_order_item($yygotominor, $this->yystack[$this->yyidx + 0]->minor);
                break;
            case 75:
                phql_ret_order_item($yygotominor, $this->yystack[$this->yyidx + -1]->minor, Opcode::ASC->value);
                $this->yy_destructor(63, $this->yystack[$this->yyidx + 0]->minor);
                break;
            case 76:
                phql_ret_order_item($yygotominor, $this->yystack[$this->yyidx + -1]->minor, Opcode::DESC->value);
                $this->yy_destructor(64, $this->yystack[$this->yyidx + 0]->minor);
                break;
            case 77:
                $yygotominor = $this->yystack[$this->yyidx + 0]->minor;
                $this->yy_destructor(65, $this->yystack[$this->yyidx + -2]->minor);
                $this->yy_destructor(62, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 82:
                $yygotominor = $this->yystack[$this->yyidx + 0]->minor;
                $this->yy_destructor(66, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 84:
                $yygotominor = true;
                $this->yy_destructor(67, $this->yystack[$this->yyidx + -1]->minor);
                $this->yy_destructor(56, $this->yystack[$this->yyidx + 0]->minor);
                break;
            case 86:
            case 90:
                phql_ret_limit_clause($yygotominor, $this->yystack[$this->yyidx + 0]->minor, null);
                $this->yy_destructor(68, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 87:
                phql_ret_limit_clause(
                    $yygotominor,
                    $this->yystack[$this->yyidx + 0]->minor,
                    $this->yystack[$this->yyidx + -2]->minor
                );
                $this->yy_destructor(68, $this->yystack[$this->yyidx + -3]->minor);
                $this->yy_destructor(35, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 88:
                phql_ret_limit_clause(
                    $yygotominor,
                    $this->yystack[$this->yyidx + -2]->minor,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                $this->yy_destructor(68, $this->yystack[$this->yyidx + -3]->minor);
                $this->yy_destructor(69, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 92:
            case 159:
                phql_ret_literal_zval(
                    $yygotominor,
                    Opcode::INTEGER->value,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                break;
            case 93:
            case 160:
                phql_ret_literal_zval(
                    $yygotominor,
                    Opcode::HINTEGER->value,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                break;
            case 94:
            case 166:
                phql_ret_placeholder_zval(
                    $yygotominor,
                    Opcode::NPLACEHOLDER->value,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                break;
            case 95:
            case 167:
                phql_ret_placeholder_zval(
                    $yygotominor,
                    Opcode::SPLACEHOLDER->value,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                break;
            case 96:
            case 168:
                phql_ret_placeholder_zval(
                    $yygotominor,
                    Opcode::BPLACEHOLDER->value,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                break;
            case 97:
                phql_ret_expr($yygotominor, Opcode::MINUS->value, null, $this->yystack[$this->yyidx + 0]->minor);
                $this->yy_destructor(25, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 98:
                phql_ret_expr(
                    $yygotominor,
                    Opcode::SUB->value,
                    $this->yystack[$this->yyidx + -2]->minor,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                $this->yy_destructor(25, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 99:
                phql_ret_expr(
                    $yygotominor,
                    Opcode::ADD->value,
                    $this->yystack[$this->yyidx + -2]->minor,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                $this->yy_destructor(24, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 100:
                phql_ret_expr(
                    $yygotominor,
                    Opcode::MUL->value,
                    $this->yystack[$this->yyidx + -2]->minor,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                $this->yy_destructor(22, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 101:
                phql_ret_expr(
                    $yygotominor,
                    Opcode::DIV->value,
                    $this->yystack[$this->yyidx + -2]->minor,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                $this->yy_destructor(21, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 102:
                phql_ret_expr(
                    $yygotominor,
                    Opcode::MOD->value,
                    $this->yystack[$this->yyidx + -2]->minor,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                $this->yy_destructor(23, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 103:
                phql_ret_expr(
                    $yygotominor,
                    Opcode::AND->value,
                    $this->yystack[$this->yyidx + -2]->minor,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                $this->yy_destructor(14, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 104:
                phql_ret_expr(
                    $yygotominor,
                    Opcode::OR->value,
                    $this->yystack[$this->yyidx + -2]->minor,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                $this->yy_destructor(15, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 105:
                phql_ret_expr(
                    $yygotominor,
                    Opcode::BITWISE_AND->value,
                    $this->yystack[$this->yyidx + -2]->minor,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                $this->yy_destructor(18, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 106:
                phql_ret_expr(
                    $yygotominor,
                    Opcode::BITWISE_OR->value,
                    $this->yystack[$this->yyidx + -2]->minor,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                $this->yy_destructor(19, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 107:
                phql_ret_expr(
                    $yygotominor,
                    Opcode::BITWISE_XOR->value,
                    $this->yystack[$this->yyidx + -2]->minor,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                $this->yy_destructor(20, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 108:
                phql_ret_expr(
                    $yygotominor,
                    Opcode::OP_MATCHES->value,
                    $this->yystack[$this->yyidx + -2]->minor,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                $this->yy_destructor(10, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 109:
                phql_ret_expr(
                    $yygotominor,
                    Opcode::OP_CONTAINS->value,
                    $this->yystack[$this->yyidx + -2]->minor,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                $this->yy_destructor(11, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 110:
                phql_ret_expr(
                    $yygotominor,
                    Opcode::OP_CONTAINED->value,
                    $this->yystack[$this->yyidx + -2]->minor,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                $this->yy_destructor(12, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 111:
                phql_ret_expr(
                    $yygotominor,
                    Opcode::OP_OVERLAPS->value,
                    $this->yystack[$this->yyidx + -2]->minor,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                $this->yy_destructor(13, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 112:
                phql_ret_expr(
                    $yygotominor,
                    Opcode::OP_CONCAT->value,
                    $this->yystack[$this->yyidx + -2]->minor,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                $this->yy_destructor(26, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 113:
                phql_ret_expr(
                    $yygotominor,
                    Opcode::OP_JSON_GET->value,
                    $this->yystack[$this->yyidx + -2]->minor,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                $this->yy_destructor(31, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 114:
                phql_ret_expr(
                    $yygotominor,
                    Opcode::OP_JSON_GET_TEXT->value,
                    $this->yystack[$this->yyidx + -2]->minor,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                $this->yy_destructor(32, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 115:
                phql_ret_expr(
                    $yygotominor,
                    Opcode::OP_JSON_PATH->value,
                    $this->yystack[$this->yyidx + -2]->minor,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                $this->yy_destructor(33, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 116:
                phql_ret_expr(
                    $yygotominor,
                    Opcode::OP_JSON_PATH_TEXT->value,
                    $this->yystack[$this->yyidx + -2]->minor,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                $this->yy_destructor(34, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 117:
                phql_ret_expr(
                    $yygotominor,
                    Opcode::EQUALS->value,
                    $this->yystack[$this->yyidx + -2]->minor,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                $this->yy_destructor(4, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 118:
                phql_ret_expr(
                    $yygotominor,
                    Opcode::NOTEQUALS->value,
                    $this->yystack[$this->yyidx + -2]->minor,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                $this->yy_destructor(5, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 119:
                phql_ret_expr(
                    $yygotominor,
                    Opcode::LESS->value,
                    $this->yystack[$this->yyidx + -2]->minor,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                $this->yy_destructor(6, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 120:
                phql_ret_expr(
                    $yygotominor,
                    Opcode::GREATER->value,
                    $this->yystack[$this->yyidx + -2]->minor,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                $this->yy_destructor(7, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 121:
                phql_ret_expr(
                    $yygotominor,
                    Opcode::GREATEREQUAL->value,
                    $this->yystack[$this->yyidx + -2]->minor,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                $this->yy_destructor(8, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 122:
                phql_ret_expr(
                    $yygotominor,
                    Opcode::LESSEQUAL->value,
                    $this->yystack[$this->yyidx + -2]->minor,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                $this->yy_destructor(9, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 123:
                phql_ret_expr(
                    $yygotominor,
                    Opcode::LIKE->value,
                    $this->yystack[$this->yyidx + -2]->minor,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                $this->yy_destructor(16, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 124:
                phql_ret_expr(
                    $yygotominor,
                    Opcode::NLIKE->value,
                    $this->yystack[$this->yyidx + -3]->minor,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                $this->yy_destructor(29, $this->yystack[$this->yyidx + -2]->minor);
                $this->yy_destructor(16, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 125:
                phql_ret_expr(
                    $yygotominor,
                    Opcode::ILIKE->value,
                    $this->yystack[$this->yyidx + -2]->minor,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                $this->yy_destructor(17, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 126:
                phql_ret_expr(
                    $yygotominor,
                    Opcode::NILIKE->value,
                    $this->yystack[$this->yyidx + -3]->minor,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                $this->yy_destructor(29, $this->yystack[$this->yyidx + -2]->minor);
                $this->yy_destructor(17, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 127:
            case 130:
                phql_ret_expr(
                    $yygotominor,
                    Opcode::IN->value,
                    $this->yystack[$this->yyidx + -4]->minor,
                    $this->yystack[$this->yyidx + -1]->minor
                );
                $this->yy_destructor(28, $this->yystack[$this->yyidx + -3]->minor);
                $this->yy_destructor(54, $this->yystack[$this->yyidx + -2]->minor);
                $this->yy_destructor(55, $this->yystack[$this->yyidx + 0]->minor);
                break;
            case 128:
            case 131:
                phql_ret_expr(
                    $yygotominor,
                    Opcode::NOTIN->value,
                    $this->yystack[$this->yyidx + -5]->minor,
                    $this->yystack[$this->yyidx + -1]->minor
                );
                $this->yy_destructor(29, $this->yystack[$this->yyidx + -4]->minor);
                $this->yy_destructor(28, $this->yystack[$this->yyidx + -3]->minor);
                $this->yy_destructor(54, $this->yystack[$this->yyidx + -2]->minor);
                $this->yy_destructor(55, $this->yystack[$this->yyidx + 0]->minor);
                break;
            case 129:
                phql_ret_expr(
                    $yygotominor,
                    Opcode::SUBQUERY->value,
                    $this->yystack[$this->yyidx + -1]->minor,
                    null
                );
                $this->yy_destructor(54, $this->yystack[$this->yyidx + -2]->minor);
                $this->yy_destructor(55, $this->yystack[$this->yyidx + 0]->minor);
                break;
            case 132:
                phql_ret_expr($yygotominor, Opcode::EXISTS->value, null, $this->yystack[$this->yyidx + -1]->minor);
                $this->yy_destructor(75, $this->yystack[$this->yyidx + -3]->minor);
                $this->yy_destructor(54, $this->yystack[$this->yyidx + -2]->minor);
                $this->yy_destructor(55, $this->yystack[$this->yyidx + 0]->minor);
                break;
            case 133:
                phql_ret_expr(
                    $yygotominor,
                    Opcode::AGAINST->value,
                    $this->yystack[$this->yyidx + -2]->minor,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                $this->yy_destructor(1, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 134:
                $qualified = [];
                phql_ret_raw_qualified_name($qualified, $this->yystack[$this->yyidx + -1]->minor);
                phql_ret_expr(
                    $yygotominor,
                    Opcode::CAST->value,
                    $this->yystack[$this->yyidx + -3]->minor,
                    $qualified
                );
                $this->yy_destructor(76, $this->yystack[$this->yyidx + -5]->minor);
                $this->yy_destructor(54, $this->yystack[$this->yyidx + -4]->minor);
                $this->yy_destructor(42, $this->yystack[$this->yyidx + -2]->minor);
                $this->yy_destructor(55, $this->yystack[$this->yyidx + 0]->minor);
                break;
            case 135:
                $qualified = [];
                phql_ret_raw_qualified_name($qualified, $this->yystack[$this->yyidx + -1]->minor, null);
                phql_ret_expr(
                    $yygotominor,
                    Opcode::CONVERT->value,
                    $this->yystack[$this->yyidx + -3]->minor,
                    $qualified
                );
                $this->yy_destructor(77, $this->yystack[$this->yyidx + -5]->minor);
                $this->yy_destructor(54, $this->yystack[$this->yyidx + -4]->minor);
                $this->yy_destructor(78, $this->yystack[$this->yyidx + -2]->minor);
                $this->yy_destructor(55, $this->yystack[$this->yyidx + 0]->minor);
                break;
            case 136:
                phql_ret_expr(
                    $yygotominor,
                    Opcode::CASE->value,
                    $this->yystack[$this->yyidx + -2]->minor,
                    $this->yystack[$this->yyidx + -1]->minor
                );
                $this->yy_destructor(79, $this->yystack[$this->yyidx + -3]->minor);
                $this->yy_destructor(80, $this->yystack[$this->yyidx + 0]->minor);
                break;
            case 139:
                phql_ret_expr(
                    $yygotominor,
                    Opcode::WHEN->value,
                    $this->yystack[$this->yyidx + -2]->minor,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                $this->yy_destructor(81, $this->yystack[$this->yyidx + -3]->minor);
                $this->yy_destructor(82, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 140:
                phql_ret_expr($yygotominor, Opcode::ELSE->value, $this->yystack[$this->yyidx + 0]->minor, null);
                $this->yy_destructor(83, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 142:
                phql_ret_func_call(
                    $yygotominor,
                    $this->yystack[$this->yyidx + -4]->minor,
                    $this->yystack[$this->yyidx + -1]->minor,
                    $this->yystack[$this->yyidx + -2]->minor
                );
                $this->yy_destructor(54, $this->yystack[$this->yyidx + -3]->minor);
                $this->yy_destructor(55, $this->yystack[$this->yyidx + 0]->minor);
                break;
            case 143:
                // distinct_or_null ::= DISTINCT (DISTINCT keyword present in function call)
                $yygotominor = true;
                $this->yy_destructor(38, $this->yystack[$this->yyidx + 0]->minor);
                break;
            case 151:
                phql_ret_expr($yygotominor, Opcode::ISNULL->value, $this->yystack[$this->yyidx + -2]->minor, null);
                $this->yy_destructor(27, $this->yystack[$this->yyidx + -1]->minor);
                $this->yy_destructor(84, $this->yystack[$this->yyidx + 0]->minor);
                break;
            case 152:
                phql_ret_expr(
                    $yygotominor,
                    Opcode::ISNOTNULL->value,
                    $this->yystack[$this->yyidx + -3]->minor,
                    null
                );
                $this->yy_destructor(27, $this->yystack[$this->yyidx + -2]->minor);
                $this->yy_destructor(29, $this->yystack[$this->yyidx + -1]->minor);
                $this->yy_destructor(84, $this->yystack[$this->yyidx + 0]->minor);
                break;
            case 153:
                phql_ret_expr(
                    $yygotominor,
                    Opcode::BETWEEN->value,
                    $this->yystack[$this->yyidx + -2]->minor,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                $this->yy_destructor(2, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 154:
                phql_ret_expr(
                    $yygotominor,
                    Opcode::BETWEEN_NOT->value,
                    $this->yystack[$this->yyidx + -2]->minor,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                $this->yy_destructor(3, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 155:
                phql_ret_expr($yygotominor, Opcode::NOT->value, null, $this->yystack[$this->yyidx + 0]->minor);
                $this->yy_destructor(29, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 156:
                phql_ret_expr(
                    $yygotominor,
                    Opcode::BITWISE_NOT->value,
                    null,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                $this->yy_destructor(30, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 157:
                phql_ret_expr(
                    $yygotominor,
                    Opcode::ENCLOSED->value,
                    $this->yystack[$this->yyidx + -1]->minor,
                    null
                );
                $this->yy_destructor(54, $this->yystack[$this->yyidx + -2]->minor);
                $this->yy_destructor(55, $this->yystack[$this->yyidx + 0]->minor);
                break;
            case 161:
                phql_ret_literal_zval($yygotominor, Opcode::STRING->value, $this->yystack[$this->yyidx + 0]->minor);
                break;
            case 162:
                phql_ret_literal_zval($yygotominor, Opcode::DOUBLE->value, $this->yystack[$this->yyidx + 0]->minor);
                break;
            case 163:
                phql_ret_literal_zval($yygotominor, Opcode::NULL->value);
                $this->yy_destructor(84, $this->yystack[$this->yyidx + 0]->minor);
                break;
            case 164:
#line 920 "c/parser.php.lemon"
                {
                    phql_ret_literal_zval($yygotominor, Opcode::TRUE->value, null);
                    $this->yy_destructor(87, $this->yystack[$this->yyidx + 0]->minor);
                }
#line 2086 "c/parser.php.php"
                break;
            case 165:
#line 924 "c/parser.php.lemon"
                {
                    phql_ret_literal_zval($yygotominor, Opcode::FALSE->value, null);
                    $this->yy_destructor(88, $this->yystack[$this->yyidx + 0]->minor);
                }
#line 2094 "c/parser.php.php"
                break;
            case 169:
#line 947 "c/parser.php.lemon"
                {
                    phql_ret_qualified_name(
                        $yygotominor,
                        null,
                        $this->yystack[$this->yyidx + -2]->minor,
                        $this->yystack[$this->yyidx + 0]->minor
                    );
                    $this->yy_destructor(41, $this->yystack[$this->yyidx + -1]->minor);
                }
#line 2102 "c/parser.php.php"
                break;
        }
        $yygoto = self::$yyRuleInfo[2 * $yyruleno];
        $yysize = self::$yyRuleInfo[(2 * $yyruleno) + 1];

        $state_for_reduce = $this->yystack[$this->yyidx - $yysize]->stateno;

        $this->yyidx -= $yysize;
        $yyact = $this->yy_find_reduce_action($state_for_reduce, $yygoto);
        if ($yyact < self::YYNSTATE) {
            $this->yy_shift($yyact, $yygoto, $yygotominor);
        } else {
            if ($yyact == self::YYNSTATE + self::YYNRULE + 1) {
                $this->yy_accept();
            }
        }

        $this->output = $yygotominor;
    }

    /*
** The following is executed when the parser accepts
*/

    private function yy_shift(
        $yyNewState,               /* The new state to shift in */
        $yyMajor,                  /* The major token to shift in */
        $yypMinor         /* Pointer ot the minor token to shift in */
    )
    {
        $this->yyidx++;
        if (isset($this->yystack[$this->yyidx])) {
            $yytos = $this->yystack[$this->yyidx];
        } else {
            $yytos = new phql_yyStackEntry;
            $this->yystack[$this->yyidx] = $yytos;
        }
        $yytos->stateno = $yyNewState;
        $yytos->major = $yyMajor;
        $yytos->minor = $yypMinor;
        if ($this->yyTraceFILE) {
            fwrite($this->yyTraceFILE, sprintf("%sShift %d\n", $this->yyTracePrompt, $yyNewState));
            fwrite($this->yyTraceFILE, sprintf("%sStack:", $this->yyTracePrompt));
            for ($i = 1; $i <= $this->yyidx; $i++) {
                $ent = $this->yystack[$i];
                fwrite($this->yyTraceFILE, sprintf(" %s", self::$yyTokenName[$ent->major]));
            }
            fwrite($this->yyTraceFILE, "\n");
        }
    }

    /* The main parser program.
** The first argument is a pointer to a structure obtained from
** "phql_Alloc" which describes the current state of the parser.
** The second argument is the major token number.  The third is
** the minor token.  The fourth optional argument is whatever the
** user wants (and specified in the grammar) and is available for
** use by the action routines.
**
** Inputs:
** <ul>
** <li> A pointer to the parser (an opaque structure.)
** <li> The major token number.
** <li> The minor token number.
** <li> An option argument of a grammar-specified type.
** </ul>
**
** Outputs:
** None.
*/

    private function yy_syntax_error()
    {
        $near_length = $this->status->getState()->getStartLength();
        $token_name = null;
        $token_found = 0;
        $tokens = Tokens::$names;
        $active_token = $this->status->getState()->getActiveToken();

        if ($this->status->getState()->getStartLength()) {
            if ($active_token) {
                if (in_array($active_token->value, $tokens)) {
                    $token_name = array_search($active_token->value, $tokens);
                }
            }

            if (!$token_name) {
                $token_name = "UNKNOWN";
            }

            if ($near_length > 0) {
                if ($this->status->getToken()->value) {
                    $this->status->setSyntaxError(sprintf(
                        "Syntax error, unexpected token %s(%s), near to '%s', when parsing: %s",
                        $token_name,
                        $this->status->getToken()->value,
                        $this->status->getState()->getStart(),
                        $this->status->getState()->getRawBuffer(),
                    ));
                } else {
                    $this->status->setSyntaxError(sprintf(
                        "Syntax error, unexpected token %s, near to '%s', when parsing: %s",
                        $token_name,
                        $this->status->getState()->getStart(),
                        $this->status->getState()->getRawBuffer(),
                    ));
                }
            } else {
                if ($active_token !== Opcode::IGNORE) {
                    if ($this->status->getToken()->value) {
                        $this->status->setSyntaxError(sprintf(
                            "Syntax error, unexpected token %s(%s), at the end of query, when parsing: %s",
                            $token_name,
                            $this->status->getToken()->value,
                            $this->status->getState()->getRawBuffer(),
                        ));
                    } else {
                        $this->status->setSyntaxError(sprintf(
                            "Syntax error, unexpected token %s, at the end of query, when parsing: %s",
                            $token_name,
                            $this->status->getState()->getRawBuffer(),
                        ));
                    }
                } else {
                    $this->status->setSyntaxError("Syntax error, unexpected EOF, at the end of query");
                }
            }

            if (!$token_found && $token_name) {
                unset($token_name);
            }
        } else {
            $this->status->setSyntaxError("Syntax error, unexpected EOF");
        }

        $this->status->setStatus(Status::PHQL_PARSING_FAILED);
    }
}

/* The state of the parser is completely contained in an instance of
** the following structure */

class phql_yyStackEntry
{
    public int $major;       /* The state-number */
    /**
     * The user-supplied minor token value.
     * This is the value of the token.
     */
    public mixed $minor = null; /* The major token value.  This is the code
                     ** number for the token at this stack level */
    public int $stateno;
}

function phql_ret_insert_statement(&$ret, $Q, $F, $V): void
{
    $ret = [
        "type"          => Opcode::INSERT->value,
        "qualifiedName" => $Q,
    ];

    if ($F !== null) {
        $ret["fields"] = $F;
    }

    $ret["values"] = $V;
}

function phql_ret_select_statement(&$ret, $S, $W, $O, $G, $H, $L, $F): void
{
    $ret = [
        "type" => Opcode::SELECT->value,
        "select" => $S,
    ];

    if (!empty($W)) {
        $ret["where"] = $W;
    }
    if (!empty($O)) {
        $ret["orderBy"] = $O;
    }
    if (!empty($G)) {
        $ret["groupBy"] = $G;
    }
    if (!empty($H)) {
        $ret["having"] = $H;
    }
    if (!empty($L)) {
        $ret["limit"] = $L;
    }
    if (!empty($F)) {
        $ret["forUpdate"] = $F;
    }
}

function phql_ret_select_clause(&$ret, $distinct, $columns, $tables, $join_list): void
{
    $ret = [];

    if ($distinct !== null) {
        $ret["distinct"] = $distinct;
    }

    $ret["columns"] = $columns;
    $ret["tables"] = $tables;

    if (!empty($join_list)) {
        $ret["joins"] = $join_list;
    }
}

function phql_ret_expr(&$ret, $type, $left, $right): void
{
    $ret = [
        "type" => $type,
    ];

    if ($left !== null) {
        $ret["left"] = $left;
    }
    if ($right !== null) {
        $ret["right"] = $right;
    }
}

function phql_ret_literal_zval(&$ret, int $type, ?Token $T = null): array
{
    $ret = ['type' => $type];
    if ($T !== null) {
        $ret['value'] = $T->value;
    }

    return $ret;
}

function phql_ret_zval_list(&$ret, $listLeft = null, $rightList = null): array
{
    $ret = [];

    if ($listLeft !== null) {
        if (is_array($listLeft) && array_key_exists(0, $listLeft)) {
            foreach ($listLeft as $item) {
                $ret[] = $item;   // copy-on-write under the hood
            }

        } else {
            $ret[] = $listLeft;
        }
    }

    if ($rightList !== null) {
        $ret[] = $rightList;
    }

    return $ret;
}

function phql_ret_column_item(
    array   &$ret,
    int     $type,
            $column = null,
    ?Token $identifierColumn = null,
    ?Token $alias = null
): array
{
    $ret = [
        'type' => $type,
    ];

    if ($column !== null) {
        $ret['column'] = $column;
    }

    if ($identifierColumn !== null) {
        $ret['column'] = $identifierColumn->value;
    }

    if ($alias !== null) {
        $ret['alias'] = $alias->value;
    }

    return $ret;
}

function phql_ret_join_item(
    array &$ret,
          $type,
          $qualified = null,
          $alias = null,
          $conditions = null
): void
{
    $ret = [
        'type' => $type,
    ];

    if ($qualified !== null) {
        $ret['qualified'] = $qualified;
    }

    if ($alias !== null) {
        $ret['alias'] = $alias;
    }

    if ($conditions !== null) {
        $ret['conditions'] = $conditions;
    }
}

function phql_ret_qualified_name(
    array   &$ret,
    ?Token  $nsAlias,
    ?Token $domain,
    Token  $name,
): void
{
    $ret = [
        'type' => Opcode::QUALIFIED->value,
    ];

    if ($nsAlias !== null) {
        $ret['ns-alias'] = $nsAlias->value;
    }

    /* if (B) phql_add_assoc_stringl(..., "domain", ...) */
    if ($domain !== null) {
        $ret['domain'] = $domain->value;
    }

    $ret['name'] = $name->value;
}

function phql_ret_update_statement(array &$ret, $update, $where = null, $limit = null): void
{
    $ret = [];
    $ret['type'] = Opcode::UPDATE->value;
    $ret['update'] = $update;

    if ($where !== null) {
        $ret['where'] = $where;
    }

    if ($limit !== null) {
        $ret['limit'] = $limit;
    }
}

function phql_ret_update_clause(array &$ret, $tables, $joins, $values): void
{
    $ret = [];
    $ret['tables'] = $tables;

    if (!empty($joins)) {
        $ret['joins'] = $joins;
    }

    $ret['values'] = $values;
}

function phql_ret_update_item(array &$ret, $column, $expr): void
{
    $ret = [];
    $ret['column'] = $column;
    $ret['expr'] = $expr;
}

function phql_ret_delete_statement(array &$ret, $delete, $where = null, $limit = null): void
{
    $ret = [];
    $ret['type'] = Opcode::DELETE->value;
    $ret['delete'] = $delete;

    if ($where !== null) {
        $ret['where'] = $where;
    }

    if ($limit !== null) {
        $ret['limit'] = $limit;
    }
}

function phql_ret_delete_clause(array &$ret, $tables): void
{
    $ret = [
        'tables' => $tables,
    ];
}

function phql_ret_assoc_name(array &$ret, $qualifiedName, $alias = null, $with = null): void
{
    $ret = [
        'qualifiedName' => $qualifiedName,
    ];

    if ($alias !== null) {
        $ret['alias'] = $alias instanceof Token ? $alias->value : $alias;
    }

    if ($with !== null) {
        $ret['with'] = $with;
    }
}

function phql_ret_order_item(array &$ret, $column, int $sort = 0): void
{
    $ret = [
        'column' => $column,
    ];
    if ($sort !== 0) {
        $ret['sort'] = $sort;
    }
}

function phql_ret_limit_clause(array &$ret, $limit, $offset = null): void
{
    $ret = [
        'number' => $limit,
    ];
    if ($offset !== null) {
        $ret['offset'] = $offset;
    }
}

function phql_ret_placeholder_zval(array &$ret, int $type, ?Token $value = null): void
{
    $ret = [];
    $ret['type'] = $type;
    $ret['value'] = $value->value ?? null;
}

function phql_ret_raw_qualified_name(array &$ret, Token $tokenA, ?Token $tokenB = null): void
{
    $ret = [];
    $ret['type'] = Opcode::RAW_QUALIFIED->value;

    if ($tokenB !== null) {
        /* Two-part qualified name: domain + name */
        $ret['domain'] = $tokenA->value;
        $ret['name']   = $tokenB->value;
    } else {
        /* Single-part name */
        $ret['name'] = $tokenA->value;
    }
}

function phql_ret_func_call(array  &$ret, $name, $arguments = null, $distinct = null): void
{
    $ret = [];
    $ret['type'] = Opcode::FCALL->value;
    $ret['name'] = $name instanceof Token ? $name->value : $name;

    if ($arguments !== null) {
        $ret['arguments'] = $arguments;
    }

    if ($distinct !== null) {
        $ret['distinct'] = $distinct;
    }
}

