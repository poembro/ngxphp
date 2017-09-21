<?php  
/**
 * @Copyright (C), 2017 poembro
 * @Name Nig.php
 * @Author poembro 269724033@qq.com
 * @Version Beta 1.0
 * @Date: 2017-09-17 下午12:30:37
 * @Description   Nig 核心类
 * @Class List
 * 1. Common
 * @Function List
 * 1.
 * @History
 * <author> <time> <version > <desc>
 * poembro 2017-09-17 下午16:30:37 Beta 1.0 第一次建立该文件
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
        Import::addLibrary(APPLICATION_PATH);
        
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
        $url = strtolower($url);
        if ($url != DS)
        {
            $segments = explode(DS, $url);
            return array_filter($segments);
        }
        
        return [DS];
    }
 
    private static function _getNode(array $frags)
    {
        $parent = Tree::$root;
        $fallbackStack = [];
         
        foreach ($frags as $v)
        {
            $node = Tree::getChildNode($parent, $v);
            if (!$node)
            {
                continue;
            }
            $fallbackStack[] = $node; 
            
            $parent = $node;
        }
        
        return $fallbackStack;
    }
     

    private static function _handle(array $stack, $current)
    { 
        $flag = FALSE;
        foreach ($stack as $k => $node)
        {
            if (empty($node->handlers) || $current != $node->original)
            {
                continue;
            }
            
            foreach ($node->handlers as $func)
            {
                try 
              {
                    if (is_array($func))
                    {
                        $group = $func[0];
                        $method = $func[1];
                        (new $group)->$method(self::$req, self::$res); 
                        continue;
                    }
                    
                    $func(self::$req, self::$res); 
                }
                catch (\Exception $e) 
                {
                    throw new \Exception($e->getMessage());
                }
                $flag = TRUE;
            }
        }
        
        if (!$flag)
        {
            return 'Not Found!';
        }
    }
    
    public function useNode($url, $func)
    {
        $frags = self::_parseURL($url);
        
        $node = Tree::addNode(Tree::$root, $frags);
        $node->handlers[] = $func; 
        $node->original = $url;
    }
    
    public function addNode($class)
    {
        $group = 'App\Controllers\\' . $class;
        if (!class_exists($group))
        {
            return ; 
        }
        
        $methods = get_class_methods($group);  
        foreach ($methods as $m)
        {
            if (strncmp($m, '__', 2) === 0)
            {
                continue;
            } 
            $this->useNode($class . DS . $m, [$group, $m]);
        }
    }
    
    public function autoNode($url) 
    {
        if ($url === DS) return ;  
        $frags = self::_parseURL($url);  
        
        if (count($frags) > 1)
        {
            array_pop($frags);
        }
        $url = implode("\\", array_map("ucfirst", $frags)); 
        $this->addNode($url);
    }

    public function run($current) 
    {
        $frags = self::_parseURL($current);
        $stack = self::_getNode($frags); 
        return self::_handle($stack, $current);   
    }
}
  
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
