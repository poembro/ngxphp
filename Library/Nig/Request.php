<?php 
/**
 * @Copyright (C),
 * @Author poembro
 * @Date: 2017-11-08 12:37:46
 * @Description Request 请求参数类
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
 
