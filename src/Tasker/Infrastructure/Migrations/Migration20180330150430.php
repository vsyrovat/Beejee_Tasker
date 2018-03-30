<?php
namespace Tasker\Infrastructure\Migrations;

use \Framework\Migrations\AbstractMigration;

class Migration20180330150430 extends AbstractMigration
{
    public function run()
    {
        return $this->pdo->query(<<<'SQL'
ALTER TABLE `tasks` ADD `is_done` TINYINT(1) NOT NULL DEFAULT 0
SQL
        );
    }
}
