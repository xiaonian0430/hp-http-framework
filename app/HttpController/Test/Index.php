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
        $this->writeJson(200, ['a'=>12], '吃了');
    }

    public function index()
    {
        //必须放在pubLic目录下面
        $this->writeFile('html/test/index/index.html');
    }
}