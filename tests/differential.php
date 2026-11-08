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

/**
 * Compares this package against the cphalcon C implementation, live, case by
 * case over the whole parity corpus (`tests/fixtures/parity/*.php`), with
 * literals enabled and disabled.
 *
 *     php -d extension=phalcon tests/differential.php [--fuzz=N] [--seed=S] [--verbose]
 *
 * The AST (or the exception message) must be identical; any mismatch outside
 * `allow-list.php` fails the run (exit 1). Allow-listed cases are checked
 * against their recorded expectation instead, so the allow-list cannot hide a
 * regression.
 *
 * `--fuzz=N` adds N generated inputs, reproducible from `--seed`: corpus
 * entries mutated by deleting, duplicating, swapping or inserting tokens and
 * random bytes, and random nestings of every recursive construct (depth 1 to
 * 120, across the 100-entry stack limit), some with arbitrary tokens as the
 * innermost operand or cut short, so that invalid input meets the limit.
 *
 * Requires ext-phalcon; exits 0 with a notice otherwise so it is harmless in a
 * pipeline that does not always have the extension (CI asserts it is loaded).
 */

require __DIR__ . '/../vendor/autoload.php';

use Phalcon\Mvc\Model\Query\Lang;
use Phalcon\Phql\Parser as PhpParser;
use Phalcon\Phql\Tests\Support\Parity;

if (!extension_loaded('phalcon') || !class_exists(Lang::class)) {
    fwrite(STDERR, "ext-phalcon is not loaded; nothing to compare against.\n");

    exit(0);
}

ini_set('phalcon.orm.cache_level', '-1');

$options = getopt('', ['fuzz::', 'seed::', 'verbose']);
$fuzz    = (int) ($options['fuzz'] ?? 0);
$seed    = (int) ($options['seed'] ?? 1);
$verbose = isset($options['verbose']);

$allowList  = Parity::allowList();
$checked    = 0;
$mismatches = 0;
$allowed    = 0;

$cParse = static function (string $phql, bool $literals): array {
    ini_set('phalcon.orm.enable_literals', $literals ? '1' : '0');

    return Parity::jsonSafe(Parity::comparable(Parity::run(static fn (string $p): mixed => Lang::parsePHQL($p), $phql)));
};

$phpParse = static fn (string $phql, bool $literals): array => Parity::jsonSafe(Parity::comparable(Parity::run(
    static fn (string $p): mixed => (new PhpParser())->setEnableLiterals($literals)->parse($p),
    $phql
)));

$show = static function (string $key, string $phql, mixed $c, mixed $php) use ($verbose): void {
    echo "MISMATCH {$key}\n";
    if (!$verbose) {
        return;
    }

    $encode = static fn (mixed $v): string => (string) json_encode(
        Parity::jsonSafe($v),
        JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
        Parity::JSON_DEPTH
    );
    echo '  input : ' . $encode($phql) . "\n";
    echo '  C     : ' . $encode($c) . "\n";
    echo '  PHP   : ' . $encode($php) . "\n\n";
};

$compare = static function (string $key, string $phql) use (
    $allowList,
    $cParse,
    $phpParse,
    $show,
    &$checked,
    &$mismatches,
    &$allowed
): void {
    foreach (Parity::MODES as $mode => $literals) {
        $checked++;
        $modeKey = $key . '/' . $mode;
        $php     = $phpParse($phql, $literals);

        if (isset($allowList[$modeKey])) {
            $allowed++;
            $expected = Parity::jsonSafe(Parity::comparable($allowList[$modeKey]));
            if ($php !== $expected) {
                $mismatches++;
                $show('[allow-list] ' . $modeKey, $phql, $expected, $php);
            }

            continue;
        }

        $c = $cParse($phql, $literals);
        if ($c !== $php) {
            $mismatches++;
            $show($modeKey, $phql, $c, $php);
        }
    }
};

$corpus = Parity::corpus();

foreach ($corpus as $group => $sources) {
    foreach ($sources as $label => $source) {
        $compare($group . '/' . $label, $source);
    }
}

if ($fuzz > 0) {
    mt_srand($seed);

    $vocabulary = [
        'SELECT', 'FROM', 'WHERE', 'AND', 'OR', 'NOT', 'IN', 'IS', 'NULL', 'LIKE', 'ILIKE', 'BETWEEN',
        'NOT BETWEEN', 'AGAINST', 'AS', 'JOIN', 'LEFT', 'INNER', 'ON', 'GROUP', 'BY', 'ORDER', 'HAVING',
        'LIMIT', 'OFFSET', 'FOR', 'UPDATE', 'SET', 'DELETE', 'INSERT', 'INTO', 'VALUES', 'DISTINCT', 'ALL',
        'CASE', 'WHEN', 'THEN', 'ELSE', 'END', 'CAST', 'CONVERT', 'USING', 'EXISTS', 'WITH', 'TRUE', 'FALSE',
        'ASC', 'DESC', '(', ')', ',', '.', '*', '+', '-', '/', '%', '=', '!=', '<>', '<', '>', '<=', '>=',
        '&', '|', '^', '~', '->', '->>', '#>', '#>>', '@@', '@>', '<@', '&&', '||', ':', '?0', ':p:', '{p}',
        '{p:int}', 'a', 'r.a', '[a b]', '\\A\\B', '1', '0x1F', '1.5', "'s'", '"d"', "''", '0', 'f(', 'f()',
    ];
    $wrappers   = [
        ['(', ')'], ['NOT ', ''], ['- ', ''], ['~ ', ''], ['f(', ')'], ['f(DISTINCT ', ')'], ['CAST(', ' AS INT)'],
        ['CONVERT(', ' USING utf8)'], ['(SELECT ', ' FROM r)'], ['a IN (', ')'], ['a NOT IN (', ')'],
        ['CASE ', ' WHEN 1 THEN 2 END'], ['CASE a WHEN 1 THEN ', ' END'], ['a + (', ')'], ['', ' NOT LIKE a'],
        ['a AGAINST ', ''], ['EXISTS (SELECT * FROM r WHERE ', ')'], ['a IN (SELECT ', ' FROM r)'],
    ];
    $contexts   = [
        'SELECT %s FROM r', 'SELECT * FROM r WHERE %s', 'SELECT * FROM r ORDER BY %s', 'UPDATE r SET a = %s',
        'INSERT INTO r VALUES (%s)', 'SELECT * FROM r JOIN s ON %s', 'DELETE FROM r WHERE %s',
        'SELECT * FROM r GROUP BY %s HAVING %s',
    ];
    $sources    = [];
    foreach ($corpus as $group => $cases) {
        if ($group !== 'depth') {
            foreach ($cases as $case) {
                $sources[] = $case;
            }
        }
    }

    $pick = static fn (array $list): mixed => $list[mt_rand(0, count($list) - 1)];

    $mutate = static function (string $phql) use ($vocabulary, $pick): string {
        $pieces = preg_split('/(\s+|[(),])/', $phql, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY) ?: [$phql];
        $rounds = mt_rand(1, 3);
        for ($i = 0; $i < $rounds; $i++) {
            $at = mt_rand(0, max(0, count($pieces) - 1));
            switch (mt_rand(0, 5)) {
                case 0:
                    array_splice($pieces, $at, 1);
                    break;
                case 1:
                    array_splice($pieces, $at, 0, [$pieces[$at] ?? '']);
                    break;
                case 2:
                    $other          = mt_rand(0, max(0, count($pieces) - 1));
                    $swap           = $pieces[$at] ?? '';
                    $pieces[$at]    = $pieces[$other] ?? '';
                    $pieces[$other] = $swap;
                    break;
                case 3:
                    array_splice($pieces, $at, 0, [' ' . $pick($vocabulary) . ' ']);
                    break;
                case 4:
                    $pieces[$at] = $pick($vocabulary);
                    break;
                default:
                    array_splice($pieces, $at, 0, [chr(mt_rand(0, 255))]);
                    break;
            }
        }

        return implode('', $pieces);
    };

    $nest = static function () use ($wrappers, $contexts, $vocabulary, $pick): string {
        $expr = mt_rand(0, 1) === 1 ? 'a' : '1';
        if (mt_rand(0, 2) === 0) {
            $expr = implode(' ', array_map(static fn (): string => $pick($vocabulary), range(1, mt_rand(1, 3))));
        }

        $depth = mt_rand(1, 120);
        for ($i = 0; $i < $depth; $i++) {
            [$open, $close] = $pick($wrappers);
            $expr           = $open . $expr . $close;
        }

        $phql = str_replace('%s', $expr, $pick($contexts));

        return mt_rand(0, 5) === 0 ? substr($phql, 0, mt_rand(0, strlen($phql))) : $phql;
    };

    for ($i = 0; $i < $fuzz; $i++) {
        $phql = mt_rand(0, 2) === 0 ? $nest() : $mutate($pick($sources));
        $compare("fuzz/seed{$seed}/#{$i}", $phql);
    }
}

echo "Checked {$checked} case(s) ({$allowed} allow-listed), seed {$seed}: {$mismatches} mismatch(es).\n";

exit($mismatches === 0 ? 0 : 1);
