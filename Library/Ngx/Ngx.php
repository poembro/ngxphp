<?php  
/**
 * @Copyright (C), 
 * @Author poembro 
 * @Date: 2017-11-08 12:37:46
 * @Description 框架核心  
 */
namespace Ngx;
   
use \Ngx\Request;
use \Ngx\Response;
use \Ngx\Config;

class Ngx
{
    public static $req = NULL;
    public static $res = NULL; 
    public static $view = NULL;
    private static $_instance = NULL;
    
    public function __construct()
    {
        Import::addLibrary(FRAMEWORK_PATH, 'Ngx');
        Import::addLibrary(APPLICATION_PATH, 'App');
    }
    
    public static function getInstance()
    {
        if (! self::$_instance)
        {
            self::$_instance = new self();
        }
        
        return self::$_instance;
    }

    public function init($path) 
    {
        Config::getInstance($path);
        self::$req = Request::getInstance(); 
        self::$res = Response::getInstance();  
        //more TODO
        return $this;
    }

    private function _parseURL($url)
    {
        $url = empty($url) ? '/' : strtolower($url); 
        if ($url !== '/')
        {
            $url = str_replace('/', ' ', $url);
            $segments = explode(' ', trim($url));
            if (count($segments) < 2 || count($segments) > 100)
            {
                throw new \Exception('url parse error!'); 
            }
            return $segments;
        }
        
        return $this->_parseURL(Config::get('ext.defaultAction'));
    }
    
    public function run($url) 
    {
        try
        {
            $frags = $this->_parseURL($url);
            $method = array_pop($frags);
            $action = implode('\\', array_map('ucfirst', $frags));
            $className = 'App\Controllers\\' . $action;
            
            if (!class_exists($className, true))
            {
                throw new \Exception('controller not found!');
            }

            if (!method_exists($className, $method))
            {
                throw new \Exception('method not found!');
            }
            
            $object = new $className(self::$req, self::$res);
            return call_user_func_array([$object, $method], [self::$req, self::$res]);
        }
        catch (\Exception $e)
        {
            $errMsg = '<html><head><title>error!</title></head><body bgcolor="white" text="black"><center>';
            $errMsg .= '<h1>' . $e->getFile() . ' line: ' . $e->getLine() . '</h1>';
            $errMsg .= '<h1>' . $e->getMessage() . '</h1>';
            $errMsg .= '</center></body></html>';

            if (Config::get('ext')['env'] === 'dev')
            {
                echo $errMsg;
            }
        }
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