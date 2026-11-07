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

use Phalcon\Phql\Parser\Ast;
use Phalcon\Phql\Scanner\Opcode;
use Phalcon\Phql\Tests\AbstractUnitTestCase;

use function array_keys;

/**
 * Node shapes of cphalcon's parser.php.inc.h: key order, optional keys left
 * out rather than null, and the list helper that keeps a single item bare.
 */
final class AstTest extends AbstractUnitTestCase
{
    public function testExpressionOmitsMissingOperands(): void
    {
        $this->assertSame(
            ['type' => Opcode::NOT->value, 'right' => ['x']],
            Ast::expr(Opcode::NOT->value, null, ['x'])
        );
    }

    public function testListOfManyItemsStaysAList(): void
    {
        $a = Ast::qualifiedName(null, 'a');
        $b = Ast::qualifiedName(null, 'b');

        $this->assertSame([$a, $b], Ast::bareIfSingle([$a, $b]));
    }

    /**
     * Rules such as `associated_name_list ::= associated_name` pass a single
     * item up unwrapped; phql_ret_zval_list() wraps it once a second follows.
     */
    public function testListOfOneItemIsTheBareItem(): void
    {
        $a = Ast::qualifiedName(null, 'a');

        $this->assertSame($a, Ast::bareIfSingle([$a]));
    }

    public function testLiteralWithoutValue(): void
    {
        $this->assertSame(['type' => Opcode::NULL->value], Ast::literal(Opcode::NULL->value, null));
    }

    public function testQualifiedNameWithDomain(): void
    {
        $this->assertSame(
            ['type' => Opcode::QUALIFIED->value, 'domain' => 'r', 'name' => 'id'],
            Ast::qualifiedName('r', 'id')
        );
    }

    public function testSelectStatementPutsOrderByBeforeGroupBy(): void
    {
        $node = Ast::selectStatement(['columns' => []], null, ['o'], ['g'], null, null, null);

        $this->assertSame(['type', 'select', 'orderBy', 'groupBy'], array_keys($node));
    }
}
