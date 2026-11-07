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
 * Regenerates the golden files for every corpus group in
 * `tests/fixtures/parity` from the cphalcon C implementation.
 *
 *     php -d extension=phalcon tests/generate-golden.php
 *
 * Unlike a snapshot, the goldens are a cross-implementation reference: they
 * record what ext-phalcon does, never what this package does. The script
 * therefore refuses to run without the extension instead of falling back to
 * the pure-PHP parser. Each golden records the extension version that
 * produced it.
 *
 * Every case is parsed by `Phalcon\Mvc\Model\Query\Lang::parsePHQL()` twice:
 * `parse` with `phalcon.orm.enable_literals` on, `parseNoLiterals` with it
 * off. `phalcon.orm.cache_level` is -1, so cphalcon neither returns cached
 * ASTs nor appends its `id` key: the goldens describe the parser alone.
 * Strings that are not valid UTF-8 are stored through Parity::jsonSafe().
 */

require __DIR__ . '/../vendor/autoload.php';

use Phalcon\Mvc\Model\Query\Lang;
use Phalcon\Phql\Tests\Support\Parity;

if (!extension_loaded('phalcon') || !class_exists(Lang::class)) {
    fwrite(STDERR, "ext-phalcon is not loaded; refusing to generate goldens from anything else.\n");
    fwrite(STDERR, "Run: php -d extension=phalcon tests/generate-golden.php\n");

    exit(1);
}

ini_set('phalcon.orm.cache_level', '-1');

$generator = 'ext-phalcon ' . phpversion('phalcon');
$written   = 0;
$cases     = 0;

echo "Generating from {$generator} on PHP " . PHP_VERSION . "\n";

$encode = static fn (mixed $value): string => json_encode(
    Parity::jsonSafe($value),
    JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR,
    Parity::JSON_DEPTH
);

$parse = static function (string $phql, bool $literals): array {
    ini_set('phalcon.orm.enable_literals', $literals ? '1' : '0');

    return Parity::run(static fn (string $p): mixed => Lang::parsePHQL($p), $phql);
};

foreach (Parity::corpus() as $group => $sources) {
    $lines = [];

    foreach ($sources as $label => $source) {
        $case = ['source' => $source];
        foreach (Parity::MODES as $mode => $literals) {
            $case[$mode] = $parse($source, $literals);
        }

        $lines[] = '        ' . $encode((string) $label) . ': ' . $encode($case);
        $cases++;
    }

    ini_set('phalcon.orm.enable_literals', '1');

    $json = "{\n"
        . '    "generator": ' . $encode($generator) . ",\n"
        . "    \"cases\": {\n"
        . implode(",\n", $lines) . "\n"
        . "    }\n"
        . "}\n";

    file_put_contents(Parity::fixturesPath() . '/' . $group . '.json', $json);
    $written++;

    echo sprintf("  %-20s %4d case(s)\n", $group, count($sources));
}

echo "Wrote {$written} golden file(s), {$cases} case(s).\n";
