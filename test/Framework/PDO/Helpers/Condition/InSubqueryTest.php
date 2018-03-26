<?php
namespace Framework\PDO\Helpers\Condition;

use Framework\PDO\Helpers\QueryBuilder;

/**
 * @backupGlobals disabled
 */
class InSubqueryTest extends \PHPUnit\Framework\TestCase
{
    public function testSingleSubcondition()
    {
        $condition = new InSubquery('id',
            (new QueryBuilder('SELECT `id` FROM `subtable` {{WHERE}}'))->prepareWhere('{{WHERE}}', new Equal('a', 'b'))
        );
        self::assertEquals('(`id` IN (SELECT `id` FROM `subtable` WHERE (`a`=:a)))', $condition->getSQL());
        self::assertEquals(['a'], $condition->getCombinedBindNames());
        self::assertEquals(['a' => 'b'], $condition->getParams());

        $condition->incrementSuffix();
        self::assertEquals('(`id` IN (SELECT `id` FROM `subtable` WHERE (`a`=:a1)))', $condition->getSQL());
        self::assertEquals(['a1'], $condition->getCombinedBindNames());
        self::assertEquals(['a1' => 'b'], $condition->getParams());
    }

    public function testSingleSubconditionTable()
    {
        $condition = new InSubquery('table.id',
            (new QueryBuilder('SELECT `id` FROM `subtable` {{WHERE}}'))->prepareWhere('{{WHERE}}', new Equal('subtable.a', 'b'))
        );
        self::assertEquals(
            '(`table`.`id` IN (SELECT `id` FROM `subtable` WHERE (`subtable`.`a`=:subtable_a)))',
            $condition->getSQL()
        );
        self::assertEquals(['subtable_a'], $condition->getCombinedBindNames());
        self::assertEquals(['subtable_a' => 'b'], $condition->getParams());

        $condition->incrementSuffix();
        self::assertEquals(
            '(`table`.`id` IN (SELECT `id` FROM `subtable` WHERE (`subtable`.`a`=:subtable_a1)))',
            $condition->getSQL()
        );
        self::assertEquals(['subtable_a1'], $condition->getCombinedBindNames());
        self::assertEquals(['subtable_a1' => 'b'], $condition->getParams());
    }

    public function testComplexSubcondition()
    {
        $condition = new AndCondition([
            new Equal('param', 'value'),
            new InSubquery('id',
                (new QueryBuilder('SELECT `id` FROM `subtable` {{WHERE}}'))
                    ->prepareWhere('{{WHERE}}',
                        new AndCondition([
                            new Equal('a', 'b'),
                            new LessOrEqual('d', 123)
                        ]))
            )
        ]);
        self::assertEquals(
            '((`param`=:param) AND (`id` IN (SELECT `id` FROM `subtable` WHERE ((`a`=:a) AND (`d`<=:d)))))',
            $condition->getSQL()
        );
        self::assertEquals(['param', 'a', 'd'], $condition->getCombinedBindNames());
        self::assertEquals(['param' => 'value', 'a' => 'b', 'd' => 123], $condition->getParams());

        $condition->incrementSuffix();
        self::assertEquals(
            '((`param`=:param1) AND (`id` IN (SELECT `id` FROM `subtable` WHERE ((`a`=:a1) AND (`d`<=:d1)))))',
            $condition->getSQL()
        );
        self::assertEquals(['param1', 'a1', 'd1'], $condition->getCombinedBindNames());
        self::assertEquals(['param1' => 'value', 'a1' => 'b', 'd1' => 123], $condition->getParams());
    }

    public function testDuplicateBindNames()
    {
        $condition = new OrCondition([
            new GreaterOrEqual('id', 12),
            new InSubquery('id',
                (new QueryBuilder('SELECT * FROM `table1` {{WHERE}}'))
                    ->prepareWhere('{{WHERE}}', new Equal('id', 34))),
            new InSubquery('id',
                (new QueryBuilder('SELECT * FROM `table2` {{WHERE}}'))
                    ->prepareWhere('{{WHERE}}', new AndCondition([new GreaterOrEqual('id', 56), new LessOrEqual('id', 1024)]))),
        ]);
        self::assertEquals(
            '((`id`>=:id) OR (`id` IN (SELECT * FROM `table1` WHERE (`id`=:id1))) OR (`id` IN (SELECT * FROM `table2` WHERE ((`id`>=:id2) AND (`id`<=:id3)))))',
            $condition->getSQL()
        );
        self::assertEquals(['id', 'id1', 'id2', 'id3'], $condition->getCombinedBindNames());
        self::assertEquals(['id' => 12, 'id1' => 34, 'id2' => 56, 'id3' => 1024], $condition->getParams());
    }
}
