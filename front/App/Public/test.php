<?php
/**
 * @Copyright (C), 2017 poembro
 * @Name index.php
 * @Author poembro 269724033@qq.com
 * @Version Beta 1.0
 * @Date: 2017-09-17 下午12:30:37
 * @Description   index 入口文件初始化类
 * @Class List
 * 1. Common
 * @Function List
 * 1.
 * @History
 * <author> <time> <version > <desc>
 * poembro 2017-09-17 下午16:30:37 Beta 1.0 第一次建立该文件
 */
define("DS", DIRECTORY_SEPARATOR);
define("FRAMEWORK_PATH", dirname(dirname(dirname(__DIR__))) . DS .'Library/Nig' . DS);
define("APPLICATION_PATH", dirname(dirname(__DIR__)) . DS .'App' . DS);
 
ini_set('display_errors',1);            
error_reporting(E_ALL);                     
 
include FRAMEWORK_PATH .'Nig.php'; 
$nig = \Nig\Nig::getInstance(APPLICATION_PATH . 'Config/Config.php');
 
$nig->useNode('/', function($req, $res) {
     
    return 1; 
});

$nig->useNode('/', function($req, $res) {
     
    return 54;
});
    
$nig->useNode('/b', function($req, $res) {
   
    return 2;
});

$nig->useNode('/b/c', function($req, $res) {
     
    return 3;
});

$nig->useNode('/b/c', function($req, $res) {
     
    return 4;
});

$nig->useNode('/b/c/d', function($req, $res) {
     
    return 5;
});
    
 
if (PHP_SAPI === 'cli')
{
    //cli方式执行 [root@www Public]# php index.php /api/auth/test
    $_SERVER['REQUEST_URI'] = isset($_SERVER['argv'][1]) ? $_SERVER['argv'][1] : '/';
} 

echo $nig->run($_SERVER['REQUEST_URI']);
echo PHP_EOL;
 