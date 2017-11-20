<?php
/**
 * @Copyright (C),
 * @Author poembro
 * @Date: 2017-11-08 12:37:46
 * @Description  index 入口文件初始化类
 */
define("DS", DIRECTORY_SEPARATOR);
define("FRAMEWORK_PATH", dirname(dirname(dirname(__DIR__))) . DS .'Library/Nig' . DS);
define("APPLICATION_PATH", dirname(dirname(__DIR__)) . DS .'App' . DS);
 
ini_set('display_errors',1);            
error_reporting(E_ALL);                     
 
include FRAMEWORK_PATH .'Nig.php'; 
$nig = \Nig\Nig::getInstance(APPLICATION_PATH . 'Config/Main.php');
 
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
 