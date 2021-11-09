<?php
/**
 * 启动文件
 * @author: Xiao Nian
 * @contact: xiaonian030@163.com
 * @datetime: 2021-09-14 10:00
 */
use HP\Http\App as HttpApp;
use HP\Task\App as TaskApp;
use App\Task\Index as TaskIndex;

//初始化
defined('IN_PHAR') or define('IN_PHAR', boolval(\Phar::running(false)));
defined('SERVER_ROOT') or define('SERVER_ROOT', IN_PHAR ? \Phar::running() : realpath(getcwd()));

//自动加载文件
$auto_file=SERVER_ROOT . '/vendor/autoload.php';
if (file_exists($auto_file)) {
    require_once $auto_file;
} else {
    exit("Please composer install.\n");
}

//启动http应用
$http_app = new HttpApp();
$http_app->run(false);

//启动task应用
$task_app = new TaskApp();
$task_app->run(true, function ($tag){
    $task_index = new TaskIndex();
    if($tag==1){
        $task_index->open();
    }else{
        $task_index->close();
    }
});



