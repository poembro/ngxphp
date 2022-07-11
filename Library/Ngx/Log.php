<?php 
/**
 * @Copyright (C),
 * @Author 张三
 * @Date: 2017-11-08 12:37:46
 * @Description Request 请求参数类
 */
namespace Ngx;

class Log
{
    /**
     * 日志
     * @var array
     * @access private
     */
    private static $log = [];
    
    /**
     * @desc 添加语句执行信息
     * @access public
     * @param string $sql sql语句
     * @return void
     */
    public static function addQuery($msg, $classname)
    {
        if (!Config::$_conf['sys']['debug']) 
        {
            return false;
        }
        array_push(self::$log, [$msg, $classname]);
        return true;
    }
    
    /**
     * @desc 获取语句执行信息
     * @access public
     * @return void
     */
    public static function getQuery()
    {
        return self::$log;
    }

    public static function outlog($_path)
    {
        if (!Config::$_conf['sys']['debug']) 
        {
            return false;
        }
        $filename = $_path . date('Ymd') . '.log';
        $msg = print_r(static::getQuery(), true);
        file_put_contents($filename, $msg . "\n", FILE_APPEND);
        return true;
    }
}
 
