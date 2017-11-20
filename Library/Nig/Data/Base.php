<?php
/**
 * @Copyright (C),
 * @Author poembro
 * @Date: 2017-11-08 12:37:46
 * @Description Base 数据处理基础类
 */
namespace Nig\Data;

use Nig\Data\Mysql;
use Nig\Data\Rdb;
use Nig\Config;

class Base
{
	/**
	 * 对象缓存
	 * @var array
	 * @access protected
	 */
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
 