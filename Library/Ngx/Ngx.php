<?php  
/**
 * @Copyright (C), 
 * @Author 张三 
 * @Date: 2017-11-08 12:37:46
 * @Description 框架核心  
 */
namespace Ngx;

class Ngx
{
    private static $_instance = NULL;
    
    public static function getInstance()
    {
        if (! self::$_instance)
        {
            self::$_instance = new self();
        }
        
        return self::$_instance;
    }

    public function init($conf) 
    {
        Config::init($conf);
        //more TODO 
        return $this;
    }

    public function run() 
    {
        $rules = $this->_router();  
        $this->_dispatch($rules); 
    }
    
    private function _router()
    {
        $router = Router::getInstance(); 
        
        $rules = $router->getRule();
        if (!empty($rules)) 
        {
            return $rules;
        }
        
        $uri = Config::get('sys.defaultAction');
        return $router->setRule($uri);
    }

    private function _dispatch($rules)
    {
        $num = count($rules);
        if ($num < 2 || $num > 100) 
        {
            throw new \Exception(' URL Exception!');  
        }

        array_unshift($rules,  'App', 'Controllers'); 
        $method = array_pop($rules); 

        $className = implode('\\', array_map('ucfirst', $rules));
        if (!class_exists($className, true))
        {
            throw new \Exception($className . ' Controller not found!');
        }

        if (!method_exists($className, $method))
        {
            throw new \Exception($method . ' Method not found!');
        }

        $req = Request::getInstance(); 
        $res = Response::getInstance(); 

        $object = new $className($req, $res);
        return call_user_func_array([$object, $method], [$req, $res]);
    }

}


/**
 * @Copyright (C),
 * @Author 张三
 * @Date: 2017-11-08 12:37:46
 * @Description 自动加载类
 */
class Import
{
    private static $_libs = array();
    private static $_isInit = false;

    public static function addLibrary($path, $libPre = null)
    {
        if (!self::$_isInit)
        {
            self::_init();
        }
        if (!$libPre)
        {
            $libPre = basename($path);
        }
        elseif ('*' == $libPre)
        {
            return set_include_path(get_include_path() . PATH_SEPARATOR . $path);
        }
        self::$_libs[$libPre] = $path;
    }

    public static function load($className)
    {
        if (!strpos($className, '\\'))
        {
            return include_once($className . '.php');
        }
        $params = explode('\\', $className);
        $libName = array_shift($params);

        if (!self::$_libs[$libName])
        {
            return false;
        }
        $path = self::$_libs[$libName] . implode(DS, $params) . '.php';
        //var_dump($path);
        if (! is_file($path))
        {
            $path = dirname($path);
            $path = $path . DS . basename($path) . '.php';
            if (is_file($path))
            {
                include_once($path);
            }
        }
        else
        {
            include_once($path);
        }
    }

    private static function _init()
    {
        spl_autoload_register(array(__CLASS__, 'load'));
        self::$_isInit = true;
    }
}