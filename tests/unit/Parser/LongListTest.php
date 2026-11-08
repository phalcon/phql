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

use function array_fill;
use function count;
use function hrtime;
use function implode;
use function min;
use function str_repeat;

/**
 * Long inputs must parse in linear time. The grammar's lists and
 * left-associative operator chains are left-recursive, so cphalcon keeps them
 * in one parser stack entry however long they grow, and so must this parser.
 *
 * No test here has an absolute time limit: CI runs this suite under coverage,
 * which makes the parser an order of magnitude slower, on runners slower than
 * a laptop. Each speed test instead parses an input and one eight times as
 * long on the same machine: linear parsing takes about 8 times as long,
 * quadratic parsing up to 64 times. The threshold of 20 sits between them.
 */
final class LongListTest extends AbstractUnitTestCase
{
    private const MAX_RATIO = 20.0;

    public function testAndChainGrowsLinearly(): void
    {
        $build = static fn (int $n): string => 'SELECT * FROM r WHERE ' . implode(' AND ', array_fill(0, $n, 'a = 1'));

        $this->assertLessThan(self::MAX_RATIO, $this->growth($build, 1000));
    }

    public function testColumnListGrowsLinearly(): void
    {
        $build = static fn (int $n): string => 'SELECT ' . implode(', ', array_fill(0, $n, 'c')) . ' FROM r';

        $this->assertLessThan(self::MAX_RATIO, $this->growth($build, 2500));
    }

    /**
     * cphalcon tests/unit/Mvc/Model/Query/Lang/ParsePHQLTest.php
     * (testMvcModelQueryLangParsePHQLLargeInList) without its 3 s limit.
     */
    public function testFortyThousandItemInListParses(): void
    {
        $ast = (new Parser())->parse($this->inList(40000));

        $this->assertSame(40000, count($ast['where']['right'] ?? []));
    }

    public function testInListGrowsLinearly(): void
    {
        $this->assertLessThan(self::MAX_RATIO, $this->growth($this->inList(...), 2500));
    }

    public function testLongStringLiteralGrowsLinearly(): void
    {
        $build = static fn (int $n): string => "SELECT * FROM r WHERE a = '" . str_repeat('ab\\\'c\\n', $n) . "'";

        // Large enough (450 KB) that the small run is not dwarfed by
        // scheduler noise on a throttled CI runner.
        $this->assertLessThan(self::MAX_RATIO, $this->growth($build, 65536));
    }

    /**
     * Ratio of the time to parse build(8n) to the time to parse build(n),
     * each the fastest of three runs, after a warm-up.
     *
     * @param callable(int): string $build
     */
    private function growth(callable $build, int $n): float
    {
        $this->secondsToParse($build(10));

        return $this->secondsToParse($build(8 * $n)) / $this->secondsToParse($build($n));
    }

    private function inList(int $count): string
    {
        return 'SELECT * FROM Robots WHERE id IN (' . implode(', ', array_fill(0, $count, '1')) . ')';
    }

    private function secondsToParse(string $phql): float
    {
        $parser = new Parser();
        $times  = [];

        for ($run = 0; $run < 3; $run++) {
            $start = hrtime(true);
            $parser->parse($phql);
            $times[] = (hrtime(true) - $start) / 1e9;
        }

        return min($times);
    }
}
