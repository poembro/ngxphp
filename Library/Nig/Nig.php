<?php  
/**
 * @Copyright (C), 
 * @Author poembro 
 * @Date: 2017-11-08 12:37:46
 * @Description 框架核心  
 */
namespace Nig;
  
use \Nig\Tree; 
use \Nig\Request;
use \Nig\Response;
use \Nig\Config;

class Nig
{
    public static $req = NULL;
    public static $res = NULL;
    public static $tree = NULL;
    public static $view = NULL; 
    public static $conf = NULL;
    private static $_instance;
    
    public function __construct($confPath = NULL)
    {
        Import::addLibrary(FRAMEWORK_PATH, 'Nig');
        Import::addLibrary(APPLICATION_PATH, 'App');
        
        self::$conf = Config::getInstance($confPath);
        self::$tree = Tree::getInstance();
        self::$req = Request::getInstance(); 
        self::$res = Response::getInstance();
    }
    
    public static function getInstance($confPath = NULL)
    {
        if (! self::$_instance)
        { 
            self::$_instance = new self($confPath);
        }
        
        return self::$_instance;
    }

    private static function _parseURL($url)
    {
        if ($url !== '/')
        {
            $url      = parse_url($url, PHP_URL_PATH);
            $segments = explode('/', strtolower($url));
            $segments = array_filter($segments);
            
            if (count($segments) > 100)
            {
                return trigger_error('nig: url parse error ', E_USER_ERROR); 
            }
            return $segments;
        }
        
        $config= Config::get('ext');
        $defaultControllers = $config['defaultControllers'];
        $defaultAction = $config['defaultAction'];
        return [$defaultControllers, $defaultAction];
    }
    
    public function useNode($url, $event)
    {
        if (is_array($url))
        { 
            $frags = $url[1];
            $url = $url[0];
        }
        else 
        {
            $frags = self::_parseURL($url); 
        }
        
        $node = Tree::addNode(Tree::$root, $frags); 
        if (strcasecmp($url, $node->original) === 0)
        {
            return false; 
        }
        
        $node->handlers[] = $event; 
        $node->original = $url;
        
        return $this;
    }
    
    public function autoNode($url) 
    {
        $frags = $flagArg = self::_parseURL($url);  
         
        if (count($frags) < 2)
        {
            return $this;
        }
        
        $method = array_pop($frags);
        $classNameArr = array_map("ucfirst", $frags); 
        $tmp = implode("\\", $classNameArr);
        $className = Config::get('ext')['index'] . $tmp;
        
        if (!class_exists($className, true)  
            || !method_exists($className, $method))
        {
             return trigger_error("nig: controllers or methods not found !",
                 E_USER_ERROR); 
        }
        
        $object = new $className(self::$req, self::$res);
        $this->useNode([$url, $flagArg], [$object, $method]);
        
        return $this;
    }

    private static function _handle(array $stack, $url)
    {
        foreach ($stack as $k => $node)
        {
            if (empty($node->handlers) || strcasecmp($url, 
                $node->original) !== 0)
            {
                continue;
            }
    
            foreach ($node->handlers as $func)
            {
                return call_user_func_array($func, 
                    [self::$req, self::$res]);
            }
        }
    }
    
    public function run($current) 
    { 
        $frags = self::_parseURL($current);
        $stack = Tree::getNode($frags); 
        return self::_handle($stack, $current);   
    }
}


  
/**
 * @Copyright (C),
 * @Author poembro
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