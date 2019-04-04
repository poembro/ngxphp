<?php  
/**
 * @Copyright (C),
 * @Author poembro
 * @Date: 2017-11-08 12:37:46
 * @Description Config 配置文件处理
 */
namespace Ngx;

class Config
{
    public static $_conf;

    private static $_instance;
 
    public static function getInstance()
    {
        if (! self::$_instance)
        {
            self::$_instance = new self();
        }
    
        return self::$_instance;
    }
    
    public static function init($filepath)
    {
        if (!is_file($filepath))
        {
            throw new \Exception('找不到名为:' . $filepath . '的配置文件');
        }

        self::$_conf = include_once $filepath;
    }

    public static function get($name)
    {
        $value = self::$_conf;
        $list = explode('.', $name);
        $num = count($list);
        
        for ($i = 0; $i < $num; $i++)
        {
            if (isset($value[$list[$i]]))
            {
                $value = $value[$list[$i]];
            }
            else
            {
                $value = [];
                break;
            }
        }

        return $value;
    }
    
    public static function set($key, $val) 
    {
        return self::$_conf[$key] = $val;
    }
}
 
