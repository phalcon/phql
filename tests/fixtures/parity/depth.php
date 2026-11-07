<?php

/**
 * Nesting depth limits. cphalcon's Lemon parser has a 100-entry stack and
 * fails with "Parsing failed, the PHQL statement is nested too deeply" when
 * a shift (or an empty-rule reduction) would make it overflow. Each construct
 * below is probed at N-2 .. N+3 around the first depth N that overflows in
 * ext-phalcon 5.23.0 (measured with a bisection probe while planning), so the
 * corpus pins the exact boundary. Left-associative chains never grow the
 * stack and are probed at large sizes instead.
 */

declare(strict_types=1);

$r = static fn (string $s, int $n): string => str_repeat($s, $n);

$subquery = static function (int $n): string {
    $s = '1';
    for ($i = 0; $i < $n; $i++) {
        $s = "(SELECT {$s} FROM r)";
    }

    return 'SELECT ' . $s . ' FROM r';
};

$exists = static function (int $n): string {
    $s = 'SELECT * FROM r';
    for ($i = 0; $i < $n; $i++) {
        $s = "SELECT * FROM r WHERE EXISTS ({$s})";
    }

    return $s;
};

$inSubquery = static function (int $n): string {
    $s = 'SELECT a FROM r';
    for ($i = 0; $i < $n; $i++) {
        $s = "SELECT a FROM r WHERE a IN ({$s})";
    }

    return $s;
};

/** @var array<string, array{int, callable(int): string}> $constructs name => [first overflowing N, builder] */
$constructs = [
    'parens-column'    => [96, static fn (int $n): string => 'SELECT ' . $r('(', $n) . '1' . $r(')', $n) . ' FROM r'],
    'parens-where'     => [96, static fn (int $n): string => 'SELECT * FROM r WHERE ' . $r('(', $n) . 'a' . $r(')', $n)],
    'parens-order'     => [92, static fn (int $n): string => 'SELECT * FROM r ORDER BY ' . $r('(', $n) . 'a' . $r(')', $n)],
    'parens-update'    => [92, static fn (int $n): string => 'UPDATE r SET a = ' . $r('(', $n) . '1' . $r(')', $n)],
    'parens-insert'    => [93, static fn (int $n): string => 'INSERT INTO r VALUES (' . $r('(', $n) . '1' . $r(')', $n) . ')'],
    'parens-join-on'   => [89, static fn (int $n): string => 'SELECT * FROM r JOIN s ON ' . $r('(', $n) . 'a' . $r(')', $n)],
    'subquery-column'  => [31, $subquery],
    'exists'           => [24, $exists],
    'in-subquery'      => [19, $inSubquery],
    'fcall'            => [32, static fn (int $n): string => 'SELECT ' . $r('f(', $n) . '1' . $r(')', $n) . ' FROM r'],
    'fcall-distinct'   => [32, static fn (int $n): string => 'SELECT ' . $r('f(DISTINCT ', $n) . '1' . $r(')', $n) . ' FROM r'],
    'cast'             => [47, static fn (int $n): string => 'SELECT ' . $r('CAST(', $n) . 'a' . $r(' AS INT)', $n) . ' FROM r'],
    'convert'          => [47, static fn (int $n): string => 'SELECT ' . $r('CONVERT(', $n) . 'a' . $r(' USING utf8)', $n) . ' FROM r'],
    'case'             => [93, static fn (int $n): string => 'SELECT ' . $r('CASE ', $n) . 'a' . $r(' WHEN 1 THEN 2 END', $n) . ' FROM r'],
    'case-then'        => [20, static fn (int $n): string => 'SELECT ' . $r('CASE a WHEN 1 THEN ', $n) . '2' . $r(' END', $n) . ' FROM r'],
    'not-chain'        => [97, static fn (int $n): string => 'SELECT * FROM r WHERE ' . $r('NOT ', $n) . 'a'],
    'minus-chain'      => [97, static fn (int $n): string => 'SELECT ' . $r('- ', $n) . 'a FROM r'],
    'bitnot-chain'     => [97, static fn (int $n): string => 'SELECT ' . $r('~ ', $n) . 'a FROM r'],
    'against-chain'    => [49, static fn (int $n): string => 'SELECT * FROM r WHERE a' . $r(' AGAINST a', $n)],
    'in-nested-args'   => [32, static fn (int $n): string => 'SELECT * FROM r WHERE ' . $r('a IN (', $n) . '1' . $r(')', $n)],
    'plus-right-paren' => [32, static fn (int $n): string => 'SELECT ' . $r('a + (', $n) . 'a' . $r(')', $n) . ' FROM r'],
    'notlike-chain'    => [33, static fn (int $n): string => 'SELECT * FROM r WHERE a' . $r(' NOT LIKE a', $n)],
    'parens-comma'     => [98, static fn (int $n): string => 'SELECT ' . $r('(', $n) . ','],
    'parens-from'      => [98, static fn (int $n): string => 'SELECT ' . $r('(', $n) . 'FROM'],
    'parens-bracket'   => [96, static fn (int $n): string => 'SELECT ' . $r('(', $n) . '1' . $r(']', $n) . ' FROM r'],
    'parens-unclosed'  => [96, static fn (int $n): string => 'SELECT ' . $r('(', $n) . '1 FROM r'],
];

$cases = [];

foreach ($constructs as $name => [$first, $build]) {
    for ($n = $first - 2; $n <= $first + 3; $n++) {
        $cases["{$name} {$n}"] = $build($n);
    }
}

$chains = [
    'and-chain'     => static fn (int $n): string => 'SELECT * FROM r WHERE a' . $r(' AND a', $n),
    'eq-chain'      => static fn (int $n): string => 'SELECT * FROM r WHERE ' . $r('a = ', $n) . '1',
    'like-chain'    => static fn (int $n): string => 'SELECT * FROM r WHERE a' . $r(' LIKE a', $n),
    'between-chain' => static fn (int $n): string => 'SELECT * FROM r WHERE a' . $r(' BETWEEN a', $n),
    'is-null-chain' => static fn (int $n): string => 'SELECT * FROM r WHERE a' . $r(' IS NULL', $n),
    'json-chain'    => static fn (int $n): string => 'SELECT a' . $r('->a', $n) . ' FROM r',
    'column-list'   => static fn (int $n): string => 'SELECT a' . $r(', a', $n) . ' FROM r',
    'join-list'     => static fn (int $n): string => 'SELECT * FROM r' . $r(' JOIN s', $n),
    'when-list'     => static fn (int $n): string => 'SELECT CASE a' . $r(' WHEN 1 THEN 2', $n) . ' END FROM r',
    'arg-list'      => static fn (int $n): string => 'SELECT f(a' . $r(', a', $n) . ') FROM r',
];

foreach ($chains as $name => $build) {
    foreach ([150, 400] as $n) {
        $cases["{$name} {$n}"] = $build($n);
    }
}

return $cases;
