<?php

/**
 * Operator precedence matrix: every ordered pair of representative binary
 * operators (one or two per Lemon precedence level, plus every NOT-prefixed
 * form), each prefix operator against each binary operator on both sides,
 * and the postfix forms (IS [NOT] NULL, [NOT] IN (...)) against each binary
 * operator. Every operator also appears once on its own.
 */

declare(strict_types=1);

$binary = [
    'AGAINST', 'BETWEEN', 'NOT BETWEEN', '=', '@@', 'AND', 'OR', 'LIKE', 'NOT LIKE',
    'ILIKE', 'NOT ILIKE', '&', '^', '*', '%', '+', '-', '||', '->', '#>>',
];
$every  = [
    'AGAINST', 'BETWEEN', 'NOT BETWEEN', '=', '!=', '<>', '<', '>', '>=', '<=', '@@', '@>', '<@', '&&',
    'AND', 'OR', 'LIKE', 'ILIKE', 'NOT LIKE', 'NOT ILIKE', '&', '|', '^', '/', '*', '%', '+', '-', '||',
    '->', '->>', '#>', '#>>',
];
$prefix  = ['-', 'NOT', '~'];
$postfix = ['IS NULL', 'IS NOT NULL', 'IN (1)', 'NOT IN (1)', 'IN (SELECT x FROM s)'];

$cases = [];

foreach ($every as $op) {
    $cases["single {$op}"] = "SELECT a {$op} b FROM r";
}

foreach ($binary as $first) {
    foreach ($binary as $second) {
        $cases["pair {$first} {$second}"] = "SELECT a {$first} b {$second} c FROM r";
    }
}

foreach ($prefix as $pre) {
    foreach ($binary as $op) {
        $cases["prefix {$pre} before {$op}"] = "SELECT {$pre} a {$op} b FROM r";
        $cases["prefix {$pre} after {$op}"]  = "SELECT a {$op} {$pre} b FROM r";
    }
    foreach ($prefix as $inner) {
        $cases["prefix {$pre} {$inner}"] = "SELECT {$pre} {$inner} a FROM r";
    }
}

foreach ($postfix as $post) {
    foreach ($binary as $op) {
        $cases["postfix {$post} after {$op}"]  = "SELECT a {$op} b {$post} FROM r";
        $cases["postfix {$post} before {$op}"] = "SELECT a {$post} {$op} b FROM r";
    }
    foreach ($prefix as $pre) {
        $cases["postfix {$post} after prefix {$pre}"] = "SELECT {$pre} a {$post} FROM r";
    }
    foreach ($postfix as $other) {
        $cases["postfix {$post} then {$other}"] = "SELECT a {$post} {$other} FROM r";
    }
}

$cases += [
    'where and or eq'          => 'SELECT * FROM r WHERE a = 1 AND b > 0 OR c < 2',
    'between and'              => 'SELECT * FROM r WHERE a BETWEEN 1 AND 2',
    'between and and'          => 'SELECT * FROM r WHERE a BETWEEN 1 AND 2 AND b = 3',
    'not between and'          => 'SELECT * FROM r WHERE a NOT BETWEEN 1 + 2 AND 3 * 4',
    'not as infix then eq'     => 'SELECT * FROM r WHERE a NOT LIKE b = c',
    'not without like'         => 'SELECT * FROM r WHERE a NOT b',
    'not not like'             => 'SELECT * FROM r WHERE NOT a NOT LIKE b',
    'is not null not'          => 'SELECT * FROM r WHERE NOT a IS NOT NULL',
    'paren groups'             => 'SELECT (a + b) * (c - d) FROM r',
    'nested parens'            => 'SELECT ((a)) FROM r',
    'json chain'               => 'SELECT a->b->>c#>d FROM r',
    'concat chain'             => 'SELECT a || b || c FROM r',
    'minus minus'              => 'SELECT a - - b FROM r',
    'minus chain literal'      => 'SELECT 1 - 2 - 3 FROM r',
    'div chain'                => 'SELECT 8 / 4 / 2 FROM r',
    'against chain'            => 'SELECT * FROM r WHERE a AGAINST b AGAINST c',
    'like chain'               => 'SELECT * FROM r WHERE a LIKE b LIKE c',
    'not like chain'           => 'SELECT * FROM r WHERE a NOT LIKE b NOT LIKE c',
    'in after in'              => 'SELECT * FROM r WHERE a IN (1) IN (2)',
    'case in expr'             => 'SELECT CASE a + 1 WHEN b * 2 THEN c - 3 ELSE d / 4 END + 5 FROM r',
    'fcall in expr'            => 'SELECT f(a) + g(b) * 2 FROM r',
    'cast in expr'             => 'SELECT CAST(a AS INT) + 1 FROM r',
    'exists and'               => 'SELECT * FROM r WHERE EXISTS (SELECT * FROM s) AND a = 1',
    'subquery plus'            => 'SELECT (SELECT 1 FROM s) + 1 FROM r',
    'placeholder arithmetic'   => 'SELECT :a: + ?0 * {b} FROM r',
    'order by precedence'      => 'SELECT * FROM r ORDER BY a + b DESC, c AND d',
    'having precedence'        => 'SELECT * FROM r GROUP BY a HAVING a = 1 OR b = 2 AND c = 3',
    'join on precedence'       => 'SELECT * FROM r JOIN s ON r.a = s.a AND r.b = s.b',
];

return $cases;
