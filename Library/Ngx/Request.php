<?php 
/**
 * @Copyright (C),
 * @Author poembro
 * @Date: 2017-11-08 12:37:46
 * @Description Request 请求参数类
 */
namespace Ngx;

class Request
{
    private $_conf = [];
    private static $_instance;
 
    private function __construct()
    {
        $this->_conf = $_SERVER;
    }
 
    public static function getInstance()
    {
        if (! self::$_instance)
        {
            self::$_instance = new self();
        }
    
        return self::$_instance;
    }
    
    public function get($key = NULL)
    {
        if (isset($this->_conf[$key]))
        {
            return $this->_conf[$key];
        }
        return NULL;
    }
    
    public function set($key, $val = NULL) 
    {
        if (is_array($key))
        {
            $this->_conf = array_merge($this->_conf, $key);
        }
        else
        {
            $this->_conf[$key] = $val;
        }
    
        return true;
    }
}
 
