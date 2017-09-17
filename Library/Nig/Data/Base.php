<?php  
/**
 * @Copyright (C), 2017 poembro
 * @Name Base.php
 * @Author poembro 269724033@qq.com
 * @Version Beta 1.0
 * @Date: 2017-09-17 下午12:30:37
 * @Description   Base  数据处理基础类
 * @Class List
 * 1. Common
 * @Function List
 * 1.
 * @History
 * <author> <time> <version > <desc>
 * poembro 2017-09-17 下午16:30:37 Beta 1.0 第一次建立该文件
 */
namespace Nig\Data;

use Nig\Data\Mysql;
use Nig\Data\Rdb;
use Nig\Config;

class Base
{
    private static $_conn = []; 
    
    private function _getConfig($key)
    { 
    	return Config::get($key); 
    }
    
    public function mysqlConn($key = 'mysql') 
    {
        if (isset(self::$_conn[$key]))
        {
            return self::$_conn[$key];
        }
        self::$_conn[$key] = new Mysql($this->_getConfig($key));
        return self::$_conn[$key];
    }
    
    public function redisConn($key = 'redis')
    {
        if (isset(self::$_conn[$key]))
        {
            return self::$_conn[$key];
        }
        
        $conf = $this->_getConfig($key);
        self::$_conn[$key] = Rdb::getInstance($conf);
        return self::$_conn[$key];
    }
}
 