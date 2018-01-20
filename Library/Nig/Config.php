<?php  
/**
 * @Copyright (C),
 * @Author poembro
 * @Date: 2017-11-08 12:37:46
 * @Description Config 配置文件处理
 */
namespace Nig;

class Config
{
    public static $_conf;
    private static $_instance;
 
    private function __construct($path = NULl)
    {
        $path = $path ? $path : APPLICATION_PATH . 'Config/Main.php';
        
        if (!file_exists($path))
        {
            echo '配置文件不存在';
            return ;
        }
        
        self::$_conf = include_once $path;
    }
 
    public static function getInstance($path = NULl)
    {
        if (! self::$_instance)
        {
            self::$_instance = new self($path);
        }
    
        return self::$_instance;
    }
    
    public static function get($key)
    {
        if (!$key) 
        {
            return self::$_conf;
        }
        
        return self::$_conf[$key];
    }
    
    public static function set($key, $val) 
    {
        return self::$_conf[$key] = $val;
    }
}
 
