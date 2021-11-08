<?php
/**
 * @author: Xiao Nian
 * @contact: xiaonian030@163.com
 * @datetime: 2019-12-01 14:00
 */

namespace App\HttpController\Test;
use App\HttpController\Basic;

class Index extends Basic
{
    public function api()
    {
        $s = microtime(true);
        $used_time = (microtime(true) - $s);
        $this->writeJson(200, ['api'=>1, 'used_time'=>$used_time], '吃了');
    }

    public function html()
    {
        //必须放在pubLic目录下面
        $this->writeFile('html/test/index/index.html');
    }

    public function redisTest()
    {
        $s = microtime(true);
        $options = [
            'parameters' => [
                'database' => 10,
            ],
        ];
        $client = new \Predis\Client('tcp://120.24.187.47:51012', $options);
        $client->set('test', 12);
        $used_time = (microtime(true) - $s);
        $this->writeJson(200, ['redisTest'=>1, 'used_time'=>$used_time], '吃了');
    }

    public function redisTestPatch()
    {
        \Swoole\Runtime::enableCoroutine(true, $flags = SWOOLE_HOOK_ALL);
        $s = microtime(true);
        for($i=0;$i<800;$i++){
            go(function () use($i) {
                $options = [
                    'parameters' => [
                        'database' => 10,
                    ],
                ];
                $client = new \Predis\Client('tcp://120.24.187.47:51012', $options);
                $client->set('redisTestPatch_'.$i, 12);
            });
        }
        $used_time = (microtime(true) - $s);
        $this->writeJson(200, ['redisTestPatch'=>1, 'used_time'=>$used_time], '吃了');
    }

    public function mysqlTest()
    {
        /**
        CREATE TABLE `inter_test` (
        `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
        `name` varchar(100) NOT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
         */
        \Swoole\Runtime::enableCoroutine(true, $flags = SWOOLE_HOOK_ALL);
        $s = microtime(true);
        go(function () {
            $database = new \HP\Database\MysqliDb(Array (
                'host' => '120.25.72.119',
                'username' => 'root',
                'password' => 'Kn6Bl4hVTkovGmcECSDR',
                'db'=> 'test_demo',
                'port' => 52341,
                'prefix' => '',
                'charset' => 'utf8mb4'));
            try {
                $database->insert('inter_test', [
                    'name' => 'mysqlTest_1212'
                ]);
            } catch (\Exception $e) {
            }
        });
        $used_time = (microtime(true) - $s);
        $this->writeJson(200, ['mysqlTest'=>1, 'used_time'=>$used_time], '吃了');
    }

    public function mysqlTestPatch()
    {
        /**
        CREATE TABLE `inter_test` (
        `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
        `name` varchar(100) NOT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
         */
        \Swoole\Runtime::enableCoroutine(true, $flags = SWOOLE_HOOK_ALL);
        $s = microtime(true);
        for($i=0;$i<100;$i++){
            go(function () use($i) {
                $database = new \HP\Database\MysqliDb (Array (
                    'host' => '120.25.72.119',
                    'username' => 'root',
                    'password' => 'Kn6Bl4hVTkovGmcECSDR',
                    'db'=> 'test_demo',
                    'port' => 52341,
                    'prefix' => '',
                    'charset' => 'utf8mb4'));
                try {
                    $database->insert('inter_test', [
                        'name' => 'mysqlTestPatch_' . $i
                    ]);
                } catch (\Exception $e) {
                }
            });
        }
        $used_time = (microtime(true) - $s);
        $this->writeJson(200, ['mysqlTestPatch'=>1, 'used_time'=>$used_time], '吃了');
    }

    public function logTest(){
        $s = microtime(true);
        \HP\Log\Log::info('123123123' ,['sdf'=>12, 'abd'=>2]);
        $used_time = (microtime(true) - $s);
        $this->writeJson(200, ['logTest'=>1, 'used_time'=>$used_time], '吃了');
    }

    public function mqTest(){
        $s = microtime(true);
        $config = \Kafka\ProducerConfig::getInstance();
        $config->setMetadataRefreshIntervalMs(10000);
        $config->setMetadataBrokerList('47.106.68.178:59094');
        $config->setBrokerVersion('1.0.0');
        $config->setRequiredAck(1);
        $config->setIsAsyn(false);
        $config->setProduceInterval(500);
        $producer = new \Kafka\Producer();
        $producer->send([
            [
                'topic' => 'test1',
                'value' => 'test1....message.',
                'key' => 'testKey',
            ],
        ]);

        $used_time = (microtime(true) - $s);
        $this->writeJson(200, ['mqTest'=>1, 'used_time'=>$used_time], 'mqTest');
    }
}