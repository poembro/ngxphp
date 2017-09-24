<?php 
/**
 * @Copyright (C), 2017 poembro
 * @Name Config.php
 * @Author poembro 269724033@qq.com
 * @Version Beta 1.0
 * @Date: 2017-09-17 下午12:30:37
 * @Description   Config 配置文件处理
 * @Class List
 * 1. Common
 * @Function List
 * 1.
 * @History
 * <author> <time> <version > <desc>
 * poembro 2017-09-17 下午16:30:37 Beta 1.0 第一次建立该文件
 */
namespace Nig;

class Config
{
    public static $_conf;
    private static $_instance;
 
    private function __construct($path = NULl)
    {
        $path = $path ? $path : APPLICATION_PATH . 'Config/Config.php';
        
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
 
