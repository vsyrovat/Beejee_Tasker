<?php
namespace Framework\PDO\Helpers\Condition;

/**
 * Class InTest
 * @backupGlobals disabled
 */
class InTest extends \PHPUnit\Framework\TestCase
{
    public function test2Arguments()
    {
        $condition = new In('foo', [1, 2, 3]);
        self::assertEquals('(`foo` IN (:foo1,:foo2,:foo3))', $condition->getSQL());
        self::assertEquals(['foo1', 'foo2', 'foo3'], $condition->getCombinedBindNames());
        self::assertEquals([1, 2, 3], $condition->getValue());
        self::assertEquals(['foo1' => 1, 'foo2' => 2, 'foo3' => 3], $condition->getParams());

        $condition->incrementSuffix();
        self::assertEquals('(`foo` IN (:foo11,:foo12,:foo13))', $condition->getSQL());
        self::assertEquals(['foo11', 'foo12', 'foo13'], $condition->getCombinedBindNames());
        self::assertEquals([1, 2, 3], $condition->getValue());
        self::assertEquals(['foo11' => 1, 'foo12' => 2, 'foo13' => 3], $condition->getParams());
    }

    public function test2ArgumentsWithTable()
    {
        $condition = new In('table.foo', [1, 2, 3]);
        self::assertEquals('(`table`.`foo` IN (:table_foo1,:table_foo2,:table_foo3))', $condition->getSQL());
        self::assertEquals(['table_foo1', 'table_foo2', 'table_foo3'], $condition->getCombinedBindNames());
        self::assertEquals([1, 2, 3], $condition->getValue());
        self::assertEquals(['table_foo1' => 1, 'table_foo2' => 2, 'table_foo3' => 3], $condition->getParams());

        $condition->incrementSuffix();
        self::assertEquals('(`table`.`foo` IN (:table_foo11,:table_foo12,:table_foo13))', $condition->getSQL());
        self::assertEquals(['table_foo11', 'table_foo12', 'table_foo13'], $condition->getCombinedBindNames());
        self::assertEquals([1, 2, 3], $condition->getValue());
        self::assertEquals(['table_foo11' => 1, 'table_foo12' => 2, 'table_foo13' => 3], $condition->getParams());
    }

    public function test4Arguments()
    {
        $condition = new In('foo', [1, 2, 3], null, 'baz');
        self::assertEquals('(`foo` IN (:baz1,:baz2,:baz3))', $condition->getSQL());
        self::assertEquals(['baz1', 'baz2', 'baz3'], $condition->getCombinedBindNames());
        self::assertEquals([1, 2, 3], $condition->getValue());
        self::assertEquals(['baz1' => 1, 'baz2' => 2, 'baz3' => 3], $condition->getParams());

        $condition->incrementSuffix();
        self::assertEquals('(`foo` IN (:baz11,:baz12,:baz13))', $condition->getSQL());
        self::assertEquals(['baz11', 'baz12', 'baz13'], $condition->getCombinedBindNames());
        self::assertEquals([1, 2, 3], $condition->getValue());
        self::assertEquals(['baz11' => 1, 'baz12' => 2, 'baz13' => 3], $condition->getParams());

        $condition = new In('foo', [1, 2, 3], null, null);
        self::assertEquals('(`foo` IN (:foo1,:foo2,:foo3))', $condition->getSQL());
        self::assertEquals(['foo1', 'foo2', 'foo3'], $condition->getCombinedBindNames());
        self::assertEquals([1, 2, 3], $condition->getValue());
        self::assertEquals(['foo1' => 1, 'foo2' => 2, 'foo3' => 3], $condition->getParams());

        $condition->incrementSuffix();
        self::assertEquals('(`foo` IN (:foo11,:foo12,:foo13))', $condition->getSQL());
        self::assertEquals(['foo11', 'foo12', 'foo13'], $condition->getCombinedBindNames());
        self::assertEquals([1, 2, 3], $condition->getValue());
        self::assertEquals(['foo11' => 1, 'foo12' => 2, 'foo13' => 3], $condition->getParams());
    }

    public function test4ArgumentsWithTable()
    {
        $condition = new In('table.foo', [1, 2, 3], null, 'baz');
        self::assertEquals('(`table`.`foo` IN (:baz1,:baz2,:baz3))', $condition->getSQL());
        self::assertEquals(['baz1', 'baz2', 'baz3'], $condition->getCombinedBindNames());
        self::assertEquals([1, 2, 3], $condition->getValue());
        self::assertEquals(['baz1' => 1, 'baz2' => 2, 'baz3' => 3], $condition->getParams());

        $condition->incrementSuffix();
        self::assertEquals('(`table`.`foo` IN (:baz11,:baz12,:baz13))', $condition->getSQL());
        self::assertEquals(['baz11', 'baz12', 'baz13'], $condition->getCombinedBindNames());
        self::assertEquals([1, 2, 3], $condition->getValue());
        self::assertEquals(['baz11' => 1, 'baz12' => 2, 'baz13' => 3], $condition->getParams());

        $condition = new In('table.foo', [1, 2, 3], null, null);
        self::assertEquals('(`table`.`foo` IN (:table_foo1,:table_foo2,:table_foo3))', $condition->getSQL());
        self::assertEquals(['table_foo1', 'table_foo2', 'table_foo3'], $condition->getCombinedBindNames());
        self::assertEquals([1, 2, 3], $condition->getValue());
        self::assertEquals(['table_foo1' => 1, 'table_foo2' => 2, 'table_foo3' => 3], $condition->getParams());

        $condition->incrementSuffix();
        self::assertEquals('(`table`.`foo` IN (:table_foo11,:table_foo12,:table_foo13))', $condition->getSQL());
        self::assertEquals(['table_foo11', 'table_foo12', 'table_foo13'], $condition->getCombinedBindNames());
        self::assertEquals([1, 2, 3], $condition->getValue());
        self::assertEquals(['table_foo11' => 1, 'table_foo12' => 2, 'table_foo13' => 3], $condition->getParams());
    }
}
