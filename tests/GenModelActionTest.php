<?php
declare(strict_types=1);
/**
 * This file is part of EasySwoole.
 *
 * @link     https://www.easyswoole.com
 * @document https://www.easyswoole.com
 * @contact  https://www.easyswoole.com/Preface/contact.html
 * @license  https://github.com/easy-swoole/easyswoole/blob/3.x/LICENSE
 */

namespace EasySwoole\FastDb\Tests;

use EasySwoole\Command\CommandManager;
use EasySwoole\FastDb\Commands\GenModelAction;
use EasySwoole\FastDb\Config;
use EasySwoole\FastDb\FastDb;
use EasySwoole\FastDb\Mysql\QueryResult;
use EasySwoole\Mysqli\QueryBuilder;
use EasySwoole\Spl\SplBean;

final class GenModelActionTest extends BaseTestCase
{
    protected $tableName = 'easyswoole_user';

    protected function setUp(): void
    {
        parent::setUp();
        $configObj = new Config(MYSQL_CONFIG);
        FastDb::getInstance()->addDb($configObj);
        FastDb::getInstance()->setOnQuery(function (QueryResult $queryResult) {
//            if ($queryResult->getQueryBuilder()) {
//                echo $queryResult->getQueryBuilder()->getLastQuery() . "\n";
//            } else {
//                echo $queryResult->getRawSql() . "\n";
//            }
        });

        $this->createTestTable();
    }

    private function createTestTable()
    {
        $sql = <<<Sql
CREATE TABLE IF NOT EXISTS `{$this->tableName}`
(
    `id`      int unsigned NOT NULL AUTO_INCREMENT COMMENT 'increment id',
    `name`    varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'name',
    `status`  tinyint unsigned DEFAULT '0' COMMENT 'status',
    `score`   int unsigned DEFAULT '0' COMMENT 'score',
    `sex`     tinyint unsigned DEFAULT '0' COMMENT 'sex',
    `address` json                                                          DEFAULT NULL COMMENT 'address',
    `email`   varchar(150) COLLATE utf8mb4_general_ci                       DEFAULT NULL COMMENT 'email',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
Sql;
        $builder = new QueryBuilder();
        $builder->raw($sql);
        FastDb::getInstance()->query($builder)->getResult();
    }

    public function testGenModel()
    {
        $file = __DIR__ . '/Model/EasyswooleUser.php';
        @unlink($file);

        $commandManager = CommandManager::getInstance();
        $opts = [
            'table'         => 'easyswoole_user',
            'path'          => 'tests/Model',
            'with-comments' => '',
        ];
        $commandManager->setOpts($opts);
        (new GenModelAction())->run();

        $this->assertFileExists($file);
        @unlink($file);
    }
}
