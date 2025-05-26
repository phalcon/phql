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
use Phalcon\Phql\Tokens;

class phql_Parser
{
        const PHQL_AGAINST           = 1;                    /* Index of top element in stack */
        const PHQL_ALL               = 30;                 /* Shifts left before out of the error */
    // phql_ARG_SDECL                /* A place to hold %extra_argument */
    /* The parser's stack */
        const PHQL_AND               = 10; /* of YYSTACKDEPTH elements */
    const PHQL_AS                = 33;
    const PHQL_ASC               = 54;


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
    const PHQL_BETWEEN           = 2;
    const PHQL_BETWEEN_NOT       = 3;
    const PHQL_BITWISE_AND       = 14;
    const PHQL_BITWISE_NOT       = 25;
    const PHQL_BITWISE_OR        = 15;
    const PHQL_BITWISE_XOR       = 16;
    const PHQL_BPLACEHOLDER      = 65;
    const PHQL_BY                = 53;
    const PHQL_CASE              = 70;
    const PHQL_CAST              = 67;
    const PHQL_COMMA             = 26;
    const PHQL_CONVERT           = 68;
    const PHQL_CROSS             = 36;
    const PHQL_DELETE            = 49;
    const PHQL_DESC              = 55;
    const PHQL_DISTINCT          = 29;
    const PHQL_DIVIDE            = 17;
    const PHQL_DOT               = 32;
    const PHQL_DOUBLE            = 77;
    const PHQL_ELSE              = 74;
    const PHQL_END               = 71;
    const PHQL_EQUALS            = 4;
    const PHQL_EXISTS            = 66;
    const PHQL_FALSE             = 79;
    const PHQL_FOR               = 58;
    const PHQL_FROM              = 28;
    const PHQL_FULL              = 40;
    const PHQL_GREATER           = 7;
    const PHQL_GREATEREQUAL      = 8;
    const PHQL_GROUP             = 56;
    const PHQL_HAVING            = 57;
    const PHQL_HINTEGER          = 62;
    const PHQL_IDENTIFIER        = 31;
    const PHQL_ILIKE             = 13;
    const PHQL_IN                = 23;
    const PHQL_INNER             = 34;
    const PHQL_INSERT            = 42;
    const PHQL_INTEGER           = 61;
    const PHQL_INTO              = 43;
    const PHQL_IS                = 22;
    const PHQL_JOIN              = 35;
    const PHQL_LEFT              = 37;
    const PHQL_LESS              = 6;
    const PHQL_LESSEQUAL         = 9;
    const PHQL_LIKE              = 12;
    const PHQL_LIMIT             = 59;
    const PHQL_MINUS             = 21;
    const PHQL_MOD               = 19;
    const PHQL_NOT               = 24;
    const PHQL_NOTEQUALS         = 5;
    const PHQL_NPLACEHOLDER      = 63;
    const PHQL_NULL              = 75;
    const PHQL_OFFSET            = 60;
    const PHQL_ON                = 41;
    const PHQL_OR                = 11;
    const PHQL_ORDER             = 52;
    const PHQL_OUTER             = 38;
    const PHQL_PARENTHESES_CLOSE = 46;
    const PHQL_PARENTHESES_OPEN  = 45;
    const PHQL_PLUS              = 20;
    const PHQL_RIGHT             = 39;
    const PHQL_SELECT            = 27;
    const PHQL_SET               = 48;
    const PHQL_SPLACEHOLDER      = 64;
    const PHQL_STRING            = 76;
    const PHQL_THEN              = 73;
    const PHQL_TIMES             = 18;
    const PHQL_TRUE              = 78;
    const PHQL_UPDATE            = 47;
    const PHQL_USING             = 69;
    const PHQL_VALUES            = 44;
    const PHQL_WHEN              = 72;
    const PHQL_WHERE             = 51;
    const PHQL_WITH              = 50;
    const YYERRORSYMBOL = 80;
    const YYNOCODE = 135;
    const YYNRULE       = 162;
    const YYNSTATE      = 295;
    const YYSTACKDEPTH  = 100;
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
    const YY_REDUCE_MAX      = 90;
#define phql_TOKENTYPE phql_parser_token*
    const YY_REDUCE_USE_DFLT = -67;
    const YY_SHIFT_MAX      = 192;
    const YY_SHIFT_USE_DFLT = -3;
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
        81,
        1,
        82,
        1,
        82,
        1,
        82,
        1,
        82,
        1,
        83,
        7,
        87,
        6,
        94,
        1,
        94,
        1,
        94,
        0,
        95,
        3,
        95,
        1,
        98,
        1,
        98,
        3,
        98,
        3,
        98,
        2,
        98,
        1,
        96,
        3,
        96,
        1,
        97,
        1,
        97,
        0,
        101,
        2,
        101,
        1,
        102,
        1,
        103,
        4,
        106,
        2,
        106,
        1,
        106,
        0,
        104,
        2,
        104,
        2,
        104,
        3,
        104,
        2,
        104,
        3,
        104,
        2,
        104,
        3,
        104,
        2,
        104,
        1,
        107,
        2,
        107,
        0,
        84,
        7,
        84,
        10,
        108,
        3,
        108,
        1,
        111,
        1,
        109,
        3,
        109,
        1,
        112,
        1,
        85,
        3,
        113,
        4,
        115,
        3,
        115,
        1,
        116,
        3,
        118,
        1,
        86,
        3,
        119,
        3,
        100,
        3,
        100,
        2,
        100,
        1,
        100,
        5,
        100,
        7,
        100,
        6,
        100,
        4,
        100,
        5,
        100,
        3,
        121,
        3,
        121,
        1,
        120,
        1,
        105,
        1,
        88,
        2,
        88,
        0,
        91,
        3,
        91,
        0,
        122,
        3,
        122,
        1,
        123,
        1,
        123,
        2,
        123,
        2,
        89,
        3,
        89,
        0,
        124,
        3,
        124,
        1,
        125,
        1,
        90,
        2,
        90,
        0,
        93,
        2,
        93,
        0,
        92,
        2,
        92,
        4,
        92,
        4,
        92,
        0,
        114,
        2,
        114,
        0,
        126,
        1,
        126,
        1,
        126,
        1,
        126,
        1,
        126,
        1,
        99,
        2,
        99,
        3,
        99,
        3,
        99,
        3,
        99,
        3,
        99,
        3,
        99,
        3,
        99,
        3,
        99,
        3,
        99,
        3,
        99,
        3,
        99,
        3,
        99,
        3,
        99,
        3,
        99,
        3,
        99,
        3,
        99,
        3,
        99,
        3,
        99,
        4,
        99,
        3,
        99,
        4,
        99,
        5,
        99,
        6,
        99,
        3,
        99,
        5,
        99,
        6,
        99,
        4,
        99,
        3,
        99,
        6,
        99,
        6,
        99,
        4,
        128,
        2,
        128,
        1,
        129,
        4,
        129,
        2,
        99,
        1,
        130,
        5,
        131,
        1,
        131,
        0,
        132,
        1,
        132,
        0,
        127,
        3,
        127,
        1,
        133,
        1,
        133,
        1,
        99,
        3,
        99,
        4,
        99,
        3,
        99,
        3,
        99,
        2,
        99,
        2,
        99,
        3,
        99,
        1,
        99,
        1,
        99,
        1,
        99,
        1,
        99,
        1,
        99,
        1,
        99,
        1,
        99,
        1,
        99,
        1,
        99,
        1,
        99,
        1,
        117,
        3,
        117,
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
        "update_clause ::= UPDATE associated_name SET update_item_list",
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
        "expr ::= expr EQUALS expr",
        /* 109 */
        "expr ::= expr NOTEQUALS expr",
        /* 110 */
        "expr ::= expr LESS expr",
        /* 111 */
        "expr ::= expr GREATER expr",
        /* 112 */
        "expr ::= expr GREATEREQUAL expr",
        /* 113 */
        "expr ::= expr LESSEQUAL expr",
        /* 114 */
        "expr ::= expr LIKE expr",
        /* 115 */
        "expr ::= expr NOT LIKE expr",
        /* 116 */
        "expr ::= expr ILIKE expr",
        /* 117 */
        "expr ::= expr NOT ILIKE expr",
        /* 118 */
        "expr ::= expr IN PARENTHESES_OPEN argument_list PARENTHESES_CLOSE",
        /* 119 */
        "expr ::= expr NOT IN PARENTHESES_OPEN argument_list PARENTHESES_CLOSE",
        /* 120 */
        "expr ::= PARENTHESES_OPEN select_statement PARENTHESES_CLOSE",
        /* 121 */
        "expr ::= expr IN PARENTHESES_OPEN select_statement PARENTHESES_CLOSE",
        /* 122 */
        "expr ::= expr NOT IN PARENTHESES_OPEN select_statement PARENTHESES_CLOSE",
        /* 123 */
        "expr ::= EXISTS PARENTHESES_OPEN select_statement PARENTHESES_CLOSE",
        /* 124 */
        "expr ::= expr AGAINST expr",
        /* 125 */
        "expr ::= CAST PARENTHESES_OPEN expr AS IDENTIFIER PARENTHESES_CLOSE",
        /* 126 */
        "expr ::= CONVERT PARENTHESES_OPEN expr USING IDENTIFIER PARENTHESES_CLOSE",
        /* 127 */
        "expr ::= CASE expr when_clauses END",
        /* 128 */
        "when_clauses ::= when_clauses when_clause",
        /* 129 */
        "when_clauses ::= when_clause",
        /* 130 */
        "when_clause ::= WHEN expr THEN expr",
        /* 131 */
        "when_clause ::= ELSE expr",
        /* 132 */
        "expr ::= function_call",
        /* 133 */
        "function_call ::= IDENTIFIER PARENTHESES_OPEN distinct_or_null argument_list_or_null PARENTHESES_CLOSE",
        /* 134 */
        "distinct_or_null ::= DISTINCT",
        /* 135 */
        "distinct_or_null ::=",
        /* 136 */
        "argument_list_or_null ::= argument_list",
        /* 137 */
        "argument_list_or_null ::=",
        /* 138 */
        "argument_list ::= argument_list COMMA argument_item",
        /* 139 */
        "argument_list ::= argument_item",
        /* 140 */
        "argument_item ::= TIMES",
        /* 141 */
        "argument_item ::= expr",
        /* 142 */
        "expr ::= expr IS NULL",
        /* 143 */
        "expr ::= expr IS NOT NULL",
        /* 144 */
        "expr ::= expr BETWEEN expr",
        /* 145 */
        "expr ::= expr BETWEEN_NOT expr",
        /* 146 */
        "expr ::= NOT expr",
        /* 147 */
        "expr ::= BITWISE_NOT expr",
        /* 148 */
        "expr ::= PARENTHESES_OPEN expr PARENTHESES_CLOSE",
        /* 149 */
        "expr ::= qualified_name",
        /* 150 */
        "expr ::= INTEGER",
        /* 151 */
        "expr ::= HINTEGER",
        /* 152 */
        "expr ::= STRING",
        /* 153 */
        "expr ::= DOUBLE",
        /* 154 */
        "expr ::= NULL",
        /* 155 */
        "expr ::= TRUE",
        /* 156 */
        "expr ::= FALSE",
        /* 157 */
        "expr ::= NPLACEHOLDER",
        /* 158 */
        "expr ::= SPLACEHOLDER",
        /* 159 */
        "expr ::= BPLACEHOLDER",
        /* 160 */
        "qualified_name ::= IDENTIFIER DOT IDENTIFIER",
        /* 161 */
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
        'IS',
        'IN',
        'NOT',
        'BITWISE_NOT',
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
    var $yyTraceFILE   = null;
    var $yyTracePrompt = null;
    static $yy_action    = [
        /*     0 */
        50,
        46,
        45,
        19,
        52,
        49,
        40,
        42,
        20,
        28,
        /*    10 */
        25,
        24,
        30,
        23,
        22,
        21,
        33,
        37,
        31,
        41,
        /*    20 */
        48,
        134,
        162,
        129,
        69,
        50,
        46,
        45,
        19,
        52,
        /*    30 */
        49,
        40,
        42,
        20,
        28,
        25,
        24,
        30,
        23,
        22,
        /*    40 */
        21,
        33,
        37,
        31,
        41,
        48,
        134,
        162,
        129,
        182,
        /*    50 */
        292,
        154,
        183,
        208,
        209,
        270,
        82,
        157,
        68,
        50,
        /*    60 */
        46,
        45,
        19,
        52,
        49,
        40,
        42,
        20,
        28,
        25,
        /*    70 */
        24,
        30,
        23,
        22,
        21,
        33,
        37,
        31,
        41,
        48,
        /*    80 */
        134,
        162,
        129,
        50,
        46,
        45,
        19,
        52,
        49,
        40,
        /*    90 */
        42,
        20,
        28,
        25,
        24,
        30,
        23,
        22,
        21,
        33,
        /*   100 */
        37,
        31,
        41,
        48,
        134,
        162,
        129,
        19,
        52,
        49,
        /*   110 */
        40,
        42,
        20,
        28,
        25,
        24,
        30,
        23,
        22,
        21,
        /*   120 */
        33,
        37,
        31,
        41,
        48,
        134,
        162,
        129,
        216,
        147,
        /*   130 */
        32,
        38,
        44,
        28,
        25,
        24,
        30,
        23,
        22,
        21,
        /*   140 */
        33,
        37,
        31,
        41,
        48,
        134,
        162,
        129,
        223,
        224,
        /*   150 */
        60,
        192,
        160,
        50,
        46,
        45,
        19,
        52,
        49,
        40,
        /*   160 */
        42,
        20,
        28,
        25,
        24,
        30,
        23,
        22,
        21,
        33,
        /*   170 */
        37,
        31,
        41,
        48,
        134,
        162,
        129,
        24,
        30,
        23,
        /*   180 */
        22,
        21,
        33,
        37,
        31,
        41,
        48,
        134,
        162,
        129,
        /*   190 */
        33,
        37,
        31,
        41,
        48,
        134,
        162,
        129,
        228,
        72,
        /*   200 */
        50,
        46,
        45,
        19,
        52,
        49,
        40,
        42,
        20,
        28,
        /*   210 */
        25,
        24,
        30,
        23,
        22,
        21,
        33,
        37,
        31,
        41,
        /*   220 */
        48,
        134,
        162,
        129,
        170,
        263,
        161,
        146,
        133,
        145,
        /*   230 */
        144,
        287,
        187,
        215,
        50,
        46,
        45,
        19,
        52,
        49,
        /*   240 */
        40,
        42,
        20,
        28,
        25,
        24,
        30,
        23,
        22,
        21,
        /*   250 */
        33,
        37,
        31,
        41,
        48,
        134,
        162,
        129,
        50,
        46,
        /*   260 */
        45,
        19,
        52,
        49,
        40,
        42,
        20,
        28,
        25,
        24,
        /*   270 */
        30,
        23,
        22,
        21,
        33,
        37,
        31,
        41,
        48,
        134,
        /*   280 */
        162,
        129,
        238,
        233,
        32,
        47,
        44,
        101,
        27,
        26,
        /*   290 */
        286,
        72,
        92,
        16,
        268,
        143,
        132,
        72,
        234,
        283,
        /*   300 */
        41,
        48,
        134,
        162,
        129,
        239,
        18,
        222,
        238,
        4,
        /*   310 */
        239,
        47,
        163,
        284,
        27,
        26,
        207,
        55,
        235,
        173,
        /*   320 */
        141,
        143,
        131,
        235,
        169,
        240,
        241,
        247,
        248,
        249,
        /*   330 */
        191,
        184,
        190,
        90,
        35,
        4,
        56,
        290,
        154,
        244,
        /*   340 */
        242,
        243,
        245,
        246,
        170,
        263,
        161,
        146,
        158,
        145,
        /*   350 */
        144,
        240,
        241,
        247,
        248,
        249,
        191,
        184,
        190,
        137,
        /*   360 */
        35,
        90,
        206,
        266,
        91,
        244,
        242,
        243,
        245,
        246,
        /*   370 */
        267,
        221,
        200,
        47,
        210,
        226,
        27,
        26,
        53,
        264,
        /*   380 */
        224,
        60,
        239,
        142,
        23,
        22,
        21,
        33,
        37,
        31,
        /*   390 */
        41,
        48,
        134,
        162,
        129,
        235,
        5,
        4,
        75,
        47,
        /*   400 */
        29,
        34,
        27,
        26,
        289,
        72,
        180,
        181,
        186,
        143,
        /*   410 */
        221,
        151,
        128,
        240,
        241,
        247,
        248,
        249,
        191,
        184,
        /*   420 */
        190,
        212,
        35,
        4,
        221,
        74,
        220,
        244,
        242,
        243,
        /*   430 */
        245,
        246,
        78,
        189,
        155,
        216,
        135,
        82,
        82,
        240,
        /*   440 */
        241,
        247,
        248,
        249,
        191,
        184,
        190,
        88,
        35,
        105,
        /*   450 */
        95,
        218,
        175,
        244,
        242,
        243,
        245,
        246,
        101,
        262,
        /*   460 */
        260,
        47,
        171,
        177,
        27,
        26,
        214,
        239,
        239,
        211,
        /*   470 */
        282,
        143,
        71,
        252,
        128,
        285,
        239,
        138,
        10,
        150,
        /*   480 */
        235,
        235,
        265,
        250,
        215,
        4,
        221,
        128,
        254,
        235,
        /*   490 */
        164,
        458,
        294,
        194,
        195,
        196,
        197,
        82,
        64,
        221,
        /*   500 */
        89,
        240,
        241,
        247,
        248,
        249,
        191,
        184,
        190,
        12,
        /*   510 */
        35,
        17,
        293,
        105,
        92,
        244,
        242,
        243,
        245,
        246,
        /*   520 */
        134,
        162,
        129,
        84,
        153,
        272,
        273,
        70,
        82,
        86,
        /*   530 */
        140,
        239,
        239,
        271,
        91,
        87,
        215,
        179,
        278,
        258,
        /*   540 */
        105,
        167,
        148,
        193,
        235,
        235,
        165,
        250,
        128,
        105,
        /*   550 */
        65,
        156,
        239,
        201,
        202,
        203,
        204,
        205,
        239,
        43,
        /*   560 */
        221,
        101,
        166,
        66,
        99,
        235,
        79,
        239,
        139,
        152,
        /*   570 */
        136,
        235,
        288,
        283,
        250,
        178,
        99,
        198,
        102,
        239,
        /*   580 */
        235,
        94,
        239,
        237,
        67,
        58,
        124,
        239,
        51,
        159,
        /*   590 */
        280,
        85,
        235,
        93,
        239,
        235,
        239,
        291,
        77,
        239,
        /*   600 */
        235,
        123,
        279,
        239,
        239,
        118,
        39,
        235,
        168,
        235,
        /*   610 */
        117,
        239,
        235,
        115,
        80,
        97,
        235,
        235,
        219,
        239,
        /*   620 */
        216,
        130,
        77,
        239,
        235,
        83,
        239,
        225,
        239,
        103,
        /*   630 */
        98,
        239,
        235,
        239,
        113,
        81,
        235,
        96,
        122,
        235,
        /*   640 */
        116,
        235,
        213,
        251,
        235,
        172,
        235,
        239,
        239,
        174,
        /*   650 */
        11,
        232,
        239,
        3,
        104,
        239,
        239,
        112,
        239,
        9,
        /*   660 */
        235,
        235,
        257,
        239,
        110,
        235,
        16,
        239,
        235,
        235,
        /*   670 */
        276,
        235,
        239,
        121,
        119,
        239,
        235,
        2,
        120,
        107,
        /*   680 */
        235,
        108,
        239,
        106,
        215,
        235,
        281,
        129,
        235,
        125,
        /*   690 */
        100,
        239,
        239,
        127,
        275,
        235,
        239,
        239,
        63,
        239,
        /*   700 */
        109,
        239,
        11,
        77,
        235,
        235,
        14,
        239,
        239,
        235,
        /*   710 */
        235,
        239,
        235,
        229,
        235,
        114,
        126,
        111,
        239,
        6,
        /*   720 */
        235,
        235,
        274,
        217,
        235,
        269,
        222,
        15,
        158,
        1,
        /*   730 */
        62,
        235,
        256,
        239,
        239,
        239,
        253,
        236,
        7,
        11,
        /*   740 */
        227,
        255,
        261,
        54,
        176,
        259,
        235,
        235,
        235,
        8,
        /*   750 */
        199,
        13,
        59,
        185,
        188,
        149,
        298,
        57,
        76,
        298,
        /*   760 */
        230,
        73,
        231,
        277,
        36,
        61,
    ];
    static $yy_default     = [
        /*     0 */
        457,
        457,
        457,
        432,
        457,
        457,
        457,
        457,
        457,
        457,
        /*    10 */
        315,
        457,
        457,
        457,
        457,
        457,
        457,
        457,
        457,
        457,
        /*    20 */
        457,
        457,
        457,
        457,
        457,
        457,
        457,
        457,
        457,
        457,
        /*    30 */
        457,
        457,
        457,
        457,
        457,
        457,
        457,
        457,
        457,
        457,
        /*    40 */
        457,
        457,
        457,
        457,
        457,
        457,
        457,
        457,
        457,
        457,
        /*    50 */
        457,
        457,
        457,
        314,
        457,
        457,
        457,
        457,
        457,
        457,
        /*    60 */
        457,
        457,
        457,
        457,
        457,
        457,
        457,
        457,
        457,
        457,
        /*    70 */
        457,
        457,
        304,
        457,
        457,
        322,
        457,
        457,
        457,
        373,
        /*    80 */
        386,
        366,
        364,
        380,
        364,
        386,
        364,
        384,
        333,
        378,
        /*    90 */
        430,
        311,
        369,
        457,
        457,
        457,
        457,
        419,
        377,
        376,
        /*   100 */
        363,
        338,
        347,
        332,
        425,
        436,
        426,
        440,
        439,
        407,
        /*   110 */
        408,
        406,
        403,
        404,
        405,
        398,
        399,
        409,
        411,
        401,
        /*   120 */
        400,
        402,
        395,
        397,
        396,
        392,
        394,
        393,
        352,
        457,
        /*   130 */
        457,
        381,
        457,
        457,
        457,
        457,
        457,
        457,
        457,
        457,
        /*   140 */
        457,
        457,
        456,
        456,
        457,
        457,
        457,
        457,
        457,
        457,
        /*   150 */
        457,
        457,
        412,
        457,
        457,
        457,
        457,
        457,
        457,
        372,
        /*   160 */
        456,
        457,
        457,
        457,
        457,
        457,
        457,
        431,
        410,
        457,
        /*   170 */
        457,
        457,
        441,
        457,
        442,
        457,
        457,
        457,
        457,
        365,
        /*   180 */
        351,
        457,
        343,
        457,
        457,
        350,
        457,
        457,
        457,
        457,
        /*   190 */
        457,
        457,
        457,
        349,
        296,
        297,
        298,
        299,
        300,
        379,
        /*   200 */
        382,
        387,
        388,
        389,
        390,
        391,
        383,
        367,
        370,
        371,
        /*   210 */
        301,
        312,
        353,
        354,
        359,
        361,
        360,
        355,
        356,
        357,
        /*   220 */
        358,
        362,
        455,
        316,
        318,
        319,
        437,
        438,
        443,
        415,
        /*   230 */
        418,
        420,
        421,
        422,
        423,
        427,
        428,
        433,
        435,
        444,
        /*   240 */
        445,
        446,
        447,
        448,
        449,
        450,
        451,
        452,
        453,
        454,
        /*   250 */
        434,
        429,
        424,
        320,
        321,
        323,
        324,
        325,
        326,
        327,
        /*   260 */
        328,
        329,
        330,
        331,
        317,
        313,
        305,
        307,
        308,
        309,
        /*   270 */
        310,
        306,
        302,
        303,
        413,
        416,
        414,
        417,
        368,
        374,
        /*   280 */
        375,
        334,
        336,
        337,
        335,
        339,
        341,
        340,
        342,
        385,
        /*   290 */
        344,
        346,
        345,
        348,
        295,
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
        26,
        1,
        2,
        3,
        4,
        5,
        /*    30 */
        6,
        7,
        8,
        9,
        10,
        11,
        12,
        13,
        14,
        15,
        /*    40 */
        16,
        17,
        18,
        19,
        20,
        21,
        22,
        23,
        24,
        115,
        /*    50 */
        116,
        117,
        83,
        54,
        55,
        31,
        87,
        33,
        60,
        1,
        /*    60 */
        2,
        3,
        4,
        5,
        6,
        7,
        8,
        9,
        10,
        11,
        /*    70 */
        12,
        13,
        14,
        15,
        16,
        17,
        18,
        19,
        20,
        21,
        /*    80 */
        22,
        23,
        24,
        1,
        2,
        3,
        4,
        5,
        6,
        7,
        /*    90 */
        8,
        9,
        10,
        11,
        12,
        13,
        14,
        15,
        16,
        17,
        /*   100 */
        18,
        19,
        20,
        21,
        22,
        23,
        24,
        4,
        5,
        6,
        /*   110 */
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
        /*   120 */
        17,
        18,
        19,
        20,
        21,
        22,
        23,
        24,
        120,
        121,
        /*   130 */
        72,
        45,
        74,
        10,
        11,
        12,
        13,
        14,
        15,
        16,
        /*   140 */
        17,
        18,
        19,
        20,
        21,
        22,
        23,
        24,
        102,
        103,
        /*   150 */
        104,
        69,
        31,
        1,
        2,
        3,
        4,
        5,
        6,
        7,
        /*   160 */
        8,
        9,
        10,
        11,
        12,
        13,
        14,
        15,
        16,
        17,
        /*   170 */
        18,
        19,
        20,
        21,
        22,
        23,
        24,
        12,
        13,
        14,
        /*   180 */
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
        /*   190 */
        17,
        18,
        19,
        20,
        21,
        22,
        23,
        24,
        46,
        27,
        /*   200 */
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
        /*   210 */
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
        /*   220 */
        21,
        22,
        23,
        24,
        34,
        35,
        36,
        37,
        109,
        39,
        /*   230 */
        40,
        112,
        33,
        31,
        1,
        2,
        3,
        4,
        5,
        6,
        /*   240 */
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
        /*   250 */
        17,
        18,
        19,
        20,
        21,
        22,
        23,
        24,
        1,
        2,
        /*   260 */
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
        /*   270 */
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
        /*   280 */
        23,
        24,
        18,
        71,
        72,
        21,
        74,
        99,
        24,
        25,
        /*   290 */
        31,
        27,
        99,
        26,
        18,
        31,
        108,
        27,
        129,
        111,
        /*   300 */
        20,
        21,
        22,
        23,
        24,
        117,
        73,
        31,
        18,
        45,
        /*   310 */
        117,
        21,
        42,
        46,
        24,
        25,
        123,
        47,
        130,
        49,
        /*   320 */
        32,
        31,
        126,
        130,
        24,
        61,
        62,
        63,
        64,
        65,
        /*   330 */
        66,
        67,
        68,
        45,
        70,
        45,
        26,
        116,
        117,
        75,
        /*   340 */
        76,
        77,
        78,
        79,
        34,
        35,
        36,
        37,
        32,
        39,
        /*   350 */
        40,
        61,
        62,
        63,
        64,
        65,
        66,
        67,
        68,
        105,
        /*   360 */
        70,
        45,
        126,
        98,
        99,
        75,
        76,
        77,
        78,
        79,
        /*   370 */
        18,
        117,
        126,
        21,
        97,
        75,
        24,
        25,
        101,
        102,
        /*   380 */
        103,
        104,
        117,
        31,
        14,
        15,
        16,
        17,
        18,
        19,
        /*   390 */
        20,
        21,
        22,
        23,
        24,
        130,
        94,
        45,
        105,
        21,
        /*   400 */
        12,
        13,
        24,
        25,
        126,
        27,
        31,
        100,
        33,
        31,
        /*   410 */
        117,
        23,
        105,
        61,
        62,
        63,
        64,
        65,
        66,
        67,
        /*   420 */
        68,
        120,
        70,
        45,
        117,
        50,
        120,
        75,
        76,
        77,
        /*   430 */
        78,
        79,
        26,
        83,
        83,
        120,
        121,
        87,
        87,
        61,
        /*   440 */
        62,
        63,
        64,
        65,
        66,
        67,
        68,
        106,
        70,
        99,
        /*   450 */
        99,
        120,
        46,
        75,
        76,
        77,
        78,
        79,
        99,
        35,
        /*   460 */
        35,
        21,
        38,
        38,
        24,
        25,
        120,
        117,
        117,
        100,
        /*   470 */
        111,
        31,
        128,
        129,
        105,
        112,
        117,
        127,
        96,
        56,
        /*   480 */
        130,
        130,
        100,
        133,
        31,
        45,
        117,
        105,
        31,
        130,
        /*   490 */
        33,
        81,
        82,
        83,
        84,
        85,
        86,
        87,
        45,
        117,
        /*   500 */
        89,
        61,
        62,
        63,
        64,
        65,
        66,
        67,
        68,
        26,
        /*   510 */
        70,
        28,
        114,
        99,
        99,
        75,
        76,
        77,
        78,
        79,
        /*   520 */
        22,
        23,
        24,
        113,
        83,
        29,
        30,
        59,
        87,
        119,
        /*   530 */
        95,
        117,
        117,
        98,
        99,
        91,
        31,
        122,
        123,
        35,
        /*   540 */
        99,
        127,
        38,
        100,
        130,
        130,
        132,
        133,
        105,
        99,
        /*   550 */
        45,
        52,
        117,
        61,
        62,
        63,
        64,
        65,
        117,
        51,
        /*   560 */
        117,
        99,
        44,
        45,
        99,
        130,
        88,
        117,
        127,
        99,
        /*   570 */
        108,
        130,
        114,
        111,
        133,
        58,
        99,
        93,
        99,
        117,
        /*   580 */
        130,
        99,
        117,
        133,
        59,
        99,
        99,
        117,
        41,
        124,
        /*   590 */
        125,
        88,
        130,
        99,
        117,
        130,
        117,
        118,
        26,
        117,
        /*   600 */
        130,
        99,
        125,
        117,
        117,
        99,
        57,
        130,
        99,
        130,
        /*   610 */
        99,
        117,
        130,
        99,
        88,
        99,
        130,
        130,
        46,
        117,
        /*   620 */
        120,
        121,
        26,
        117,
        130,
        92,
        117,
        107,
        117,
        99,
        /*   630 */
        99,
        117,
        130,
        117,
        99,
        90,
        130,
        99,
        99,
        130,
        /*   640 */
        99,
        130,
        46,
        29,
        130,
        99,
        130,
        117,
        117,
        99,
        /*   650 */
        26,
        46,
        117,
        131,
        99,
        117,
        117,
        99,
        117,
        53,
        /*   660 */
        130,
        130,
        35,
        117,
        99,
        130,
        26,
        117,
        130,
        130,
        /*   670 */
        46,
        130,
        117,
        99,
        99,
        117,
        130,
        45,
        99,
        99,
        /*   680 */
        130,
        99,
        117,
        99,
        31,
        130,
        46,
        24,
        130,
        99,
        /*   690 */
        99,
        117,
        117,
        99,
        46,
        130,
        117,
        117,
        45,
        117,
        /*   700 */
        99,
        117,
        26,
        26,
        130,
        130,
        4,
        117,
        117,
        130,
        /*   710 */
        130,
        117,
        130,
        46,
        130,
        99,
        99,
        99,
        117,
        53,
        /*   720 */
        130,
        130,
        46,
        46,
        130,
        31,
        31,
        26,
        32,
        45,
        /*   730 */
        43,
        130,
        35,
        117,
        117,
        117,
        31,
        46,
        45,
        26,
        /*   740 */
        75,
        35,
        35,
        28,
        44,
        35,
        130,
        130,
        130,
        45,
        /*   750 */
        47,
        26,
        26,
        31,
        31,
        31,
        134,
        48,
        50,
        134,
        /*   760 */
        46,
        50,
        46,
        46,
        45,
        45,
    ];
    static $yy_reduce_ofst = [
        /*     0 */
        410,
        441,
        350,
        414,
        351,
        435,
        415,
        462,
        188,
        465,
        /*    10 */
        277,
        450,
        265,
        193,
        479,
        477,
        359,
        382,
        555,
        558,
        /*    20 */
        565,
        574,
        575,
        579,
        511,
        541,
        550,
        546,
        514,
        509,
        /*    30 */
        506,
        502,
        494,
        487,
        470,
        486,
        482,
        539,
        538,
        531,
        /*    40 */
        618,
        617,
        601,
        591,
        584,
        580,
        582,
        590,
        594,
        616,
        /*    50 */
        516,
        530,
        535,
        46,
        443,
        307,
        369,
        -66,
        344,
        221,
        /*    60 */
        293,
        -31,
        254,
        500,
        315,
        8,
        119,
        196,
        236,
        246,
        /*    70 */
        278,
        169,
        302,
        301,
        306,
        341,
        331,
        346,
        363,
        411,
        /*    80 */
        398,
        444,
        478,
        484,
        503,
        458,
        526,
        533,
        520,
        545,
        /*    90 */
        522,
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
        270,
        264,
        264,
        290,
        378,
        352,
        440,
        440,
        440,
        440,
        /*    10 */
        310,
        290,
        352,
        440,
        440,
        440,
        440,
        121,
        440,
        440,
        /*    20 */
        440,
        440,
        440,
        440,
        440,
        440,
        440,
        440,
        440,
        440,
        /*    30 */
        440,
        440,
        440,
        440,
        440,
        440,
        440,
        440,
        440,
        440,
        /*    40 */
        440,
        440,
        440,
        440,
        440,
        440,
        440,
        440,
        440,
        440,
        /*    50 */
        440,
        440,
        440,
        190,
        121,
        121,
        121,
        121,
        58,
        121,
        /*    60 */
        121,
        172,
        121,
        202,
        202,
        202,
        259,
        492,
        492,
        492,
        /*    70 */
        492,
        212,
        496,
        453,
        505,
        457,
        653,
        202,
        259,
        423,
        /*    80 */
        468,
        499,
        508,
        517,
        508,
        468,
        508,
        525,
        547,
        549,
        /*    90 */
        614,
        24,
        -1,
        233,
        82,
        152,
        199,
        257,
        257,
        257,
        /*   100 */
        257,
        257,
        257,
        257,
        257,
        257,
        257,
        103,
        103,
        123,
        /*   110 */
        123,
        123,
        123,
        123,
        123,
        165,
        165,
        370,
        370,
        173,
        /*   120 */
        173,
        173,
        280,
        280,
        280,
        498,
        498,
        498,
        375,
        388,
        /*   130 */
        677,
        -2,
        267,
        406,
        300,
        596,
        640,
        518,
        624,
        676,
        /*   140 */
        483,
        276,
        288,
        316,
        424,
        425,
        504,
        572,
        627,
        605,
        /*   150 */
        606,
        632,
        663,
        648,
        702,
        667,
        666,
        694,
        695,
        701,
        /*   160 */
        696,
        697,
        684,
        687,
        705,
        691,
        693,
        713,
        663,
        665,
        /*   170 */
        706,
        707,
        663,
        715,
        663,
        700,
        704,
        710,
        703,
        725,
        /*   180 */
        708,
        709,
        726,
        714,
        86,
        711,
        722,
        723,
        716,
        717,
        /*   190 */
        719,
        720,
        724,
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
var /* int */
        $yyerrcnt;

    /* For tracing shifts, the names of all terminals and nonterminals
** are required.  The following table supplies these names */
var /* int */
        $yyidx = -1;

    /* For tracing reduce actions, the names of all rules are required.
*/
var $yystack = [];

    /*
** This function returns the symbolic name associated with a token
** value.
*/

    public function __construct(private Status $status)
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

    function phql_(
        $yymajor,                 /* The major token code number */
        $yyminor = null           /* The value for the token */
    )
    {
        $yyact        = 0;            /* The parser action. */
        $yyendofinput = 0;     /* True if we are at the end of input */
        $yyerrorhit   = 0;   /* True if yymajor has invoked an error */

        /* (re)initialize the parser, if necessary */
        if ($this->yyidx < 0) {
            $this->yyidx    = 0;
            $this->yyerrcnt = -1;
            $ent            = new phql_yyStackEntry;
            $ent->stateno   = 0;
            $ent->major     = 0;
            $this->yystack  = [0 => $ent];

            $this->YY_NO_ACTION     = self::YYNSTATE + self::YYNRULE + 2;
            $this->YY_ACCEPT_ACTION = self::YYNSTATE + self::YYNRULE + 1;
            $this->YY_ERROR_ACTION  = self::YYNSTATE + self::YYNRULE;
        }
        $yyendofinput = ($yymajor == 0);

        if ($this->yyTraceFILE) {
            fprintf(
                $this->yyTraceFILE,
                "%sInput %s\n",
                $this->yyTracePrompt,
                self::$yyTokenName[$yymajor]
            );
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
                            fprintf($this->yyTraceFILE, "%sSyntax Error!\n", $this->yyTracePrompt);
                        }
                        if (self::YYERRORSYMBOL) {
                            /* A syntax error has occurred.
              ** The response to an error depends upon whether or not the
              ** grammar defines an error token "ERROR".
              **
              ** This is what we do if the grammar does define ERROR:
              **
              **  * Call the %syntax_error function.
              **
              **  * Begin popping the stack until we enter a state where
              **    it is legal to shift the error symbol, then shift
              **    the error symbol.
              **
              **  * Set the error count to three.
              **
              **  * Begin accepting and shifting new tokens.  No new error
              **    processing will occur until three tokens have been
              **    shifted successfully.
              **
              */
                            if ($this->yyerrcnt < 0) {
                                $this->yy_syntax_error();
                            }
                            $yymx = $this->yystack[$this->yyidx]->major;
                            if ($yymx == self::YYERRORSYMBOL || $yyerrorhit) {
                                if ($this->yyTraceFILE) {
                                    fprintf(
                                        $this->yyTraceFILE,
                                        "%sDiscard input token %s\n",
                                        $this->yyTracePrompt,
                                        self::$yyTokenName[$yymajor]
                                    );
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
                            $yyerrorhit     = 1;
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
                                $this->yy_syntax_error($yymajor, $yyminor);
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

    function phql_Trace(/* stream */ $TraceFILE, /* string */ $zTracePrompt)
    {
        $this->yyTraceFILE   = $TraceFILE;
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
            fprintf($this->yyTraceFILE, "%sAccept!\n", $this->yyTracePrompt);
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
                if (isset($yypminor->yy0)) {
                    if (($yypminor->yy0)->free_flag) {
                        unset(($yypminor->yy0)->token);
                    }
                    unset($yypminor->yy0);
                }
                break;
            case 82:
            case 83:
            case 84:
            case 85:
            case 86:
            case 87:
            case 89:
            case 95:
            case 96:
            case 98:
            case 99:
            case 100:
            case 101:
            case 102:
            case 103:
            case 104:
            case 105:
            case 109:
            case 110:
            case 112:
            case 113:
            case 115:
            case 116:
            case 117:
            case 119:
            case 120:
            case 121:
            case 122:
            case 123:
            case 124:
            case 125:
            case 127:
            case 130:
            case 133:
                unset($yypminor->yy202);
                break;
            case 88:
            case 90:
            case 91:
            case 92:
            case 93:
            case 94:
            case 106:
            case 107:
            case 114:
            case 131:
            case 132:
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
        $i = 0;
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
                        fprintf(
                            $this->yyTraceFILE,
                            "%sFALLBACK %s => %s\n",
                            $this->yyTracePrompt,
                            self::$yyTokenName[$iLookAhead],
                            self::$yyTokenName[$iFallback]
                        );
                    }
                    return $this->yy_find_shift_action($iFallback);
                }
            }
            return self::$yy_default[$stateno];
        } else {
            return self::$yy_action[$i];
        }
    }

    private function yy_parse_failed()
    {
        if ($this->yyTraceFILE) {
            fprintf($this->yyTraceFILE, "%sFail!\n", $this->yyTracePrompt);
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
            fprintf(
                $this->yyTraceFILE,
                "%sPopping %s\n",
                $this->yyTracePrompt,
                self::$yyTokenName[$yytos->major],
            );
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
            fprintf(
                $this->yyTraceFILE,
                "%sReduce [%s].\n",
                $this->yyTracePrompt,
                self::$yyRuleName[$yyruleno]
            );
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
                {
                    //ZVAL_ZVAL($status->ret, $this->yystack[$this->yyidx + 0]->minor, 1, 1);
                }
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
            case 132:
            case 136:
            case 141:
            case 149:
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
#line 146 "c/parser.php.lemon"
                {
                    phql_ret_select_clause(
                        $yygotominor,
                        $this->yystack[$this->yyidx + -4]->minor,
                        $this->yystack[$this->yyidx + -3]->minor,
                        $this->yystack[$this->yyidx + -1]->minor,
                        $this->yystack[$this->yyidx + 0]->minor
                    );
                    $this->yy_destructor(27, $this->yystack[$this->yyidx + -5]->minor);
                    $this->yy_destructor(28, $this->yystack[$this->yyidx + -2]->minor);
                }
#line 1200 "c/parser.php.php"
                break;
            case 7:
                $yygotominor = 1;
                $this->yy_destructor(29, $this->yystack[$this->yyidx + 0]->minor);
                break;
            case 8:
                $yygotominor = 0;
                $this->yy_destructor(30, $this->yystack[$this->yyidx + 0]->minor);
                break;
            case 9:
            case 20:
            case 27:
            case 38:
            case 69:
            case 71:
            case 78:
            case 83:
            case 85:
            case 89:
            case 91:
            case 135:
            case 137:
                unset($yygotominor);
                break;
            case 10:
            case 17:
            case 41:
            case 44:
            case 49:
            case 64:
            case 72:
            case 79:
            case 138:
                phql_ret_zval_list(
                    $yygotominor,
                    $this->yystack[$this->yyidx + -2]->minor,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                $this->yy_destructor(26, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 11:
            case 42:
            case 45:
            case 129:
            case 139:
                phql_ret_zval_list($yygotominor, $this->yystack[$this->yyidx + 0]->minor);
                break;
            case 12:
            case 140:
#line 182 "c/parser.php.lemon"
                {
                    phql_ret_column_item($yygotominor, Opcode::PHQL_T_STARALL);
                    $this->yy_destructor(18, $this->yystack[$this->yyidx + 0]->minor);
                }
#line 1271 "c/parser.php.php"
                break;
            case 13:
#line 186 "c/parser.php.lemon"
                {
                    phql_ret_column_item(
                        $yygotominor,
                        Opcode::PHQL_T_DOMAINALL,
                        null,
                        $this->yystack[$this->yyidx + -2]->minor,
                        null
                    );
                    $this->yy_destructor(32, $this->yystack[$this->yyidx + -1]->minor);
                    $this->yy_destructor(18, $this->yystack[$this->yyidx + 0]->minor);
                }
#line 1280 "c/parser.php.php"
                break;
            case 14:
#line 190 "c/parser.php.lemon"
                {
                    phql_ret_column_item(
                        $yygotominor,
                        Opcode::PHQL_T_EXPR,
                        $this->yystack[$this->yyidx + -2]->minor,
                        null,
                        $this->yystack[$this->yyidx + 0]->minor
                    );
                    $this->yy_destructor(33, $this->yystack[$this->yyidx + -1]->minor);
                }
#line 1288 "c/parser.php.php"
                break;
            case 15:
#line 194 "c/parser.php.lemon"
                {
                    phql_ret_column_item(
                        $yygotominor,
                        Opcode::PHQL_T_EXPR,
                        $this->yystack[$this->yyidx + -1]->minor,
                        null,
                        $this->yystack[$this->yyidx + 0]->minor
                    );
                }
#line 1295 "c/parser.php.php"
                break;
            case 16:
#line 198 "c/parser.php.lemon"
                {
                    phql_ret_column_item(
                        $yygotominor,
                        Opcode::PHQL_T_EXPR,
                        $this->yystack[$this->yyidx + 0]->minor,
                        null,
                        null
                    );
                }
#line 1302 "c/parser.php.php"
                break;
            case 21:
            case 128:
#line 226 "c/parser.php.lemon"
                {
                    phql_ret_zval_list(
                        $yygotominor,
                        $this->yystack[$this->yyidx + -1]->minor,
                        $this->yystack[$this->yyidx + 0]->minor
                    );
                }
#line 1310 "c/parser.php.php"
                break;
            case 24:
#line 247 "c/parser.php.lemon"
                {
                    phql_ret_join_item(
                        $yygotominor,
                        $this->yystack[$this->yyidx + -3]->minor,
                        $this->yystack[$this->yyidx + -2]->minor,
                        $this->yystack[$this->yyidx + -1]->minor,
                        $this->yystack[$this->yyidx + 0]->minor
                    );
                }
#line 1317 "c/parser.php.php"
                break;
            case 25:
#line 255 "c/parser.php.lemon"
                {
                    phql_ret_qualified_name($yygotominor, null, null, $this->yystack[$this->yyidx + 0]->minor);
                    $this->yy_destructor(33, $this->yystack[$this->yyidx + -1]->minor);
                }
#line 1325 "c/parser.php.php"
                break;
            case 26:
            case 46:
            case 66:
            case 161:
#line 259 "c/parser.php.lemon"
                {
                    phql_ret_qualified_name($yygotominor, null, null, $this->yystack[$this->yyidx + 0]->minor);
                }
#line 1335 "c/parser.php.php"
                break;
            case 28:
#line 271 "c/parser.php.lemon"
                {
                    $yygotominor = Opcode::PHQL_T_INNERJOIN;
                    $this->yy_destructor(34, $this->yystack[$this->yyidx + -1]->minor);
                    $this->yy_destructor(35, $this->yystack[$this->yyidx + 0]->minor);
                }
#line 1344 "c/parser.php.php"
                break;
            case 29:
#line 275 "c/parser.php.lemon"
                {
                    $yygotominor = Opcode::PHQL_T_CROSSJOIN;
                    $this->yy_destructor(36, $this->yystack[$this->yyidx + -1]->minor);
                    $this->yy_destructor(35, $this->yystack[$this->yyidx + 0]->minor);
                }
#line 1353 "c/parser.php.php"
                break;
            case 30:
#line 279 "c/parser.php.lemon"
                {
                    $yygotominor = Opcode::PHQL_T_LEFTJOIN;
                    $this->yy_destructor(37, $this->yystack[$this->yyidx + -2]->minor);
                    $this->yy_destructor(38, $this->yystack[$this->yyidx + -1]->minor);
                    $this->yy_destructor(35, $this->yystack[$this->yyidx + 0]->minor);
                }
#line 1363 "c/parser.php.php"
                break;
            case 31:
                $yygotominor = Opcode::PHQL_T_LEFTJOIN;
                $this->yy_destructor(37, $this->yystack[$this->yyidx + -1]->minor);
                $this->yy_destructor(35, $this->yystack[$this->yyidx + 0]->minor);
                break;
            case 32:
                $yygotominor = Opcode::PHQL_T_RIGHTJOIN;
                $this->yy_destructor(39, $this->yystack[$this->yyidx + -2]->minor);
                $this->yy_destructor(38, $this->yystack[$this->yyidx + -1]->minor);
                $this->yy_destructor(35, $this->yystack[$this->yyidx + 0]->minor);
                break;
            case 33:
#line 291 "c/parser.php.lemon"
                {
                    $yygotominor = Opcode::PHQL_T_RIGHTJOIN;
                    $this->yy_destructor(39, $this->yystack[$this->yyidx + -1]->minor);
                    $this->yy_destructor(35, $this->yystack[$this->yyidx + 0]->minor);
                }
#line 1391 "c/parser.php.php"
                break;
            case 34:
#line 295 "c/parser.php.lemon"
                {
                    $yygotominor = Opcode::PHQL_T_FULLJOIN;
                    $this->yy_destructor(40, $this->yystack[$this->yyidx + -2]->minor);
                    $this->yy_destructor(38, $this->yystack[$this->yyidx + -1]->minor);
                    $this->yy_destructor(35, $this->yystack[$this->yyidx + 0]->minor);
                }
#line 1401 "c/parser.php.php"
                break;
            case 35:
#line 299 "c/parser.php.lemon"
                {
                    $yygotominor = Opcode::PHQL_T_FULLJOIN;
                    $this->yy_destructor(40, $this->yystack[$this->yyidx + -1]->minor);
                    $this->yy_destructor(35, $this->yystack[$this->yyidx + 0]->minor);
                }
#line 1410 "c/parser.php.php"
                break;
            case 36:
                $yygotominor = Opcode::PHQL_T_INNERJOIN;
                $this->yy_destructor(35, $this->yystack[$this->yyidx + 0]->minor);
                break;
            case 37:
#line 311 "c/parser.php.lemon"
                {
                    $yygotominor = $this->yystack[$this->yyidx + 0]->minor;
                    $this->yy_destructor(41, $this->yystack[$this->yyidx + -1]->minor);
                }
#line 1426 "c/parser.php.php"
                break;
            case 39:
                phql_ret_insert_statement(
                    $yygotominor,
                    $this->yystack[$this->yyidx + -4]->minor,
                    null,
                    $this->yystack[$this->yyidx + -1]->minor
                );
                $this->yy_destructor(42, $this->yystack[$this->yyidx + -6]->minor);
                $this->yy_destructor(43, $this->yystack[$this->yyidx + -5]->minor);
                $this->yy_destructor(44, $this->yystack[$this->yyidx + -3]->minor);
                $this->yy_destructor(45, $this->yystack[$this->yyidx + -2]->minor);
                $this->yy_destructor(46, $this->yystack[$this->yyidx + 0]->minor);
                break;
            case 40:
                phql_ret_insert_statement(
                    $yygotominor,
                    $this->yystack[$this->yyidx + -7]->minor,
                    $this->yystack[$this->yyidx + -5]->minor,
                    $this->yystack[$this->yyidx + -1]->minor
                );
                $this->yy_destructor(42, $this->yystack[$this->yyidx + -9]->minor);
                $this->yy_destructor(43, $this->yystack[$this->yyidx + -8]->minor);
                $this->yy_destructor(45, $this->yystack[$this->yyidx + -6]->minor);
                $this->yy_destructor(46, $this->yystack[$this->yyidx + -4]->minor);
                $this->yy_destructor(44, $this->yystack[$this->yyidx + -3]->minor);
                $this->yy_destructor(45, $this->yystack[$this->yyidx + -2]->minor);
                $this->yy_destructor(46, $this->yystack[$this->yyidx + 0]->minor);
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
                    $this->yystack[$this->yyidx + -2]->minor,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                $this->yy_destructor(47, $this->yystack[$this->yyidx + -3]->minor);
                $this->yy_destructor(48, $this->yystack[$this->yyidx + -1]->minor);
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
                $this->yy_destructor(49, $this->yystack[$this->yyidx + -2]->minor);
                $this->yy_destructor(28, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 55:
                phql_ret_assoc_name(
                    $yygotominor,
                    $this->yystack[$this->yyidx + -2]->minor,
                    $this->yystack[$this->yyidx + 0]->minor,
                    null
                );
                $this->yy_destructor(33, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 56:
                phql_ret_assoc_name(
                    $yygotominor,
                    $this->yystack[$this->yyidx + -1]->minor,
                    $this->yystack[$this->yyidx + 0]->minor,
                );
                break;
            case 57:
                phql_ret_assoc_name($yygotominor, $this->yystack[$this->yyidx + 0]->minor, null, null);
                break;
            case 58:
                phql_ret_assoc_name(
                    $yygotominor,
                    $this->yystack[$this->yyidx + -4]->minor,
                    $this->yystack[$this->yyidx + -2]->minor,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                $this->yy_destructor(33, $this->yystack[$this->yyidx + -3]->minor);
                $this->yy_destructor(50, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 59:
                phql_ret_assoc_name(
                    $yygotominor,
                    $this->yystack[$this->yyidx + -6]->minor,
                    $this->yystack[$this->yyidx + -4]->minor,
                    $this->yystack[$this->yyidx + -1]->minor
                );
                $this->yy_destructor(33, $this->yystack[$this->yyidx + -5]->minor);
                $this->yy_destructor(50, $this->yystack[$this->yyidx + -3]->minor);
                $this->yy_destructor(45, $this->yystack[$this->yyidx + -2]->minor);
                $this->yy_destructor(46, $this->yystack[$this->yyidx + 0]->minor);
                break;
            case 60:
                phql_ret_assoc_name(
                    $yygotominor,
                    $this->yystack[$this->yyidx + -5]->minor,
                    $this->yystack[$this->yyidx + -4]->minor,
                    $this->yystack[$this->yyidx + -1]->minor
                );
                $this->yy_destructor(50, $this->yystack[$this->yyidx + -3]->minor);
                $this->yy_destructor(45, $this->yystack[$this->yyidx + -2]->minor);
                $this->yy_destructor(46, $this->yystack[$this->yyidx + 0]->minor);
                break;
            case 61:
                phql_ret_assoc_name(
                    $yygotominor,
                    $this->yystack[$this->yyidx + -3]->minor,
                    $this->yystack[$this->yyidx + -2]->minor,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                $this->yy_destructor(50, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 62:
                phql_ret_assoc_name(
                    $yygotominor,
                    $this->yystack[$this->yyidx + -4]->minor,
                    null,
                    $this->yystack[$this->yyidx + -1]->minor
                );
                $this->yy_destructor(50, $this->yystack[$this->yyidx + -3]->minor);
                $this->yy_destructor(45, $this->yystack[$this->yyidx + -2]->minor);
                $this->yy_destructor(46, $this->yystack[$this->yyidx + 0]->minor);
                break;
            case 63:
                phql_ret_assoc_name(
                    $yygotominor,
                    $this->yystack[$this->yyidx + -2]->minor,
                    null,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                $this->yy_destructor(50, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 68:
                $yygotominor = $this->yystack[$this->yyidx + 0]->minor;
                $this->yy_destructor(51, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 70:
                $yygotominor = $this->yystack[$this->yyidx + 0]->minor;
                $this->yy_destructor(52, $this->yystack[$this->yyidx + -2]->minor);
                $this->yy_destructor(53, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 74:
                phql_ret_order_item($yygotominor, $this->yystack[$this->yyidx + 0]->minor, 0);
                break;
            case 75:
                phql_ret_order_item($yygotominor, $this->yystack[$this->yyidx + -1]->minor, Opcode::PHQL_T_ASC);
                $this->yy_destructor(54, $this->yystack[$this->yyidx + 0]->minor);
                break;
            case 76:
                phql_ret_order_item($yygotominor, $this->yystack[$this->yyidx + -1]->minor, Opcode::PHQL_T_DESC);
                $this->yy_destructor(55, $this->yystack[$this->yyidx + 0]->minor);
                break;
            case 77:
                $yygotominor = $this->yystack[$this->yyidx + 0]->minor;
                $this->yy_destructor(56, $this->yystack[$this->yyidx + -2]->minor);
                $this->yy_destructor(53, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 82:
                $yygotominor = $this->yystack[$this->yyidx + 0]->minor;
                $this->yy_destructor(57, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 84:
                $yygotominor = true;
                $this->yy_destructor(58, $this->yystack[$this->yyidx + -1]->minor);
                $this->yy_destructor(47, $this->yystack[$this->yyidx + 0]->minor);
                break;
            case 86:
            case 90:
            phql_ret_limit_clause($yygotominor, $this->yystack[$this->yyidx + 0]->minor, null);
            $this->yy_destructor(59, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 87:
                phql_ret_limit_clause(
                    $yygotominor,
                    $this->yystack[$this->yyidx + 0]->minor,
                    $this->yystack[$this->yyidx + -2]->minor
                );
                $this->yy_destructor(59, $this->yystack[$this->yyidx + -3]->minor);
                $this->yy_destructor(26, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 88:
                phql_ret_limit_clause(
                    $yygotominor,
                    $this->yystack[$this->yyidx + -2]->minor,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                $this->yy_destructor(59, $this->yystack[$this->yyidx + -3]->minor);
                $this->yy_destructor(60, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 92:
            case 150:
            phql_ret_literal_zval(
                $yygotominor,
                Opcode::PHQL_T_INTEGER,
                $this->yystack[$this->yyidx + 0]->minor
            );
                break;
            case 93:
            case 151:
                phql_ret_literal_zval(
                    $yygotominor,
                    Opcode::PHQL_T_HINTEGER,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                break;
            case 94:
            case 157:
                phql_ret_placeholder_zval(
                    $yygotominor,
                    Opcode::PHQL_T_NPLACEHOLDER,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                break;
            case 95:
            case 158:
#line 648 "c/parser.php.lemon"
                {
                    phql_ret_placeholder_zval(
                        $yygotominor,
                        Opcode::PHQL_T_SPLACEHOLDER,
                        $this->yystack[$this->yyidx + 0]->minor
                    );
                }
#line 1695 "c/parser.php.php"
                break;
            case 96:
            case 159:
#line 652 "c/parser.php.lemon"
                {
                    phql_ret_placeholder_zval(
                        $yygotominor,
                        Opcode::PHQL_T_BPLACEHOLDER,
                        $this->yystack[$this->yyidx + 0]->minor
                    );
                }
#line 1703 "c/parser.php.php"
                break;
            case 97:
#line 660 "c/parser.php.lemon"
                {
                    phql_ret_expr($yygotominor, Opcode::PHQL_T_MINUS, null, $this->yystack[$this->yyidx + 0]->minor);
                    $this->yy_destructor(21, $this->yystack[$this->yyidx + -1]->minor);
                }
#line 1711 "c/parser.php.php"
                break;
            case 98:
#line 664 "c/parser.php.lemon"
                {
                    phql_ret_expr(
                        $yygotominor,
                        Opcode::PHQL_T_SUB,
                        $this->yystack[$this->yyidx + -2]->minor,
                        $this->yystack[$this->yyidx + 0]->minor
                    );
                    $this->yy_destructor(21, $this->yystack[$this->yyidx + -1]->minor);
                }
#line 1719 "c/parser.php.php"
                break;
            case 99:
#line 668 "c/parser.php.lemon"
                {
                    phql_ret_expr(
                        $yygotominor,
                        Opcode::PHQL_T_ADD,
                        $this->yystack[$this->yyidx + -2]->minor,
                        $this->yystack[$this->yyidx + 0]->minor
                    );
                    $this->yy_destructor(20, $this->yystack[$this->yyidx + -1]->minor);
                }
#line 1727 "c/parser.php.php"
                break;
            case 100:
#line 672 "c/parser.php.lemon"
                {
                    phql_ret_expr(
                        $yygotominor,
                        Opcode::PHQL_T_MUL,
                        $this->yystack[$this->yyidx + -2]->minor,
                        $this->yystack[$this->yyidx + 0]->minor
                    );
                    $this->yy_destructor(18, $this->yystack[$this->yyidx + -1]->minor);
                }
#line 1735 "c/parser.php.php"
                break;
            case 101:
#line 676 "c/parser.php.lemon"
                {
                    phql_ret_expr(
                        $yygotominor,
                        Opcode::PHQL_T_DIV,
                        $this->yystack[$this->yyidx + -2]->minor,
                        $this->yystack[$this->yyidx + 0]->minor
                    );
                    $this->yy_destructor(17, $this->yystack[$this->yyidx + -1]->minor);
                }
#line 1743 "c/parser.php.php"
                break;
            case 102:
#line 680 "c/parser.php.lemon"
                {
                    phql_ret_expr(
                        $yygotominor,
                        Opcode::PHQL_T_MOD,
                        $this->yystack[$this->yyidx + -2]->minor,
                        $this->yystack[$this->yyidx + 0]->minor
                    );
                    $this->yy_destructor(19, $this->yystack[$this->yyidx + -1]->minor);
                }
#line 1751 "c/parser.php.php"
                break;
            case 103:
#line 684 "c/parser.php.lemon"
                {
                    phql_ret_expr(
                        $yygotominor,
                        Opcode::PHQL_T_AND,
                        $this->yystack[$this->yyidx + -2]->minor,
                        $this->yystack[$this->yyidx + 0]->minor
                    );
                    $this->yy_destructor(10, $this->yystack[$this->yyidx + -1]->minor);
                }
#line 1759 "c/parser.php.php"
                break;
            case 104:
#line 688 "c/parser.php.lemon"
                {
                    phql_ret_expr(
                        $yygotominor,
                        Opcode::PHQL_T_OR,
                        $this->yystack[$this->yyidx + -2]->minor,
                        $this->yystack[$this->yyidx + 0]->minor
                    );
                    $this->yy_destructor(11, $this->yystack[$this->yyidx + -1]->minor);
                }
#line 1767 "c/parser.php.php"
                break;
            case 105:
#line 692 "c/parser.php.lemon"
                {
                    phql_ret_expr(
                        $yygotominor,
                        Opcode::PHQL_T_BITWISE_AND,
                        $this->yystack[$this->yyidx + -2]->minor,
                        $this->yystack[$this->yyidx + 0]->minor
                    );
                    $this->yy_destructor(14, $this->yystack[$this->yyidx + -1]->minor);
                }
#line 1775 "c/parser.php.php"
                break;
            case 106:
#line 696 "c/parser.php.lemon"
                {
                    phql_ret_expr(
                        $yygotominor,
                        Opcode::PHQL_T_BITWISE_OR,
                        $this->yystack[$this->yyidx + -2]->minor,
                        $this->yystack[$this->yyidx + 0]->minor
                    );
                    $this->yy_destructor(15, $this->yystack[$this->yyidx + -1]->minor);
                }
#line 1783 "c/parser.php.php"
                break;
            case 107:
#line 700 "c/parser.php.lemon"
                {
                    phql_ret_expr(
                        $yygotominor,
                        Opcode::PHQL_T_BITWISE_XOR,
                        $this->yystack[$this->yyidx + -2]->minor,
                        $this->yystack[$this->yyidx + 0]->minor
                    );
                    $this->yy_destructor(16, $this->yystack[$this->yyidx + -1]->minor);
                }
#line 1791 "c/parser.php.php"
                break;
            case 108:
#line 704 "c/parser.php.lemon"
                {
                    phql_ret_expr(
                        $yygotominor,
                        Opcode::PHQL_T_EQUALS,
                        $this->yystack[$this->yyidx + -2]->minor,
                        $this->yystack[$this->yyidx + 0]->minor
                    );
                    $this->yy_destructor(4, $this->yystack[$this->yyidx + -1]->minor);
                }
#line 1799 "c/parser.php.php"
                break;
            case 109:
#line 708 "c/parser.php.lemon"
                {
                    phql_ret_expr(
                        $yygotominor,
                        Opcode::PHQL_T_NOTEQUALS,
                        $this->yystack[$this->yyidx + -2]->minor,
                        $this->yystack[$this->yyidx + 0]->minor
                    );
                    $this->yy_destructor(5, $this->yystack[$this->yyidx + -1]->minor);
                }
#line 1807 "c/parser.php.php"
                break;
            case 110:
#line 712 "c/parser.php.lemon"
                {
                    phql_ret_expr(
                        $yygotominor,
                        Opcode::PHQL_T_LESS,
                        $this->yystack[$this->yyidx + -2]->minor,
                        $this->yystack[$this->yyidx + 0]->minor
                    );
                    $this->yy_destructor(6, $this->yystack[$this->yyidx + -1]->minor);
                }
#line 1815 "c/parser.php.php"
                break;
            case 111:
#line 716 "c/parser.php.lemon"
                {
                    phql_ret_expr(
                        $yygotominor,
                        Opcode::PHQL_T_GREATER,
                        $this->yystack[$this->yyidx + -2]->minor,
                        $this->yystack[$this->yyidx + 0]->minor
                    );
                    $this->yy_destructor(7, $this->yystack[$this->yyidx + -1]->minor);
                }
#line 1823 "c/parser.php.php"
                break;
            case 112:
#line 720 "c/parser.php.lemon"
                {
                    phql_ret_expr(
                        $yygotominor,
                        Opcode::PHQL_T_GREATEREQUAL,
                        $this->yystack[$this->yyidx + -2]->minor,
                        $this->yystack[$this->yyidx + 0]->minor
                    );
                    $this->yy_destructor(8, $this->yystack[$this->yyidx + -1]->minor);
                }
#line 1831 "c/parser.php.php"
                break;
            case 113:
#line 724 "c/parser.php.lemon"
                {
                    phql_ret_expr(
                        $yygotominor,
                        Opcode::PHQL_T_LESSEQUAL,
                        $this->yystack[$this->yyidx + -2]->minor,
                        $this->yystack[$this->yyidx + 0]->minor
                    );
                    $this->yy_destructor(9, $this->yystack[$this->yyidx + -1]->minor);
                }
#line 1839 "c/parser.php.php"
                break;
            case 114:
#line 728 "c/parser.php.lemon"
                {
                    phql_ret_expr(
                        $yygotominor,
                        Opcode::PHQL_T_LIKE,
                        $this->yystack[$this->yyidx + -2]->minor,
                        $this->yystack[$this->yyidx + 0]->minor
                    );
                    $this->yy_destructor(12, $this->yystack[$this->yyidx + -1]->minor);
                }
#line 1847 "c/parser.php.php"
                break;
            case 115:
#line 732 "c/parser.php.lemon"
                {
                    phql_ret_expr(
                        $yygotominor,
                        Opcode::PHQL_T_NLIKE,
                        $this->yystack[$this->yyidx + -3]->minor,
                        $this->yystack[$this->yyidx + 0]->minor
                    );
                    $this->yy_destructor(24, $this->yystack[$this->yyidx + -2]->minor);
                    $this->yy_destructor(12, $this->yystack[$this->yyidx + -1]->minor);
                }
#line 1856 "c/parser.php.php"
                break;
            case 116:
#line 736 "c/parser.php.lemon"
                {
                    phql_ret_expr(
                        $yygotominor,
                        Opcode::PHQL_T_ILIKE,
                        $this->yystack[$this->yyidx + -2]->minor,
                        $this->yystack[$this->yyidx + 0]->minor
                    );
                    $this->yy_destructor(13, $this->yystack[$this->yyidx + -1]->minor);
                }
#line 1864 "c/parser.php.php"
                break;
            case 117:
#line 740 "c/parser.php.lemon"
                {
                    phql_ret_expr(
                        $yygotominor,
                        Opcode::PHQL_T_NILIKE,
                        $this->yystack[$this->yyidx + -3]->minor,
                        $this->yystack[$this->yyidx + 0]->minor
                    );
                    $this->yy_destructor(24, $this->yystack[$this->yyidx + -2]->minor);
                    $this->yy_destructor(13, $this->yystack[$this->yyidx + -1]->minor);
                }
#line 1873 "c/parser.php.php"
                break;
            case 118:
            case 121:
#line 744 "c/parser.php.lemon"
                {
                    phql_ret_expr(
                        $yygotominor,
                        Opcode::PHQL_T_IN,
                        $this->yystack[$this->yyidx + -4]->minor,
                        $this->yystack[$this->yyidx + -1]->minor
                    );
                    $this->yy_destructor(23, $this->yystack[$this->yyidx + -3]->minor);
                    $this->yy_destructor(45, $this->yystack[$this->yyidx + -2]->minor);
                    $this->yy_destructor(46, $this->yystack[$this->yyidx + 0]->minor);
                }
#line 1884 "c/parser.php.php"
                break;
            case 119:
            case 122:
#line 748 "c/parser.php.lemon"
                {
                    phql_ret_expr(
                        $yygotominor,
                        Opcode::PHQL_T_NOTIN,
                        $this->yystack[$this->yyidx + -5]->minor,
                        $this->yystack[$this->yyidx + -1]->minor
                    );
                    $this->yy_destructor(24, $this->yystack[$this->yyidx + -4]->minor);
                    $this->yy_destructor(23, $this->yystack[$this->yyidx + -3]->minor);
                    $this->yy_destructor(45, $this->yystack[$this->yyidx + -2]->minor);
                    $this->yy_destructor(46, $this->yystack[$this->yyidx + 0]->minor);
                }
#line 1896 "c/parser.php.php"
                break;
            case 120:
#line 752 "c/parser.php.lemon"
                {
                    phql_ret_expr(
                        $yygotominor,
                        Opcode::PHQL_T_SUBQUERY,
                        $this->yystack[$this->yyidx + -1]->minor,
                        null
                    );
                    $this->yy_destructor(45, $this->yystack[$this->yyidx + -2]->minor);
                    $this->yy_destructor(46, $this->yystack[$this->yyidx + 0]->minor);
                }
#line 1905 "c/parser.php.php"
                break;
            case 123:
#line 764 "c/parser.php.lemon"
                {
                    phql_ret_expr($yygotominor, Opcode::PHQL_T_EXISTS, null, $this->yystack[$this->yyidx + -1]->minor);
                    $this->yy_destructor(66, $this->yystack[$this->yyidx + -3]->minor);
                    $this->yy_destructor(45, $this->yystack[$this->yyidx + -2]->minor);
                    $this->yy_destructor(46, $this->yystack[$this->yyidx + 0]->minor);
                }
#line 1915 "c/parser.php.php"
                break;
            case 124:
#line 768 "c/parser.php.lemon"
                {
                    phql_ret_expr(
                        $yygotominor,
                        Opcode::PHQL_T_AGAINST,
                        $this->yystack[$this->yyidx + -2]->minor,
                        $this->yystack[$this->yyidx + 0]->minor
                    );
                    $this->yy_destructor(1, $this->yystack[$this->yyidx + -1]->minor);
                }
#line 1923 "c/parser.php.php"
                break;
            case 125:
#line 772 "c/parser.php.lemon"
                {
                    {
                        $qualified = null;
                        phql_ret_raw_qualified_name($qualified, $this->yystack[$this->yyidx + -1]->minor, null);
                        phql_ret_expr(
                            $yygotominor,
                            Opcode::PHQL_T_CAST,
                            $this->yystack[$this->yyidx + -3]->minor,
                            $qualified
                        );
                    }
                    $this->yy_destructor(67, $this->yystack[$this->yyidx + -5]->minor);
                    $this->yy_destructor(45, $this->yystack[$this->yyidx + -4]->minor);
                    $this->yy_destructor(33, $this->yystack[$this->yyidx + -2]->minor);
                    $this->yy_destructor(46, $this->yystack[$this->yyidx + 0]->minor);
                }
#line 1938 "c/parser.php.php"
                break;
            case 126:
#line 780 "c/parser.php.lemon"
                {
                    {
                        $qualified = null;
                        phql_ret_raw_qualified_name($qualified, $this->yystack[$this->yyidx + -1]->minor, null);
                        phql_ret_expr(
                            $yygotominor,
                            Opcode::PHQL_T_CONVERT,
                            $this->yystack[$this->yyidx + -3]->minor,
                            $qualified
                        );
                    }
                    $this->yy_destructor(68, $this->yystack[$this->yyidx + -5]->minor);
                    $this->yy_destructor(45, $this->yystack[$this->yyidx + -4]->minor);
                    $this->yy_destructor(69, $this->yystack[$this->yyidx + -2]->minor);
                    $this->yy_destructor(46, $this->yystack[$this->yyidx + 0]->minor);
                }
#line 1953 "c/parser.php.php"
                break;
            case 127:
#line 788 "c/parser.php.lemon"
                {
                    phql_ret_expr(
                        $yygotominor,
                        Opcode::PHQL_T_CASE,
                        $this->yystack[$this->yyidx + -2]->minor,
                        $this->yystack[$this->yyidx + -1]->minor
                    );
                    $this->yy_destructor(70, $this->yystack[$this->yyidx + -3]->minor);
                    $this->yy_destructor(71, $this->yystack[$this->yyidx + 0]->minor);
                }
#line 1962 "c/parser.php.php"
                break;
            case 130:
#line 800 "c/parser.php.lemon"
                {
                    phql_ret_expr(
                        $yygotominor,
                        Opcode::PHQL_T_WHEN,
                        $this->yystack[$this->yyidx + -2]->minor,
                        $this->yystack[$this->yyidx + 0]->minor
                    );
                    $this->yy_destructor(72, $this->yystack[$this->yyidx + -3]->minor);
                    $this->yy_destructor(73, $this->yystack[$this->yyidx + -1]->minor);
                }
#line 1971 "c/parser.php.php"
                break;
            case 131:
#line 804 "c/parser.php.lemon"
                {
                    phql_ret_expr($yygotominor, Opcode::PHQL_T_ELSE, $this->yystack[$this->yyidx + 0]->minor, null);
                    $this->yy_destructor(74, $this->yystack[$this->yyidx + -1]->minor);
                }
#line 1979 "c/parser.php.php"
                break;
            case 133:
                phql_ret_func_call(
                    $yygotominor,
                    $this->yystack[$this->yyidx + -4]->minor,
                    $this->yystack[$this->yyidx + -1]->minor,
                    $this->yystack[$this->yyidx + -2]->minor
                );
                $this->yy_destructor(45, $this->yystack[$this->yyidx + -3]->minor);
                $this->yy_destructor(46, $this->yystack[$this->yyidx + 0]->minor);
                break;
            case 134:
                $yygotominor = 0;
                $this->yy_destructor(29, $this->yystack[$this->yyidx + 0]->minor);
                break;
            case 142:
                phql_ret_expr($yygotominor, Opcode::PHQL_T_ISNULL, $this->yystack[$this->yyidx + -2]->minor, null);
                $this->yy_destructor(22, $this->yystack[$this->yyidx + -1]->minor);
                $this->yy_destructor(75, $this->yystack[$this->yyidx + 0]->minor);
                break;
            case 143:
                phql_ret_expr(
                    $yygotominor,
                    Opcode::PHQL_T_ISNOTNULL,
                    $this->yystack[$this->yyidx + -3]->minor,
                    null
                );
                $this->yy_destructor(22, $this->yystack[$this->yyidx + -2]->minor);
                $this->yy_destructor(24, $this->yystack[$this->yyidx + -1]->minor);
                $this->yy_destructor(75, $this->yystack[$this->yyidx + 0]->minor);
                break;
            case 144:
                phql_ret_expr(
                    $yygotominor,
                    Opcode::PHQL_T_BETWEEN,
                    $this->yystack[$this->yyidx + -2]->minor,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                $this->yy_destructor(2, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 145:
                phql_ret_expr(
                    $yygotominor,
                    Opcode::PHQL_T_BETWEEN_NOT,
                    $this->yystack[$this->yyidx + -2]->minor,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                $this->yy_destructor(3, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 146:
                phql_ret_expr($yygotominor, Opcode::PHQL_T_NOT, null, $this->yystack[$this->yyidx + 0]->minor);
                $this->yy_destructor(24, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 147:
                phql_ret_expr(
                    $yygotominor,
                    Opcode::PHQL_T_BITWISE_NOT,
                    null,
                    $this->yystack[$this->yyidx + 0]->minor
                );
                $this->yy_destructor(25, $this->yystack[$this->yyidx + -1]->minor);
                break;
            case 148:
                phql_ret_expr(
                    $yygotominor,
                    Opcode::PHQL_T_ENCLOSED,
                    $this->yystack[$this->yyidx + -1]->minor,
                    null
                );
                $this->yy_destructor(45, $this->yystack[$this->yyidx + -2]->minor);
                $this->yy_destructor(46, $this->yystack[$this->yyidx + 0]->minor);
                break;
            case 152:
                phql_ret_literal_zval($yygotominor, Opcode::PHQL_T_STRING, $this->yystack[$this->yyidx + 0]->minor);
                break;
            case 153:
#line 912 "c/parser.php.lemon"
                {
                    phql_ret_literal_zval($yygotominor, Opcode::PHQL_T_DOUBLE, $this->yystack[$this->yyidx + 0]->minor);
                }
#line 2070 "c/parser.php.php"
                break;
            case 154:
#line 916 "c/parser.php.lemon"
                {
                    phql_ret_literal_zval($yygotominor, Opcode::PHQL_T_NULL, null);
                    $this->yy_destructor(75, $this->yystack[$this->yyidx + 0]->minor);
                }
#line 2078 "c/parser.php.php"
                break;
            case 155:
#line 920 "c/parser.php.lemon"
                {
                    phql_ret_literal_zval($yygotominor, Opcode::PHQL_T_TRUE, null);
                    $this->yy_destructor(78, $this->yystack[$this->yyidx + 0]->minor);
                }
#line 2086 "c/parser.php.php"
                break;
            case 156:
#line 924 "c/parser.php.lemon"
                {
                    phql_ret_literal_zval($yygotominor, Opcode::PHQL_T_FALSE, null);
                    $this->yy_destructor(79, $this->yystack[$this->yyidx + 0]->minor);
                }
#line 2094 "c/parser.php.php"
                break;
            case 160:
#line 947 "c/parser.php.lemon"
                {
                    phql_ret_qualified_name(
                        $yygotominor,
                        null,
                        $this->yystack[$this->yyidx + -2]->minor,
                        $this->yystack[$this->yyidx + 0]->minor
                    );
                    $this->yy_destructor(32, $this->yystack[$this->yyidx + -1]->minor);
                }
#line 2102 "c/parser.php.php"
                break;
        }
        $yygoto = self::$yyRuleInfo[2 * $yyruleno];
        $yysize = self::$yyRuleInfo[(2 * $yyruleno) + 1];

        $state_for_reduce = $this->yystack[$this->yyidx - $yysize]->stateno;

        $this->yyidx -= $yysize;
        $yyact       = $this->yy_find_reduce_action($state_for_reduce, $yygoto);
        if ($yyact < self::YYNSTATE) {
            $this->yy_shift($yyact, $yygoto, $yygotominor);
        } else {
            if ($yyact == self::YYNSTATE + self::YYNRULE + 1) {
                $this->yy_accept();
            }
        }
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
            $yytos                       = new phql_yyStackEntry;
            $this->yystack[$this->yyidx] = $yytos;
        }
        $yytos->stateno = $yyNewState;
        $yytos->major   = $yyMajor;
        $yytos->minor   = $yypMinor;
        if ($this->yyTraceFILE) {
            fprintf($this->yyTraceFILE, "%sShift %d\n", $this->yyTracePrompt, $yyNewState);
            fprintf($this->yyTraceFILE, "%sStack:", $this->yyTracePrompt);
            for ($i = 1; $i <= $this->yyidx; $i++) {
                $ent = $this->yystack[$i];
                fprintf($this->yyTraceFILE, " %s", self::$yyTokenName[$ent->major]);
            }
            fprintf($this->yyTraceFILE, "\n");
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
                if (in_array($active_token, $tokens)) {
                    $token_name = array_search($active_token, $tokens);
                }
            }

			if (!$token_name) {
                $token_name = "UNKNOWN";
            }

			if ($near_length > 0) {
                if ($this->status->getToken()->value) {
                    $this->status->setSyntaxError(sprintf(
                        "Syntax error, unexpected token %s(%s), near to '%s', when parsing: %s (%d)",
                        $token_name,
                        $this->status->getToken()->value,
                        $this->status->getState()->getStart(),
                        $this->status->phql,
                        $this->status->phql_length
                    ));
                } else {
                    $this->status->setSyntaxError(sprintf(
                        "Syntax error, unexpected token %s, near to '%s', when parsing: %s (%d)",
                        $token_name,
                        $this->status->getState()->getStart(),
                        $this->status->phql,
                        $this->status->phql_length
                    ));
                }
            } else {
                if ($active_token != Opcode::PHQL_T_IGNORE) {
                    if ($this->status->getToken()->value) {
                        $this->status->setSyntaxError(sprintf(
                            "Syntax error, unexpected token %s(%s), at the end of query, when parsing: %s (%d)",
                            $token_name,
                            $this->status->getToken()->value,
                            $this->status->phql,
                            $this->status->phql_length
                        ));
                    } else {
                        $this->status->setSyntaxError(sprintf(
                            "Syntax error, unexpected token %s, at the end of query, when parsing: %s (%d)",
                            $token_name,
                            $this->status->phql,
                            $this->status->phql_length
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
        "type"          => Opcode::PHQL_T_INSERT,
        "qualifiedName" => $Q,
        "values"        => $V,
    ];

    if ($F !== null) {
        $ret["fields"] = $F;
    }
}

function phql_ret_select_statement(&$ret, $S, $W, $O, $G, $H, $L, $F): void
{
    $ret = [
        "type"   => Opcode::PHQL_T_SELECT,
        "select" => $S,
    ];

    if ($W !== null) {
        $ret["where"] = $W;
    }
    if ($O !== null) {
        $ret["orderBy"] = $O;
    }
    if ($G !== null) {
        $ret["groupBy"] = $G;
    }
    if ($H !== null) {
        $ret["having"] = $H;
    }
    if ($L !== null) {
        $ret["limit"] = $L;
    }
    if ($F !== null) {
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
    $ret["tables"]  = $tables;

    if ($join_list !== null) {
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

function phql_ret_literal_zval(int $type, $T = null): array
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
    array &$ret,
    int   $type,
          $column = null,
    ?string $identifierColumn = null,
    ?string $alias = null
): array {
    $ret = [];
    $ret['type'] = $type;

    if ($column !== null) {
        $ret['column'] = $column;
    }

    if ($identifierColumn !== null) {
        $ret['column'] = $identifierColumn;
    }

    if ($alias !== null) {
        $ret['alias'] = $alias;
    }

    return $ret;
}

function phql_ret_join_item(
    array &$ret,
          $type,
          $qualified = null,
          $alias = null,
          $conditions = null
): void {
    /* array_init(ret); */
    $ret = [];

    /* add_assoc_zval(ret, "type", type); */
    $ret['type'] = $type;

    /* if (qualified && Z_TYPE_P(qualified) != IS_UNDEF) */
    if ($qualified !== null) {
        $ret['qualified'] = $qualified;
    }

    /* if (alias && Z_TYPE_P(alias) != IS_UNDEF) */
    if ($alias !== null) {
        $ret['alias'] = $alias;
    }

    /* if (conditions && Z_TYPE_P(conditions) != IS_UNDEF) */
    if ($conditions !== null) {
        $ret['conditions'] = $conditions;
    }
}

function phql_ret_qualified_name(
    array  &$ret,
    ?string $nsAlias,
    ?string $domain,
    string  $name
): void {
    /* array_init(ret); */
    $ret = [];

    /* add_assoc_long(ret, "type", PHQL_T_QUALIFIED); */
    $ret['type'] = defined('PHQL_T_QUALIFIED') ? PHQL_T_QUALIFIED : 0;

    /* if (A) phql_add_assoc_stringl(..., "ns-alias", ...) */
    if ($nsAlias !== null) {
        $ret['ns-alias'] = $nsAlias;
    }

    /* if (B) phql_add_assoc_stringl(..., "domain", ...) */
    if ($domain !== null) {
        $ret['domain'] = $domain;
    }

    /* phql_add_assoc_stringl(..., "name", C...) – always present */
    $ret['name'] = $name;
}

function phql_ret_update_statement(
    array &$ret,
          $update,
          $where = null,
          $limit = null
): void {
    /* array_init(ret); */
    $ret = [];

    /* add_assoc_long(ret, "type", PHQL_T_UPDATE); */
    $ret['type'] = defined('PHQL_T_UPDATE') ? PHQL_T_UPDATE : 0;

    /* add_assoc_zval(ret, "update", U); */
    $ret['update'] = $update;

    /* if (W && Z_TYPE_P(W) != IS_UNDEF) */
    if ($where !== null) {
        $ret['where'] = $where;
    }

    /* if (L && Z_TYPE_P(L) != IS_UNDEF) */
    if ($limit !== null) {
        $ret['limit'] = $limit;
    }
}

function phql_ret_update_clause(
    array &$ret,
          $tables,
          $values
): void {
    /* array_init(ret); */
    $ret = [];

    /* add_assoc_zval(ret, "tables", tables); */
    $ret['tables'] = $tables;

    /* add_assoc_zval(ret, "values", values); */
    $ret['values'] = $values;
}

function phql_ret_update_item(
    array &$ret,
          $column,
          $expr
): void {
    /* array_init(ret); */
    $ret = [];

    /* add_assoc_zval(ret, "column", column); */
    $ret['column'] = $column;

    /* add_assoc_zval(ret, "expr", expr); */
    $ret['expr'] = $expr;
}

function phql_ret_delete_statement(
    array &$ret,
          $delete,
          $where = null,
          $limit = null
): void {
    /* array_init(ret); */
    $ret = [];

    /* add_assoc_long(ret, "type", PHQL_T_DELETE); */
    $ret['type'] = defined('PHQL_T_DELETE') ? PHQL_T_DELETE : 0;

    /* add_assoc_zval(ret, "delete", D); */
    $ret['delete'] = $delete;

    /* if (W && Z_TYPE_P(W) != IS_UNDEF) */
    if ($where !== null) {
        $ret['where'] = $where;
    }

    /* if (L && Z_TYPE_P(L) != IS_UNDEF) */
    if ($limit !== null) {
        $ret['limit'] = $limit;
    }
}

function phql_ret_delete_clause(
    array &$ret,
          $tables
): void {
    /* array_init(ret); */
    $ret = [];

    /* add_assoc_zval(ret, "tables", tables); */
    $ret['tables'] = $tables;
}

function phql_ret_assoc_name(
    array  &$ret,
           $qualifiedName,
    ?string $alias = null,
    $with = null
): void {
    /* array_init(ret); */
    $ret = [];

    /* add_assoc_zval(ret, "qualifiedName", qualified_name); */
    $ret['qualifiedName'] = $qualifiedName;

    /* if (alias) phql_add_assoc_stringl(..., "alias", ...) */
    if ($alias !== null) {
        $ret['alias'] = $alias;
    }

    /* if (with && Z_TYPE_P(with) != IS_UNDEF) */
    if ($with !== null) {
        $ret['with'] = $with;
    }
}

function phql_ret_order_item(
    array &$ret,
          $column,
    int   $sort = 0
): void {
    /* array_init(ret); */
    $ret = [];

    /* add_assoc_zval(ret, "column", column); */
    $ret['column'] = $column;

    /* if (sort) add_assoc_long(ret, "sort", sort); */
    if ($sort !== 0) {
        $ret['sort'] = $sort;
    }
}

function phql_ret_limit_clause(
    array &$ret,
          $limit,
          $offset = null
): void {
    /* array_init(ret); */
    $ret = [];

    /* add_assoc_zval(ret, "number", L); */
    $ret['number'] = $limit;

    /* if (O && Z_TYPE_P(O) != IS_UNDEF) */
    if ($offset !== null) {
        $ret['offset'] = $offset;
    }
}

function phql_ret_placeholder_zval(
    array  &$ret,
    int     $type,
    string  $value
): void {
    /* array_init(ret); */
    $ret = [];

    /* add_assoc_long(ret, "type", type); */
    $ret['type'] = $type;

    /* phql_add_assoc_stringl(ret, "value", T->token, ...) */
    $ret['value'] = $value;
}

function phql_ret_raw_qualified_name(
    array  &$ret,
    string  $tokenA,
    ?string $tokenB = null
): void {
    /* array_init(ret); */
    $ret = [];

    /* add_assoc_long(ret, "type", PHQL_T_RAW_QUALIFIED); */
    $ret['type'] = defined('PHQL_T_RAW_QUALIFIED') ? PHQL_T_RAW_QUALIFIED : 0;

    if ($tokenB !== null) {
        /* Two-part qualified name: domain + name */
        $ret['domain'] = $tokenA;  // equivalent to phql_add_assoc_stringl(..., "domain", A->token, ...)
        $ret['name']   = $tokenB;  // equivalent to phql_add_assoc_stringl(..., "name",   B->token, ...)
    } else {
        /* Single-part name */
        $ret['name'] = $tokenA;
    }
}

function phql_ret_func_call(
    array  &$ret,
    string  $name,
    $arguments = null,
    $distinct  = null
): void {
    /* array_init(ret); */
    $ret = [];

    /* add_assoc_long(ret, "type", PHQL_T_FCALL); */
    $ret['type'] = defined('PHQL_T_FCALL') ? PHQL_T_FCALL : 0;

    /* phql_add_assoc_stringl(ret, "name", name->token, ...) */
    $ret['name'] = $name;

    /* if (arguments && Z_TYPE_P(arguments) != IS_UNDEF) */
    if ($arguments !== null) {
        $ret['arguments'] = $arguments;
    }

    /* if (distinct && Z_TYPE_P(distinct) != IS_UNDEF) */
    if ($distinct !== null) {
        $ret['distinct'] = $distinct;
    }
}

