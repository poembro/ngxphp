<?php 
/**
 * @Copyright (C), 2017 poembro
 * @Name Request.php
 * @Author poembro 269724033@qq.com
 * @Version Beta 1.0
 * @Date: 2017-09-17 下午12:30:37
 * @Description   Request 请求参数类
 * @Class List
 * 1. Common
 * @Function List
 * 1.
 * @History
 * <author> <time> <version > <desc>
 * poembro 2017-09-17 下午16:30:37 Beta 1.0 第一次建立该文件
 */
namespace Nig;

class Request
{
    private $_conf;
    private static $_instance;
 
    private function __construct($path)
    {
        $this->_conf = $_REQUEST;
    }
 
    public static function getInstance($path = NULl)
    {
        if (! self::$_instance)
        {
            self::$_instance = new self($path);
        }
    
        return self::$_instance;
    }
    
    public function get($key)
    {
        return $this->_conf[$key];
    }
    
    public function set($key, $val) 
    {
        return $this->_conf[$key] = $val;
    }
}
 
