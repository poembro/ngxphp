<?php 
/**
 * @Copyright (C),
 * @Author poembro
 * @Date: 2017-11-08 12:37:46
 * @Description Response 响应处理类
 */
namespace Nig;

use \Nig\View;
use \Nig\Config;

class Response
{
    public $view;
    
    private static $_instance;
 
    private function __construct($path)
    { 
        $this->view = View::getInstance();
        $this->view->setTemplateFolder( Config::get('ext')['View'] );
    }
 
    public static function getInstance($path = NULl)
    {
        if (! self::$_instance)
        {
            self::$_instance = new self($path);
        }
    
        return self::$_instance;
    }
}
