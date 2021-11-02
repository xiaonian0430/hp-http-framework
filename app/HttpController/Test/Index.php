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
        for ($c = 100; $c--;) {
            go(function () use( $c ) {
                for ($n = 100; $n--;) {
                    echo 'time: '.$c.'-'.$n.PHP_EOL;
                }
            });
        }
        $used_time = (microtime(true) - $s);
        $this->writeJson(200, ['a'=>12, 'used_time'=>$used_time], '吃了');
    }

    public function index()
    {
        //必须放在pubLic目录下面
        $this->writeFile('html/test/index/index.html');
    }
}