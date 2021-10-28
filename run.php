<?php
/**
 * 启动文件
 * @author: Xiao Nian
 * @contact: xiaonian030@163.com
 * @datetime: 2021-09-14 10:00
 */
use Workerman\Worker;
use Workerman\Protocols\Http\Request;
use Workerman\Connection\TcpConnection;

//初始化
ini_set('display_errors', 'on');
defined('IN_PHAR') or define('IN_PHAR', boolval(\Phar::running(false)));
defined('SERVER_ROOT') or define('SERVER_ROOT', IN_PHAR ? \Phar::running() : realpath(getcwd()));
defined('PUBLIC_ROOT') or define('PUBLIC_ROOT', SERVER_ROOT.'/public'); //不能加后缀

//创建临时目录
$temp_path=SERVER_ROOT.'/temp';
$log_path=SERVER_ROOT.'/temp/log';
if(!is_dir($log_path)){
    mkdir($log_path, 0777, true);
}
defined('TEMP_ROOT') or define('TEMP_ROOT', $temp_path);
defined('LOG_ROOT') or define('LOG_ROOT', $log_path);

// 检查扩展或环境
if(strpos(strtolower(PHP_OS), 'win') === 0) {
    exit("start.php not support windows.\n");
}
if(!extension_loaded('pcntl')) {
    exit("Please install pcntl extension.\n");
}
if(!extension_loaded('posix')) {
    exit("Please install posix extension.\n");
}

//自动加载文件
require_once SERVER_ROOT . '/core/autoload.php';

//导入配置文件
$mode='produce';
foreach ($argv as $item){
    $item_val=explode('=', $item);
    if(count($item_val)==2 && $item_val[0]=='-mode'){
        $mode=$item_val[1];
    }
}
$config_path=SERVER_ROOT . '/config/'.$mode.'.php';
if (file_exists($config_path)) {
    $conf = require_once $config_path;
}else{
    exit($config_path." is not exist\n");
}
defined('CONFIG') or define('CONFIG', $conf);

//初始化worker
Worker::$stdoutFile = LOG_ROOT.'/error.log';
Worker::$logFile = LOG_ROOT.'/log.log';

$address=CONFIG['HTTP_FRAMEWORK']['PROTOCOL'].'://'.CONFIG['HTTP_FRAMEWORK']['LISTEN_ADDRESS'].':'.CONFIG['HTTP_FRAMEWORK']['PORT'];
$http = new Worker($address);

$http->name= CONFIG['HTTP_FRAMEWORK']['SERVER_NAME'];

// 进程数量
$http->count = CONFIG['HTTP_FRAMEWORK']['PROCESS_COUNT'];

// 接收请求
$http->onMessage = function (TcpConnection $connection, Request $request) {
    //路由分发: 模块=module 类=class 方法=function
    $path=trim($request->path(),'/');
    $dot=strpos($path, '.');
    if($dot===false){
        $paths=explode('/',$path);
        if(isset($paths[0])){
            $module=ucwords($paths[0]);
        }else{
            $module='Index';
        }
        if(isset($paths[1])){
            $class_name=ucwords($paths[1]);
        }else{
            $class_name='Index';
        }
        $function = $paths[2] ?? 'index';
        $class='App\HttpController\\'.$module.'\\'.$class_name;
        if(class_exists($class)){
            $instance=new $class($connection, $request);
            if(method_exists($instance, $function)){
                $instance->$function();
            }else{
                $instance=new App\HttpController\Controller($connection, $request);
                $instance->writeJsonNoFound();
            }
        }else{
            $instance=new App\HttpController\Controller($connection, $request);
            $instance->writeJsonNoFound();
        }
    }else{
        $instance=new App\HttpController\Controller($connection, $request);
        $instance->writeHtml($path);
    }
};

// 运行所有服务
Worker::runAll();
