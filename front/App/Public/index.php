<?php
/**
 * @Copyright (C),
 * @Author poembro
 * @Date: 2017-11-08 12:37:46
 * @Description  index 入口文件初始化类
 */
define("DS", DIRECTORY_SEPARATOR);
define("FRAMEWORK_PATH", dirname(dirname(dirname(__DIR__))) . DS .'Library' . DS . 'Nig' . DS);
define("APPLICATION_PATH", dirname(dirname(__DIR__)) . DS .'App' . DS);

ini_set('display_errors', 1);            
error_reporting(E_ALL);

if (PHP_SAPI === 'cli')
{
	$_SERVER['REQUEST_URI'] = $_SERVER['argv'][1];
}

include FRAMEWORK_PATH .'Nig.php'; 
 
echo Nig\Nig::getInstance(APPLICATION_PATH . 'Config/Main.php')
        ->autoNode($_SERVER['REQUEST_URI'])
        ->run($_SERVER['REQUEST_URI']);



