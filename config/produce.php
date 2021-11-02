<?php
/**
 * @author: Xiao Nian
 * @contact: xiaonian030@163.com
 * @datetime: 2021-10-30 14:01
 */

//SWOOLE 1 其他 0
return [
    'EVENT_LOOP'=>1,
    'HTTP_SERVER'    => [
        'SERVER_NAME'    => 'HTTP_SERVER',
        'PROCESS_COUNT'     => 4,  //进程数
        'LISTEN_ADDRESS' => '0.0.0.0',
        'PORT'           => 5151
    ]
];
