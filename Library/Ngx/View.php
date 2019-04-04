<?php 
/**
 * @Copyright (C),
 * @Author poembro
 * @Date: 2017-11-08 12:37:46
 * @Description View 视图处理类
 */
namespace Ngx;
 
class View 
{
    private $_variables = array ();
    private $_cacheContents;
    private $_templateFolder;
    private static $_instance;
   
    public static function getInstance()
    {
        if (! self::$_instance)
        {
            self::$_instance = new self();
        }
        
        return self::$_instance;
    }
    
    public function setTemplateFolder($path)
    {
        $this->_templateFolder = $path;
    }

    public function assign($key, $value = null)
    {
        if (is_array($key))
        {
            $this->_variables = array_merge($this->_variables, $key);
        }
        else
        {
            $this->_variables[$key] = $value;
        }
        
        return $this;
    }
 
    public function display($file)
    {
        echo $this->fetch($file);
    }
 
    public function fetch($file)
    {
        $path = $this->_templateFolder . $file;
        
        if (! is_file($path))
        {
            throw new \Exception($path . '不存在或者不是一个文件!');
        }
        
        extract($this->_variables, EXTR_SKIP);
        ob_start();
        
        include $path;
        
        $this->_cacheContents = ob_get_contents();
        ob_clean();
        
        return $this->_cacheContents;
    }
}
?>
