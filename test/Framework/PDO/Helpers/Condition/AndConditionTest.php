<?php
namespace Framework\PDO\Helpers\Condition;

/**
 * @backupGlobals disabled
 */
class AndConditionTest extends \PHPUnit\Framework\TestCase
{
    public function testPeer()
    {
        $condition = new AndCondition([
            new Equal('foo', 123),
            new Equal('bar', 456),
        ]);
        self::assertEquals('((`foo`=:foo) AND (`bar`=:bar))', $condition->getSQL());
    }

    public function testSingleArgument()
    {
        $condition = new AndCondition([
            new Equal('foo', 123)
        ]);
        self::assertEquals('(`foo`=:foo)', $condition->getSQL());
    }

    public function testAdd()
    {
        $condition = new AndCondition();
        $condition->add(new Equal('foo', 123));
        $condition->add(new Equal('bar', 456));
        self::assertEquals('((`foo`=:foo) AND (`bar`=:bar))', $condition->getSQL());
    }

    public function testDuplicateBindNames1()
    {
        $condition = new AndCondition([
            new GreaterOrEqual('foo', 123),
            new LessOrEqual('foo', 456)
        ]);
        self::assertEquals('((`foo`>=:foo) AND (`foo`<=:foo1))', $condition->getSQL());
        self::assertEquals(['foo', 'foo1'], $condition->getCombinedBindNames());
        self::assertEquals(['foo' => 123, 'foo1' => 456], $condition->getParams());
    }

    public function testDuplicateBindNames2()
    {
        $condition = new AndCondition([
            new AndCondition([
                new Equal('foo', 123),
                new Equal('bar', 456)
            ]),
            new AndCondition([
                new Equal('foo', 123),
                new Equal('bar', 456)
            ])
        ]);
        self::assertEquals('(((`foo`=:foo) AND (`bar`=:bar)) AND ((`foo`=:foo1) AND (`bar`=:bar1)))', $condition->getSQL());
        self::assertEquals(['foo', 'bar', 'foo1', 'bar1'], $condition->getCombinedBindNames());
        self::assertEquals(['foo' => 123, 'bar' => 456, 'foo1' => 123, 'bar1' => 456], $condition->getParams());
    }

    public function testCombined()
    {
        $condition = new AndCondition([
            new AndCondition([
                new Equal('foo', 123),
                new Equal('bar', 456),
            ]),
            new AndCondition([
                new Equal('baz', 123),
                new GreaterOrEqual('buzz', 456),
            ]),
            new LessOrEqual('beer', 123),
            new Equal('bear', 456),
        ]);
        self::assertEquals(
            '(((`foo`=:foo) AND (`bar`=:bar)) AND ((`baz`=:baz) AND (`buzz`>=:buzz)) AND (`beer`<=:beer) AND (`bear`=:bear))',
            $condition->getSQL()
        );
    }

    public function testEmptyCondition()
    {
        $condition = new AndCondition;
        self::assertEquals('', $condition->getSQL());
    }

    public function testEmptySubcondition()
    {
        $condition = new AndCondition([
            new Equal('foo', 123),
            new AndCondition(),
        ]);
        self::assertEquals('(`foo`=:foo)', $condition->getSQL());
    }
}
