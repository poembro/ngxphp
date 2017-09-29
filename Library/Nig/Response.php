<?php 
/**
 * @Copyright (C), 2017 poembro
 * @Name Response.php
 * @Author poembro 269724033@qq.com
 * @Version Beta 1.0
 * @Date: 2017-09-17 下午12:30:37
 * @Description   Response 响应处理类
 * @Class List
 * 1. Common
 * @Function List
 * 1.
 * @History
 * <author> <time> <version > <desc>
 * poembro 2017-09-17 下午16:30:37 Beta 1.0 第一次建立该文件
 */
namespace Nig;

use \Nig\View;

class Response
{
    public $view;
    
    private static $_instance;
 
    private function __construct($path)
    { 
        $this->view = View::getInstance();
        $this->view->setTemplateFolder( APPLICATION_PATH . 'View');
    }
 
    public static function getInstance($path = NULl)
    {
        if (! self::$_instance)
        {
            self::$_instance = new self($path);
        }
    
        return self::$_instance;
    }
     
    public function redirect($path)
    {
        
        return  \Nig\Nig::getInstance()->run($path);
    }
}
 
