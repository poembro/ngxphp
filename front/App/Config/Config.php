<?php  
/**
 * @Copyright (C), 2017 poembro
 * @Name Config.php
 * @Author poembro 269724033@qq.com
 * @Version Beta 1.0
 * @Date: 2017-09-17 下午12:30:37
 * @Description   Config 配置文件
 * @Class List
 * 1. Common
 * @Function List
 * 1.
 * @History
 * <author> <time> <version > <desc>
 * poembro 2017-09-17 下午16:30:37 Beta 1.0 第一次建立该文件
 */
return [
    'main' => [ 
         
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
        'pconnect' => false
    ] 
];