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
        ExceptionHandle::init();
        
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
        $index = strpos($url, '?');
        if ($index !== false)
        { 
            parse_str(substr($url, $index + 1), $getArg); 
            Request::getInstance()->set($getArg); 
            
            $url = substr($url, 0, $index);
        }
        
        $url = strtolower($url);
        if ($url !== '/')
        {
            $segments = explode('/', $url);
            $segments = array_filter($segments);
            if (count($segments) > 100)
            {
            	return trigger_error('url parse error '. __FILE__ .':'. __LINE__, E_WARNING); 
            }    
            return $segments;
        }
    
        return ['/'];
    }
    
    public function useNode($url, $event)
    {
        $frags = self::_parseURL($url); 
        
        $node = Tree::addNode(Tree::$root, $frags); 
        if (strcasecmp($url, $node->original) === 0)
        {
            return false; 
        }
        $node->handlers[] = $event; 
        $node->original = $url;
    }
    
    public function autoNode($url) 
    {
        $frags = self::_parseURL($url);  
        
        if (count($frags) < 2)
        {
        	return trigger_error("url error !", E_WARNING); 
        }
        
        $method = array_pop($frags);
        $className = array_map("ucfirst", $frags); 
        $group = Config::get('ext')['index'] . implode("\\", $className);
        
        if (!class_exists($group, true) && !method_exists($group, $method))
        {
        	 return trigger_error("controllers or methods not found !", E_WARNING); 
        }
 
        $this->useNode($url, [new $group, $method]);
    }

    private static function _handle(array $stack, $url)
    {
    	foreach ($stack as $k => $node)
    	{
    		if (empty($node->handlers) ||
    		strcasecmp($url, $node->original) !== 0)
    		{
    			continue;
    		}
    
    		foreach ($node->handlers as $func)
    		{
    			try
    			{
    				return call_user_func_array($func, 
    						array(self::$req, self::$res));
    			}
    			catch (\Exception $e)
    			{
    				throw new \Exception($e->getMessage());
    			}
    		}
    	}
    	 
    	return 'Not Found!';
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
 
/**
 * @Copyright (C),
 * @Author poembro
 * @Date: 2017-11-08 12:37:46
 * @Description 开启后，把所有php默认样式的 warning、error等信息接替过来
 */
class ExceptionHandle
{
	private static $_isInit = false;

    public static function init()
    {
    	if (!self::$_isInit && Config::get('ext')['debug'])
        {
	        set_error_handler(array(__CLASS__, 'onError' ));
	        self::$_isInit = true;
        }    
    }
    
    /*
     * trigger_error("Value must be 1 or below",E_USER_WARNING);
     * E_USER_ERROR - 致命的用户生成的 run-time 错误。错误无法恢复。脚本执行被中断。
	 * E_USER_WARNING - 非致命的用户生成的 run-time 警告。脚本执行不被中断。
	 * E_USER_NOTICE - 默认。用户生成的 run-time 通知。在脚本发现可能有错误时发生，但也可能在脚本正常运行时发生。 
     */
    public static function onError($errno, $errstr, $errfile, $errline)
    {
        switch ($errno)
        {
            case E_ERROR:
                echo "ERROR: [ID $errno] $errstr (Line: $errline of $errfile) \n";
                exit("程序已经停止运行，请联系管理员。");  
            case E_WARNING:
               echo "WARNING: [ID $errno] $errstr (Line: $errline of $errfile) \n";
               exit("framework error");
               break;
        
            default:  //不显示Notice级的错误
                break;
        }
    }
     
}

