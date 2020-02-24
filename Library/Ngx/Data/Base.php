<?php
/**
 * @Copyright (C),
 * @Author poembro
 * @Date: 2017-11-08 12:37:46
 * @Description Base 数据处理基础类
 */
namespace Ngx\Data;

use Ngx\Data\Pdo;
use Ngx\Data\Rdb;
use Ngx\Config;

class Base
{
    /**
     * 对象缓存
     * @var array
     * @access protected
     */
    private static $_conn = []; 

    /**
     * 获取配置
     * @return 配置|array
     * @throws Exception
     */
    final private function _getconfig($key)
    {
        if (!$key)
        {
            throw new \Exception("Data config error ! \r\n");
        }
        return Config::get($key); 
    }
    
    /**
     * 获取mysql对象
     * @return mysql对象|\PDO 
     */
    final public function mysql($key = 'mysql') 
    {
        if (isset(self::$_conn[$key]))
        {
            return self::$_conn[$key];
        }
        
        $conf = $this->_getconfig($key);

        self::$_conn[$key] = new Pdo($conf); 
        return self::$_conn[$key];
    }

    /**
     * 获取redis对象
     * @return redis对象|\Rdb 
     */
    final public function redis($key = 'redis')
    {
        if (isset(self::$_conn[$key]))
        {
            return self::$_conn[$key];
        }
        
        $conf = $this->_getconfig($key);
        
        self::$_conn[$key] = Rdb::getInstance($conf);
        return self::$_conn[$key];
    }
}
 