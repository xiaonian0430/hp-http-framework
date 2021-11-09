<?php
/**
 * @author: Xiao Nian
 * @contact: xiaonian030@163.com
 * @datetime: 2019-12-01 14:00
 */

namespace App\Task;
use longlang\phpkafka\Consumer\Consumer;
use longlang\phpkafka\Consumer\ConsumerConfig;
class Index
{
    public function action()
    {
        echo 'test'.PHP_EOL;
        $config = new ConsumerConfig();
        $config->setBroker('47.106.68.178:59094');
        $config->setTopic('test'); // 主题名称
        $config->setGroupId('testGroup'); // 分组ID
        $config->setClientId('test'); // 客户端ID，不同的消费者进程请使用不同的设置
        $config->setGroupInstanceId('test'); // 分组实例ID，不同的消费者进程请使用不同的设置
        $config->setInterval(0.1);
        $consumer = new Consumer($config, function(ConsumeMessage $message){
            var_dump($message->getKey() . ':' . $message->getValue());
            // $consumer->ack($message); // autoCommit设为false时，手动提交
        });
        $consumer->start();
    }
}