<?php
namespace Framework\PDO\Helpers\Condition;

/**
 * @backupGlobals disabled
 */
class LessTest extends \PHPUnit\Framework\TestCase
{
    public function test2Arguments()
    {
        $condition = new Less('foo', 'bar');
        self::assertEquals('(`foo`<:foo)', $condition->getSQL());

        $condition->incrementSuffix();
        self::assertEquals('(`foo`<:foo1)', $condition->getSQL());
    }

    public function test2ArgumentsWithTable()
    {
        $condition = new Less('table.foo', 'bar');
        self::assertEquals('(`table`.`foo`<:table_foo)', $condition->getSQL());
        self::assertEquals('bar', $condition->getValue());
        self::assertEquals(['table_foo' => 'bar'], $condition->getParams());

        $condition->incrementSuffix();
        self::assertEquals('(`table`.`foo`<:table_foo1)', $condition->getSQL());
        self::assertEquals('bar', $condition->getValue());
        self::assertEquals(['table_foo1' => 'bar'], $condition->getParams());
    }

    public function test4Arguments()
    {
        $condition = new Less('foo', 'bar', null, 'baz');
        self::assertEquals('(`foo`<:baz)', $condition->getSQL());

        $condition->incrementSuffix();
        self::assertEquals('(`foo`<:baz1)', $condition->getSQL());

        $condition = new Less('foo', 'bar', null, null);
        self::assertEquals('(`foo`<:foo)', $condition->getSQL());

        $condition->incrementSuffix();
        self::assertEquals('(`foo`<:foo1)', $condition->getSQL());
    }

    public function test4ArgumentsWithTable()
    {
        $condition = new Less('table.foo', 'bar', null, 'baz');
        self::assertEquals('(`table`.`foo`<:baz)', $condition->getSQL());
        self::assertEquals('bar', $condition->getValue());
        self::assertEquals(['baz' => 'bar'], $condition->getParams());

        $condition->incrementSuffix();
        self::assertEquals('(`table`.`foo`<:baz1)', $condition->getSQL());
        self::assertEquals('bar', $condition->getValue());
        self::assertEquals(['baz1' => 'bar'], $condition->getParams());

        $condition = new Less('table.foo', 'bar', null, null);
        self::assertEquals('(`table`.`foo`<:table_foo)', $condition->getSQL());
        self::assertEquals('bar', $condition->getValue());
        self::assertEquals(['table_foo' => 'bar'], $condition->getParams());

        $condition->incrementSuffix();
        self::assertEquals('(`table`.`foo`<:table_foo1)', $condition->getSQL());
        self::assertEquals('bar', $condition->getValue());
        self::assertEquals(['table_foo1' => 'bar'], $condition->getParams());
    }
}
