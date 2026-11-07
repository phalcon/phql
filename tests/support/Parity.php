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

namespace Phalcon\Phql\Tests\Support;

use Throwable;

use function base64_encode;
use function basename;
use function file_get_contents;
use function get_class;
use function glob;
use function in_array;
use function is_array;
use function is_file;
use function is_string;
use function json_decode;
use function ksort;
use function preg_match;
use function sort;

use const JSON_THROW_ON_ERROR;

/**
 * Shared plumbing for the cphalcon parity checks.
 *
 * The corpus lives in `tests/fixtures/parity/<group>.php` (plain
 * `label => phql` arrays). `tests/generate-golden.php` runs every case through
 * ext-phalcon (`Phalcon\Mvc\Model\Query\Lang::parsePHQL()`) twice - with
 * literals enabled and disabled - and records both results in `<group>.json`.
 * FixturesTest replays the goldens against this package and
 * `tests/differential.php` compares both implementations live.
 *
 * Every outcome is normalised to one comparable shape, so "returned an AST"
 * and "threw with this message" compare with a single `===`.
 *
 * @phpstan-type Outcome array{outcome: 'value'|'throw', value: mixed, exception?: class-string}
 */
final class Parity
{
    public const BYTES_PREFIX = '<bytes:base64>';

    public const JSON_DEPTH = 4096;

    /**
     * Golden keys, one per literals mode: `parse` has `orm.enable_literals`
     * on (the default), `parseNoLiterals` has it off.
     */
    public const MODES = ['parse' => true, 'parseNoLiterals' => false];

    /**
     * Corpus files that are not case groups.
     */
    private const NON_GROUPS = ['allow-list'];

    /**
     * Expected phalcon/phql outcome for cases where phalcon/phql deliberately
     * differs from cphalcon, keyed by "group/label/mode".
     *
     * @return array<string, array{outcome: 'throw'|'value', value: mixed, reason: string}>
     */
    public static function allowList(): array
    {
        $path = self::fixturesPath() . '/allow-list.php';
        if (!is_file($path)) {
            return [];
        }

        /** @var array<string, array{outcome: 'throw'|'value', value: mixed, reason: string}> */
        return require $path;
    }

    /**
     * Strips what is allowed to differ between implementations (the exception
     * class: cphalcon throws Phalcon\Mvc\Model\Exception, this package throws
     * Phalcon\Phql\Exception) so the rest compares with `===`.
     *
     * @param array<string, mixed> $outcome
     *
     * @return array{outcome: mixed, value: mixed}
     */
    public static function comparable(array $outcome): array
    {
        return ['outcome' => $outcome['outcome'] ?? null, 'value' => $outcome['value'] ?? null];
    }

    /**
     * @return array<string, array<string, string>> group => label => phql
     */
    public static function corpus(): array
    {
        $groups = [];
        $files  = glob(self::fixturesPath() . '/*.php') ?: [];
        sort($files);

        foreach ($files as $file) {
            $group = basename($file, '.php');
            if (in_array($group, self::NON_GROUPS, true)) {
                continue;
            }

            /** @var array<string, string> $cases */
            $cases          = require $file;
            $groups[$group] = $cases;
        }

        ksort($groups);

        return $groups;
    }

    public static function fixturesPath(): string
    {
        return __DIR__ . '/../fixtures/parity';
    }

    /**
     * Makes a value JSON-encodable without losing bytes: every string that
     * is not valid UTF-8 (control bytes, a multibyte character cut by a
     * 16-byte error excerpt, ...) becomes `<bytes:base64>` + its base64.
     * Goldens store values in this form and the tests apply it to the PHP
     * side too, so both compare byte for byte with `===`.
     */
    public static function jsonSafe(mixed $value): mixed
    {
        if (is_string($value)) {
            return preg_match('//u', $value) === 1 ? $value : self::BYTES_PREFIX . base64_encode($value);
        }

        if (is_array($value)) {
            foreach ($value as $key => $item) {
                $value[$key] = self::jsonSafe($item);
            }
        }

        return $value;
    }

    /**
     * @return array<string, mixed> decoded golden file for one group
     */
    public static function golden(string $group): array
    {
        $path = self::fixturesPath() . '/' . $group . '.json';
        $json = is_file($path) ? file_get_contents($path) : false;
        if ($json === false) {
            return [];
        }

        $decoded = json_decode($json, true, self::JSON_DEPTH, JSON_THROW_ON_ERROR);

        return is_array($decoded) ? $decoded : [];
    }

    /**
     * Runs a parser and normalises either outcome into one shape.
     *
     * @param callable(string): mixed $run
     *
     * @phpstan-return Outcome
     */
    public static function run(callable $run, string $phql): array
    {
        try {
            return ['outcome' => 'value', 'value' => $run($phql)];
        } catch (Throwable $ex) {
            return [
                'outcome'   => 'throw',
                'value'     => $ex->getMessage(),
                'exception' => get_class($ex),
            ];
        }
    }
}
