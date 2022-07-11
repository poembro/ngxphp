<?php 
/**
 * @Copyright (C),
 * @Author 张三
 * @Date: 2017-11-08 12:37:46
 * @Description Response 响应处理类
 */
namespace Ngx;

class Response
{
    public $view;
    private static $_instance;
 
    private function __construct()
    {
        $path = Config::get('sys.view');
        $this->view = View::getInstance();
        $this->view->setTemplateFolder($path);
    }
 
    public static function getInstance()
    {
        if (! self::$_instance)
        {
            self::$_instance = new self();
        }
    
        return self::$_instance;
    }
}
