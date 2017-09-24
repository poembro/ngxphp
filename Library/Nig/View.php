<?php 
/**
 * @Copyright (C), 2017 poembro
 * @Name View.php
 * @Author poembro 269724033@qq.com
 * @Version Beta 1.0
 * @Date: 2017-09-17 下午12:30:37
 * @Description   View 视图处理类
 * @Class List
 * 1. Common
 * @Function List
 * 1.
 * @History
 * <author> <time> <version > <desc>
 * poembro 2017-09-17 下午16:30:37 Beta 1.0 第一次建立该文件
 */
namespace Nig;
 
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
