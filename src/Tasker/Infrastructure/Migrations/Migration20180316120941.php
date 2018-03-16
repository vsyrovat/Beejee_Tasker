<?php
namespace Tasker\Infrastructure\Migrations;

use \Framework\Migrations\AbstractMigration;

class Migration20180316120941 extends AbstractMigration
{
    public function run()
    {
        return $this->pdo->query(<<<'SQL'
CREATE TABLE `tasks` (
  `id` INTEGER AUTO_INCREMENT,
  `created_at` DATETIME,
  `username` VARCHAR(255),
  `email` VARCHAR(255),
  `text` TEXT,
  `image` VARCHAR(255),
  PRIMARY KEY (`id`)
)
SQL
        );
    }
}
