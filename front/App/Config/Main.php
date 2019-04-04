<?php
/**
 * @Copyright (C),
 * @Author poembro
 * @Date: 2017-11-08 12:37:46
 * @Description Config 配置文件
 */
return [
    'ext' => [
        'defaultAction' => '/login/test',
        'view' =>  APPLICATION_PATH . 'View',
        'debug' => false,
        'env' => 'dev' // production
    ],
    'app' => [
                        
    ],
    'redis' => [
        'host'       => '127.0.0.1',
        'port'       => 6379,
        'lifetime'   => 3600,
        'persistent' => true,
        'options'    => null,
        'auth'       => null,
        'servers'    => null
    ],
    'mysql' => [
        'adapter'  => 'Mysql',
        'hostname'     => '127.0.0.1',
        'port'     => 3306,
        'username' => 'root',
        'password' => '123456',
        'dbname'   => 'test2',
        'charset'  => 'utf8',
        'pconnect' => true
    ] 
];