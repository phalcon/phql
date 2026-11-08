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

namespace Phalcon\Phql\Parser;

use Phalcon\Phql\Scanner\Opcode;

use function count;

/**
 * Builds the AST nodes, one method per phql_ret_* helper of cphalcon's
 * ext/phalcon/mvc/model/query/parser.php.inc.h. Keys appear in the order the
 * C helpers add them, optional keys are left out (never null), types are the
 * Opcode values and every token value stays a string.
 *
 * @phpstan-type Node array<array-key, mixed>
 */
final class Ast
{
    /**
     * phql_ret_assoc_name()
     *
     * @param array<array-key, mixed>      $qualifiedName
     * @param array<array-key, mixed>|null $with
     *
     * @return array<string, mixed>
     */
    public static function assocName(array $qualifiedName, ?string $alias, ?array $with): array
    {
        $node = ['qualifiedName' => $qualifiedName];
        if ($alias !== null) {
            $node['alias'] = $alias;
        }

        if ($with !== null) {
            $node['with'] = $with;
        }

        return $node;
    }

    /**
     * cphalcon's phql_ret_zval_list() leaves a list of one item bare for the
     * rules that pass their single item up unwrapped (tables, joins, `with`
     * models, ORDER BY / GROUP BY items, UPDATE assignments) and wraps it
     * once a second item follows.
     *
     * @param list<array<array-key, mixed>> $items at least one
     *
     * @return array<array-key, mixed>
     */
    public static function bareIfSingle(array $items): array
    {
        return count($items) === 1 ? $items[0] : $items;
    }

    /**
     * phql_ret_column_item(): `column` is an expression, or the domain name
     * of `domain.*` (STARALL has neither).
     *
     * @param array<array-key, mixed>|string|null $column
     *
     * @return array<string, mixed>
     */
    public static function columnItem(int $type, array | string | null $column, ?string $alias): array
    {
        $node = ['type' => $type];
        if ($column !== null) {
            $node['column'] = $column;
        }

        if ($alias !== null) {
            $node['alias'] = $alias;
        }

        return $node;
    }

    /**
     * phql_ret_delete_clause()
     *
     * @param array<array-key, mixed> $tables
     *
     * @return array<string, mixed>
     */
    public static function deleteClause(array $tables): array
    {
        return ['tables' => $tables];
    }

    /**
     * phql_ret_delete_statement()
     *
     * @param array<array-key, mixed>      $delete
     * @param array<array-key, mixed>|null $where
     * @param array<array-key, mixed>|null $limit
     *
     * @return array<string, mixed>
     */
    public static function deleteStatement(array $delete, ?array $where, ?array $limit): array
    {
        $node = ['type' => Opcode::DELETE->value, 'delete' => $delete];
        if ($where !== null) {
            $node['where'] = $where;
        }

        if ($limit !== null) {
            $node['limit'] = $limit;
        }

        return $node;
    }

    /**
     * phql_ret_expr()
     *
     * @param array<array-key, mixed>|null $left
     * @param array<array-key, mixed>|null $right
     *
     * @return array<string, mixed>
     */
    public static function expr(int $type, ?array $left, ?array $right): array
    {
        $node = ['type' => $type];
        if ($left !== null) {
            $node['left'] = $left;
        }

        if ($right !== null) {
            $node['right'] = $right;
        }

        return $node;
    }

    /**
     * phql_ret_func_call()
     *
     * @param array<array-key, mixed>|null $arguments
     *
     * @return array<string, mixed>
     */
    public static function funcCall(string $name, ?array $arguments, bool $distinct): array
    {
        $node = ['type' => Opcode::FCALL->value, 'name' => $name];
        if ($arguments !== null) {
            $node['arguments'] = $arguments;
        }

        if ($distinct) {
            $node['distinct'] = true;
        }

        return $node;
    }

    /**
     * phql_ret_insert_statement()
     *
     * @param array<array-key, mixed>      $qualifiedName
     * @param array<array-key, mixed>|null $fields
     * @param array<array-key, mixed>      $values
     *
     * @return array<string, mixed>
     */
    public static function insertStatement(array $qualifiedName, ?array $fields, array $values): array
    {
        $node = ['type' => Opcode::INSERT->value, 'qualifiedName' => $qualifiedName];
        if ($fields !== null) {
            $node['fields'] = $fields;
        }

        $node['values'] = $values;

        return $node;
    }

    /**
     * phql_ret_join_item(): the alias is a qualified-name node.
     *
     * @param array<array-key, mixed>      $qualified
     * @param array<array-key, mixed>|null $alias
     * @param array<array-key, mixed>|null $conditions
     *
     * @return array<string, mixed>
     */
    public static function joinItem(int $type, array $qualified, ?array $alias, ?array $conditions): array
    {
        $node = ['type' => $type, 'qualified' => $qualified];
        if ($alias !== null) {
            $node['alias'] = $alias;
        }

        if ($conditions !== null) {
            $node['conditions'] = $conditions;
        }

        return $node;
    }

    /**
     * phql_ret_limit_clause()
     *
     * @param array<array-key, mixed>      $number
     * @param array<array-key, mixed>|null $offset
     *
     * @return array<string, mixed>
     */
    public static function limitClause(array $number, ?array $offset): array
    {
        $node = ['number' => $number];
        if ($offset !== null) {
            $node['offset'] = $offset;
        }

        return $node;
    }

    /**
     * phql_ret_literal_zval(): NULL, TRUE and FALSE have no value.
     *
     * @return array<string, mixed>
     */
    public static function literal(int $type, ?string $value): array
    {
        return $value === null ? ['type' => $type] : ['type' => $type, 'value' => $value];
    }

    /**
     * phql_ret_order_item(): a sort of 0 (no ASC/DESC) is left out.
     *
     * @param array<array-key, mixed> $column
     *
     * @return array<string, mixed>
     */
    public static function orderItem(array $column, int $sort): array
    {
        return $sort === 0 ? ['column' => $column] : ['column' => $column, 'sort' => $sort];
    }

    /**
     * phql_ret_placeholder_zval()
     *
     * @return array<string, mixed>
     */
    public static function placeholder(int $type, string $value): array
    {
        return ['type' => $type, 'value' => $value];
    }

    /**
     * phql_ret_qualified_name(); its `ns-alias` key is never produced by the
     * grammar.
     *
     * @return array<string, mixed>
     */
    public static function qualifiedName(?string $domain, string $name): array
    {
        if ($domain === null) {
            return ['type' => Opcode::QUALIFIED->value, 'name' => $name];
        }

        return ['type' => Opcode::QUALIFIED->value, 'domain' => $domain, 'name' => $name];
    }

    /**
     * phql_ret_raw_qualified_name() as CAST and CONVERT use it: a type name.
     *
     * @return array<string, mixed>
     */
    public static function rawQualifiedName(string $name): array
    {
        return ['type' => Opcode::RAW_QUALIFIED->value, 'name' => $name];
    }

    /**
     * phql_ret_select_clause(): `distinct` is 1 for DISTINCT and 0 for ALL.
     *
     * @param array<array-key, mixed>      $columns
     * @param array<array-key, mixed>      $tables
     * @param array<array-key, mixed>|null $joins
     *
     * @return array<string, mixed>
     */
    public static function selectClause(?int $distinct, array $columns, array $tables, ?array $joins): array
    {
        $node = [];
        if ($distinct !== null) {
            $node['distinct'] = $distinct;
        }

        $node['columns'] = $columns;
        $node['tables']  = $tables;
        if ($joins !== null) {
            $node['joins'] = $joins;
        }

        return $node;
    }

    /**
     * phql_ret_select_statement(): ORDER BY comes before GROUP BY in the node
     * although GROUP BY comes first in the statement.
     *
     * @param array<array-key, mixed>      $select
     * @param array<array-key, mixed>|null $where
     * @param array<array-key, mixed>|null $orderBy
     * @param array<array-key, mixed>|null $groupBy
     * @param array<array-key, mixed>|null $having
     * @param array<array-key, mixed>|null $limit
     *
     * @return array<string, mixed>
     */
    public static function selectStatement(
        array $select,
        ?array $where,
        ?array $orderBy,
        ?array $groupBy,
        ?array $having,
        ?array $limit,
        ?bool $forUpdate
    ): array {
        $node = ['type' => Opcode::SELECT->value, 'select' => $select];
        if ($where !== null) {
            $node['where'] = $where;
        }

        if ($orderBy !== null) {
            $node['orderBy'] = $orderBy;
        }

        if ($groupBy !== null) {
            $node['groupBy'] = $groupBy;
        }

        if ($having !== null) {
            $node['having'] = $having;
        }

        if ($limit !== null) {
            $node['limit'] = $limit;
        }

        if ($forUpdate !== null) {
            $node['forUpdate'] = $forUpdate;
        }

        return $node;
    }

    /**
     * phql_ret_update_clause()
     *
     * @param array<array-key, mixed>      $tables
     * @param array<array-key, mixed>|null $joins
     * @param array<array-key, mixed>      $values
     *
     * @return array<string, mixed>
     */
    public static function updateClause(array $tables, ?array $joins, array $values): array
    {
        $node = ['tables' => $tables];
        if ($joins !== null) {
            $node['joins'] = $joins;
        }

        $node['values'] = $values;

        return $node;
    }

    /**
     * phql_ret_update_item()
     *
     * @param array<array-key, mixed> $column
     * @param array<array-key, mixed> $expr
     *
     * @return array<string, mixed>
     */
    public static function updateItem(array $column, array $expr): array
    {
        return ['column' => $column, 'expr' => $expr];
    }

    /**
     * phql_ret_update_statement()
     *
     * @param array<array-key, mixed>      $update
     * @param array<array-key, mixed>|null $where
     * @param array<array-key, mixed>|null $limit
     *
     * @return array<string, mixed>
     */
    public static function updateStatement(array $update, ?array $where, ?array $limit): array
    {
        $node = ['type' => Opcode::UPDATE->value, 'update' => $update];
        if ($where !== null) {
            $node['where'] = $where;
        }

        if ($limit !== null) {
            $node['limit'] = $limit;
        }

        return $node;
    }
}
