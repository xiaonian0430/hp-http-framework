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
        $options = [
            'parameters' => [
                'database' => 10,
            ],
        ];
        $client = new \Predis\Client('tcp://120.24.187.47:51012', $options);
        $client->set('test', 12);
        $this->writeJson(200, ['api'=>1, 'used_time'=>$used_time], '吃了');
    }

    public function html()
    {
        //必须放在pubLic目录下面
        $this->writeFile('html/test/index/index.html');
    }

    public function redisTest()
    {
        \Swoole\Runtime::enableCoroutine();
        $s = microtime(true);
        \Co\run(function() {
            for ($c = 100; $c--;) {
                go(function () use($c){
                    $options = [
                        'parameters' => [
                            'database' => 10,
                        ],
                    ];
                    $client = new \Predis\Client('tcp://120.24.187.47:51012', $options);
                    for ($n = 100; $n--;) {
                        $client->set('test_'.$c.'_'.$n, 12);
                    }
                });
            }
        });
        $used_time = (microtime(true) - $s);
        $this->writeJson(200, ['api'=>1, 'used_time'=>$used_time], '吃了');
    }
}