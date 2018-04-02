<?php

declare(strict_types=1);

namespace Framework\PDO\Helpers\Order;

use PHPUnit\Framework\TestCase;

class OrderByChainTest extends TestCase
{
    public function testCreate()
    {
        $orderBy = new OrderByChain(
            new OrderBy('field1', 'asc'),
            new OrderBy('field2', 'desc')
        );
        self::assertEquals('`field1` ASC, `field2` DESC', $orderBy->getSQL());
    }

    public function testAdd()
    {
        $orderBy = new OrderByChain();
        $orderBy->add(new OrderBy('field1', 'asc'));
        $orderBy->add(new OrderBy('field2', 'desc'));
        self::assertEquals('`field1` ASC, `field2` DESC', $orderBy->getSQL());
    }

    /**
     * @expectedException \TypeError
     */
    public function testBadArgument()
    {
        $orderBy = new OrderByChain(null);
    }
}
