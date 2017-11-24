<?php

/**
 * 上传类
 * @author Aiden
 * @example $upload = new Upload();
 *          $config = array(
 *          'savePath' => './upload/', //保存目录
 *          'maxSize' => 1024, //文件最大字节，以K为单位,默认为2M
 *          'allowType' => 'txt,jpg,gif,zip,rar', //允许上传类型
 *          'isSaveOldName' => true, //新文件是否使用旧文件名称，默认为false
 *          );
 *          if (!$upload->uploadFile($_FILES['file'], $config))
 *          {
 *          echo $upload->getError();
 *          }
 *          else
 *          {
 *          print_r($upload->getFileInfo());
 *          }
 */ 
namespace App\Lib;

class FileUpload
{

	/**
	 * 存储上传文件的信息，包括大小，路径等
	 * @var array
	 * @access private
	 */
	private $_fileInfo = array ();

	/**
	 * 上传最大字节数(以K为单位),默认2M
	 * @var int
	 * @access private
	 */
	private $_maxSize = 2048;

	/**
	 * 上传目录
	 * @var string
	 * @access private
	 */
	private $_savePath = './Upload/';

	/**
	 * 错误信息
	 * @var string
	 * @access private
	 */
	private $_error = '';

	/**
	 * 允许上传的类型
	 * @var
	 *
	 *
	 * @access private
	 */
	private $_allowUploadType = array ();

	/**
	 * 是否保存旧文件的名称
	 * @var
	 *
	 *
	 * @access private
	 */
	private $_isSaveOldName = false;

	/**
	 * 上传文件临时保存在服务器的信息
	 * @var array
	 * @access private
	 */
	private $_tmpInfo = array ();

	/**
	 * 开始上传文件
	 * @access public
	 * @param array $file 上传文件信息，如: $_FILES['file'];
	 * @param array $config 上传配置信息，可以设置上传大小，上传路径，上传类型
	 * @example $flag = $upload->uploadFile($_FILES['file']);
	 * @return bool
	 */
	public function uploadFile($file, $config = array())
	{
		$this->_fileInfo = array ();
		if (is_array($config) && count($config) > 0)
		{
			$this->_handleConfig($config); /* 处理配置信息 */
		}
		if (! is_array($file))
		{
			return false;
		}
		$error = $file['error'];
		switch ($error)
		{
			case 0 :
				$this->_error = '';
				break;
			case 1 :
				$this->_error = '超过了php.ini中文件大小';
				break;
			case 2 :
				$this->_error = '超过了MAX_FILE_SIZE 选项指定的文件大小';
				break;
			case 3 :
				$this->_error = '文件只有部分被上传';
				break;
			case 4 :
				$this->_error = '没有文件被上传';
				break;
			case 5 :
				$this->_error = '上传文件大小为0';
				break;
			default :
				$this->_error = '上传文件失败！';
				break;
		}
		if (! empty($this->_error))
		{
			return false;
		}
		if (is_uploaded_file($file['tmp_name']))
		{
			$this->_tmpInfo = $file;
			$maxSize = $this->_maxSize * 1024;
			$this->_fileInfo['type'] = $this->_getFileType($file['name']);
			if ($file['size'] > $maxSize)
			{
				$this->_error = '文件太大，只能上传' . ceil($maxSize / 1024) . 'K以内的文件';
				return false;
			}
			elseif (! in_array($this->_fileInfo['type'], $this->_allowUploadType))
			{
				$this->_error = '文件类型不确定';
				return false;
			}
			$this->_mkSavePath($this->_savePath); // 生成保存目录
			$this->_fileInfo['name'] = $this->_getNewFileName();
			$this->_fileInfo['file_path'] = $this->_savePath . $this->_fileInfo['name'];
			move_uploaded_file($file['tmp_name'], $this->_fileInfo['file_path']);
			$this->_fileInfo['size'] = $file['size'];
			return true;
		}
		else
		{
			$this->_error = '上传出错';
			return true;
		}
	}

	/**
	 * 设置文件保存目录
	 * @access public
	 * @param string $path 文件保存目录
	 * @example $upload->setSavePath('/usr/www/myzmh/upload');
	 * @return void
	 */
	public function setSavePath($path)
	{
		if (! empty($path))
		{
			$path = rtrim($path, '/');
			$path = rtrim($path, '\\');
			$path .= DIRECTORY_SEPARATOR;
			$this->_savePath = $path;
		}
	}

	/**
	 * 设置上传文件最大字节
	 * @access public
	 * @param int $size 字节数，以K为单位
	 * @return void
	 */
	public function setMaxSize($size)
	{
		$size = (int)$size;
		$this->_maxSize = $size;
	}

	/**
	 * 设置允许上传的类型
	 * @access public
	 * @param 上传类型 以 "," 隔开，如: gif,jpg,rar
	 * @example $upload->setAllowType('gif,jpg,rar');
	 * @return void
	 */
	public function setAllowType($type)
	{
		if (! empty($type))
		{
			$type = trim($type, ',');
			$this->_allowUploadType = explode(',', $type);
		}
	}

	/**
	 * 上传是否发生错误
	 * @access public
	 * @return bool
	 */
	public function hasError()
	{
		return ! empty($this->_error);
	}

	/**
	 * 得到错误信息
	 * @access public
	 * @return string
	 */
	public function getError()
	{
		return $this->_error;
	}

	/**
	 * 得到上传文件信息
	 * @access public
	 * @return array
	 */
	public function getFileInfo()
	{
		return $this->_fileInfo;
	}

	/**
	 * 处理上传配置信息
	 * @access public
	 * @param array $config 配置信息数组
	 * @return void
	 */
	private function _handleConfig($config)
	{
		if (isset($config['savePath']))
		{
			$this->setSavePath($config['savePath']);
		}
		if (isset($config['maxSize']))
		{
			$this->setMaxSize($config['maxSize']);
		}
		if (isset($config['allowType']))
		{
			$this->setAllowType($config['allowType']);
		}
		if (isset($config['isSaveOldName']))
		{
			$this->_isSaveOldName = (bool)$config['isSaveOldName'];
		}
	}

	/**
	 * 递归生成文件保存目录
	 * @access public
	 * @param string $path 文件保存目录
	 * @return void
	 */
	private function _mkSavePath($path)
	{
		if (! file_exists($path))
		{
			$this->_mkSavePath(dirname($path));
			mkdir($path, 0777);
		}
	}

	/**
	 * 得到上传文件类型
	 * @access public
	 * @param string $filename 文件名
	 * @return string
	 */
	private function _getFileType($filename)
	{
		return strtolower(trim(substr(strrchr($filename, '.'), 1, 10)));
	}

	/**
	 * 得到新文件名称
	 * @access public
	 * @param 1
	 * @return string
	 */
	private function _getNewFileName()
	{
		$name = '';
		if ($this->_isSaveOldName)
		{
			$name = $this->_tmpInfo['name'];
		}
		else
		{
			$name = date('YmdHis', time()); // 当前时间
			$name .= strtoupper(substr(md5(rand()), 0, 4)); // 加上4个随机字符
			$name .= '.' . $this->_fileInfo['type'];
		}
		return $name;
	}

}