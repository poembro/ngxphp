<?php
/**
 * @Copyright (C),
 * @Author poembro
 * @Date: 2017-11-08 12:37:46
 * @Description  index 入口文件初始化类
 */
define("DS", DIRECTORY_SEPARATOR);
define("FRAMEWORK_PATH", dirname(dirname(dirname(__DIR__))) . DS .'Library' . DS . 'Ngx' . DS);
define("APPLICATION_PATH", dirname(dirname(__DIR__)) . DS .'App' . DS);


error_reporting(E_ALL);      

if (php_sapi_name() == 'cli-server') {
    if (preg_match('/\.(?:png|jpg|jpeg|gif|js|css|woff|woff2|ttf)(\?.*)?$/', $_SERVER['REQUEST_URI'])) {
        return false;
    }
}

if (PHP_SAPI === 'cli')
{
    $_SERVER['PATH_INFO'] = $_SERVER['argv'][1];
}

foreach ($_POST as $k => $v)
{
    if (is_string($v))
    {
        $_POST[$k] = addslashes($v);
    }
}

foreach ($_GET as $k => $v)
{
    if (is_string($v))
    {
        $_GET[$k] = addslashes($v);
    }
}

include FRAMEWORK_PATH . 'Ngx.php';
Ngx\Ngx::getInstance()
    ->init(APPLICATION_PATH . 'Config/Main.php')
    ->run($_SERVER['PATH_INFO']);