<?php
namespace Framework\PDO\Helpers\Condition;

/**
 * @backupGlobals disabled
 */
class OrConditionTest extends \PHPUnit\Framework\TestCase
{
    public function testPeer()
    {
        $condition = new OrCondition([
            new Equal('foo', 123),
            new Equal('bar', 456),
        ]);
        self::assertEquals('((`foo`=:foo) OR (`bar`=:bar))', $condition->getSQL());
    }

    public function testSingleArgument()
    {
        $condition = new OrCondition([
            new Equal('foo', 123)
        ]);
        self::assertEquals('(`foo`=:foo)', $condition->getSQL());
    }

    public function testAdd()
    {
        $condition = new OrCondition();
        $condition->add(new Equal('foo', 123));
        $condition->add(new Equal('bar', 456));
        self::assertEquals('((`foo`=:foo) OR (`bar`=:bar))', $condition->getSQL());
    }

    public function testDuplicateBindNames1()
    {
        $condition = new OrCondition([
            new Equal('foo', 123),
            new Equal('foo', 456)
        ]);
        self::assertEquals('((`foo`=:foo) OR (`foo`=:foo1))', $condition->getSQL());
        self::assertEquals(['foo', 'foo1'], $condition->getCombinedBindNames());
        self::assertEquals(['foo' => 123, 'foo1' => 456], $condition->getParams());
    }

    public function testDuplicateBindNames2()
    {
        $condition = new OrCondition([
            new OrCondition([
                new Equal('foo', 123),
                new Equal('bar', 456)
            ]),
            new OrCondition([
                new Equal('foo', 123),
                new Equal('bar', 456)
            ])
        ]);
        self::assertEquals('(((`foo`=:foo) OR (`bar`=:bar)) OR ((`foo`=:foo1) OR (`bar`=:bar1)))', $condition->getSQL());
        self::assertEquals(['foo', 'bar', 'foo1', 'bar1'], $condition->getCombinedBindNames());
        self::assertEquals(['foo' => 123, 'bar' => 456, 'foo1' => 123, 'bar1' => 456], $condition->getParams());
    }

    public function testCombined()
    {
        $condition = new OrCondition([
            new OrCondition([
                new Equal('foo', 123),
                new Equal('bar', 456),
            ]),
            new OrCondition([
                new Equal('baz', 123),
                new GreaterOrEqual('buzz', 456),
            ]),
            new LessOrEqual('beer', 123),
            new Equal('bear', 456),
        ]);
        self::assertEquals(
            '(((`foo`=:foo) OR (`bar`=:bar)) OR ((`baz`=:baz) OR (`buzz`>=:buzz)) OR (`beer`<=:beer) OR (`bear`=:bear))',
            $condition->getSQL()
        );
    }

    public function testEmptyCondition()
    {
        $condition = new OrCondition;
        self::assertEquals('', $condition->getSQL());
    }

    public function testEmptySubcondition()
    {
        $condition = new OrCondition([
            new Equal('foo', 123),
            new AndCondition(),
        ]);
        self::assertEquals('(`foo`=:foo)', $condition->getSQL());
    }
}
