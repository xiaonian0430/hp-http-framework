<?php
/**
 * 启动文件
 * @author: Xiao Nian
 * @contact: xiaonian030@163.com
 * @datetime: 2021-09-14 10:00
 */
use HP\Http\App;

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
date_default_timezone_set('PRC');

$config = \Kafka\ConsumerConfig::getInstance();
$config->setMetadataRefreshIntervalMs(10000);
$config->setMetadataBrokerList('47.106.68.178:59094');
$config->setGroupId('test');
$config->setBrokerVersion('1.0.0');
$config->setTopics(['test']);
//$config->setOffsetReset('earliest');
$consumer = new \Kafka\Consumer();
$consumer->start(function($topic, $part, $message) {
    var_dump($message);
});

