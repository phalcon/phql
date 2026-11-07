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

namespace Phalcon\Phql\Tests\Unit\Parser;

use Phalcon\Phql\Parser;
use Phalcon\Phql\Tests\AbstractUnitTestCase;
use Phalcon\Phql\Tests\Support\Parity;
use PHPUnit\Framework\Attributes\DataProvider;

use function array_keys;
use function is_array;

/**
 * Replays every case of the parity corpus against the golden outcome recorded
 * from ext-phalcon by `tests/generate-golden.php`, once with literals enabled
 * (`parse`) and once with them disabled (`parseNoLiterals`).
 *
 * `assertSame()` rather than `assertEquals()`: key order and int/string types
 * are part of the AST format, and only an identity comparison notices drift.
 * Exceptions compare by message only - cphalcon throws
 * Phalcon\Mvc\Model\Exception, this package Phalcon\Phql\Exception.
 *
 * Cases in `allow-list.php` are deliberate phalcon/phql divergences and are
 * checked against their recorded expectation instead of the C golden.
 */
final class FixturesTest extends AbstractUnitTestCase
{
    /**
     * @return array<string, array{0: string, 1: bool, 2: mixed}>
     */
    public static function goldenProvider(): array
    {
        $allowList = Parity::allowList();
        $cases     = [];

        foreach (Parity::corpus() as $group => $sources) {
            $golden = Parity::golden($group);
            /** @var array<string, array<string, mixed>> $goldenCases */
            $goldenCases = is_array($golden['cases'] ?? null) ? $golden['cases'] : [];

            foreach ($sources as $label => $source) {
                foreach (Parity::MODES as $mode => $literals) {
                    $key = $group . '/' . $label . '/' . $mode;
                    if (isset($allowList[$key])) {
                        $expected = Parity::jsonSafe(Parity::comparable($allowList[$key]));
                    } else {
                        /** @var array<string, mixed> $outcome */
                        $outcome  = $goldenCases[$label][$mode] ?? ['outcome' => 'missing golden', 'value' => null];
                        $expected = Parity::comparable($outcome);
                    }

                    $cases[$key] = [$source, $literals, $expected];
                }
            }
        }

        return $cases;
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function groupProvider(): array
    {
        $groups = [];
        foreach (array_keys(Parity::corpus()) as $group) {
            $groups[$group] = [$group];
        }

        return $groups;
    }

    /**
     * Each golden records the statement it was generated from. A corpus
     * statement edited, added or removed without regenerating the goldens
     * would otherwise be compared against another statement's outcome.
     */
    #[DataProvider('groupProvider')]
    public function testGoldensMatchTheCorpus(string $group): void
    {
        /** @var array<string, array<string, mixed>> $cases */
        $cases  = Parity::golden($group)['cases'] ?? [];
        $golden = [];
        foreach ($cases as $label => $case) {
            $golden[$label] = $case['source'] ?? null;
        }

        $this->assertSame(
            Parity::jsonSafe(Parity::corpus()[$group]),
            $golden,
            "Regenerate the goldens: php -d extension=phalcon tests/generate-golden.php"
        );
    }

    #[DataProvider('goldenProvider')]
    public function testParseMatchesCphalcon(string $source, bool $literals, mixed $expected): void
    {
        $actual = Parity::jsonSafe(Parity::comparable(Parity::run(
            static fn (string $phql): ?array => (new Parser())->setEnableLiterals($literals)->parse($phql),
            $source
        )));

        $this->assertSame($expected, $actual);
    }
}
