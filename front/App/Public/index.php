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
    
    $res->redirect('/main/index');
    //$nig->run('/main/index'); 
    return ; 
});



$nig->useNode('/a/a', function($req, $res) {
    echo 7;
    return ;
});

$nig->useNode('/a/a/a', function($req, $res) {
    echo 6;
    return ;
});

$nig->useNode('/a', function($req, $res) {
    echo 8;
    return ;
});


//挂载一个方法
$nig->useNode('/main/index', function($req, $res) {
     echo 123456;
     return ;
});
 
$nig->useNode('/api/user', function($req, $res) {
     echo 2;
     return ;
});

$nig->useNode('/nig/index.php', function($req, $res) {
	echo 5;
	return ;
});
	
$nig->useNode('/nig/app/index.php', function($req, $res) {
	echo 6;
	return ;
});

$nig->useNode('/nig/app/public/index.php', function($req, $res) {
	$m = new \App\Model\User\Main();
	$m->setOne('hello', 123);
    return $m->getOne('hello');
});

 
if (PHP_SAPI === 'cli')
{
    //cli方式执行 [root@www Public]# php index.php /api/auth/test
    $_SERVER['REQUEST_URI'] = $_SERVER['argv'][1];
} 

$nig->autoNode($_SERVER['REQUEST_URI']);


echo $nig->run($_SERVER['REQUEST_URI']);
 
 