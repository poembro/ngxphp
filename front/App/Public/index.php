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
define('UTF8_ENABLED', FALSE); 
error_reporting(E_ALL & ~E_NOTICE);    

ini_set('default_charset', 'UTF-8'); //设置默认字符集 
ini_set('date.timezone','Asia/Shanghai');
if (extension_loaded('mbstring'))
{
    @ini_set('mbstring.internal_encoding', 'UTF-8'); 
    mb_substitute_character('none');
}


function _main()
{
    try {
        include FRAMEWORK_PATH . 'Ngx.php';
        Ngx\Import::addLibrary(FRAMEWORK_PATH, 'Ngx');
        Ngx\Import::addLibrary(APPLICATION_PATH, 'App');
        // Ngx\Import::addLibrary(dirname(APPLICATION_PATH) . DS .'Sdk' . DS, 'Sdk'); 
        // Ngx\Import::addLibrary(dirname(APPLICATION_PATH) . DS .'Firebase' . DS, 'Firebase'); 

        Ngx\Ngx::getInstance()
            ->init(APPLICATION_PATH . 'Config/Main.php')
            ->run();
 
        $filename = APPLICATION_PATH . date('Ymd') . 'login.log';
        Ngx\Log::outlog($filename);
    }
    catch (\Exception $e)
    {
        $errMsg = '<html><head><title>error!</title></head><body bgcolor="white" text="black"><center>';
        $errMsg .= '<h1>' . $e->getFile() . ' line: ' . $e->getLine() . '</h1>';
        $errMsg .= '<h1>' . $e->getMessage() . '</h1>';
        $errMsg .= '</center></body></html>';
 
        $data = array(
            'ret' => 1,
            'msg' => $e->getMessage(),
            'data' => [],
            'timestamp' => time(),
        );
        //echo $errMsg; 
        echo json_encode($data);
    }
}
_main();
