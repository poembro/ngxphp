<?php
return [
    'sys' => [
        'defaultAction' => '/login/index',
        'view' =>  APPLICATION_PATH . 'View',
        'debug' => true,
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
        'auth'       => "",
        'servers'    => null
    ],
     
    'mysql' => [
        'adapter'  => 'Mysql',
        'hostname' => '127.0.0.1',
        'port'     => 3306,
        'username' => 'root',
        'password' => '123456',
        'dbname'   => 'xxxxxx',
        'charset'  => 'utf8',
        'pconnect' => true
    ],
    'oss' =>[
        'access_id'     => 'xxx',
        'access_key'    => 'xxx',
        'bucket'        => 'fhgames',
        'endpoint'      => 'http://xxx.aliyuncs.com', 
        'path' => 'xxx/xxx',
     ],
];