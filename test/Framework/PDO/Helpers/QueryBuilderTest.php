<?php
namespace Framework\PDO\Helpers;

use Framework\PDO\Helpers\Condition\AndCondition;
use Framework\PDO\Helpers\Condition\Equal;
use Framework\PDO\Helpers\Condition\GreaterOrEqual;
use Framework\PDO\Helpers\Condition\InSubquery;
use Framework\PDO\Helpers\Condition\LessOrEqual;
use Framework\PDO\Helpers\Condition\OrCondition;
use Framework\PDO\Helpers\Order\OrderByChain;
use Framework\PDO\Helpers\Order\OrderBy;

/**
 * @backupGlobals disabled
 */
class QueryBuilderTest extends \PHPUnit\Framework\TestCase
{
    public function testBasic()
    {
        $queryBuilder = new QueryBuilder('SELECT * FROM `table`');
        self::assertEquals('SELECT * FROM `table`', $queryBuilder->getQuery());
    }

    public function testWhere()
    {
        $queryBuilder = new QueryBuilder("SELECT * FROM `table` {{WHERE}}", ['color' => 'green']);
        $queryBuilder->prepareWhere('{{WHERE}}', new AndCondition([
            new AndCondition([
                new GreaterOrEqual('date', '2012-11-12', \PDO::PARAM_STR, 'minDate'),
                new LessOrEqual('date', '2017-11-12', \PDO::PARAM_STR, 'maxDate'),
            ]),
            new Equal('parent', 3),
        ]));

        self::assertEquals(
            'SELECT * FROM `table` WHERE (((`date`>=:minDate) AND (`date`<=:maxDate)) AND (`parent`=:parent))',
            $queryBuilder->getQuery()
        );

        self::assertEquals([
            'minDate' => '2012-11-12',
            'maxDate' => '2017-11-12',
            'parent' => 3,
            'color' => 'green',
        ], $queryBuilder->getParams());
    }

    public function testWhereSubquery()
    {
        $queryBuilder = new QueryBuilder("SELECT * FROM `table` {{WHERE}}");

        $subQueryBuilder = new QueryBuilder("SELECT `id` FROM `subtable` {{WHERE}}");
        $subQueryBuilder->prepareWhere('{{WHERE}}', new OrCondition([
            new Equal('param1', 'value1'),
            new Equal('param2', 'value2'),
        ]));

        self::assertEquals(
            'SELECT `id` FROM `subtable` WHERE ((`param1`=:param1) OR (`param2`=:param2))',
            $subQueryBuilder->getQuery()
        );

        self::assertEquals([
            'param1' => 'value1',
            'param2' => 'value2',
        ], $subQueryBuilder->getParams());

        $queryBuilder->prepareWhere('{{WHERE}}', new InSubquery('id', $subQueryBuilder));

        self::assertEquals(
            'SELECT * FROM `table` WHERE (`id` IN (SELECT `id` FROM `subtable` WHERE ((`param1`=:param1) OR (`param2`=:param2))))',
            $queryBuilder->getQuery()
        );

        self::assertEquals([
            'param1' => 'value1',
            'param2' => 'value2',
        ], $queryBuilder->getParams());
    }

    public function testDuplicateBindNames()
    {
        $queryBuilder = new QueryBuilder(
            'SELECT * FROM `table1` {{WHERE}} UNION SELECT * FROM `table2` WHERE `color`=:color',
            ['color' => 'red']
        );
        $queryBuilder->prepareWhere('{{WHERE}}', new Equal('color', 'black', \PDO::PARAM_STR));

        self::assertEquals(
            'SELECT * FROM `table1` WHERE (`color`=:color1) UNION SELECT * FROM `table2` WHERE `color`=:color',
            $queryBuilder->getQuery()
        );
        self::assertEquals(['color' => 'red', 'color1' => 'black'], $queryBuilder->getParams());
    }

    public function testOrderBy()
    {
        $queryBuilder = new QueryBuilder('SELECT * FROM table {{ORDERBY}}');
        $queryBuilder->prepareOrderBy('{{ORDERBY}}', new OrderBy('foo', 'desc'));
        self::assertEquals('SELECT * FROM table ORDER BY `foo` DESC', $queryBuilder->getQuery());

        $queryBuilder = new QueryBuilder('SELECT * FROM `table` {{ORDERBY}}');
        $queryBuilder->prepareOrderBy('{{ORDERBY}}',
            new OrderByChain(
                new OrderBy('field1', 'asc'),
                new OrderBy('field2', 'desc')
            )
        );
        self::assertEquals('SELECT * FROM `table` ORDER BY `field1` ASC, `field2` DESC', $queryBuilder->getQuery());

        $queryBuilder = new QueryBuilder('SELECT * FROM `table` {{ORDERBY}}');
        $queryBuilder->prepareOrderBy('{{ORDERBY}}', new OrderBy(null));
        self::assertEquals('SELECT * FROM `table` ', $queryBuilder->getQuery());
    }
}
