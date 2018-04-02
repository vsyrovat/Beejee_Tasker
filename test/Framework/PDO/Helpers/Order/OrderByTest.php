<?php

declare(strict_types=1);

namespace Framework\PDO\Helpers\Order;

use PHPUnit\Framework\TestCase;

class OrderByTest extends TestCase
{
    public function testNormal()
    {
        $orderBy = new OrderBy('field', 'asc');
        self::assertEquals('`field` ASC', $orderBy->getSQL());

        $orderBy = new OrderBy('field', 'desc');
        self::assertEquals('`field` DESC', $orderBy->getSQL());
    }

    public function testWithDBName()
    {
        $orderBy = new OrderBy('table.field', 'asc');
        self::assertEquals('`table`.`field` ASC', $orderBy->getSQL());

        $orderBy = new OrderBy('table.field', 'desc');
        self::assertEquals('`table`.`field` DESC', $orderBy->getSQL());
    }

    public function testNull()
    {
        $orderBy = new OrderBy(null);
        self::assertNull($orderBy->getSQL());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testBadArguments()
    {
        $orderBy = new OrderBy('field', 'blabla');
    }
}
