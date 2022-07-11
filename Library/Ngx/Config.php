<?php  
/**
 * @Copyright (C),
 * @Author 张三
 * @Date: 2017-11-08 12:37:46
 * @Description Config 配置文件处理
 */
namespace Ngx;

class Config
{
    /**
     * @desc   缓存对象数组
     * @access public 
     * @return array
     */
    public static $_conf; 

    /**
     * @desc   初始化配置
     * @access public 
     * @param  string $filepath 
     * @return void
     */ 
    public static function init($filepath)
    {
        if (!is_file($filepath))
        {
            throw new \Exception('not find:' . $filepath);
        }

        self::$_conf = include_once $filepath;
    }

    /**
     * @desc   获取配置
     * @access public 
     * @param  string $name 
     * @return void
     */ 
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
    
    /**
     * @desc   写入配置
     * @access public 
     * @param  string $key 
     * @param  void $val
     * @return void
     */ 
    public static function set($key, $val) 
    {
        return self::$_conf[$key] = $val;
    }
}
 
