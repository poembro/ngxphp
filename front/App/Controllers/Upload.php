<?php
/**
 * @Copyright (C),
 * @Author poembro
 * @Date: 2017-11-08 12:37:46
 * @Description  User 传统的php框架思维中的控制器
 */
namespace App\Controllers;

class Upload
{
    /**
     * @desc 不检测登陆
     * @var public
     * @access protected
     */
    protected $_isCheckLogin = true;
    
	/**
	 * @desc 上传图片
	 * @access public 
	 * @param void
	 * @return void
	 */
	public function imageAction()
	{
		/*
		$str = var_export($_FILES, true); 
		$str .= var_export($this->response->getHeaders(), true);
	    file_put_contents('./text.txt', $str);
	   */
		$m = new App\Model\Upload();
		$ret = $m->image('Filedata');
		if ($ret['ret'] == 1)
		{
			return $this->outJson(1001, $ret['msg']);
		}
		
		$this->outJson(0, 'ok', array('id' => time(), 'url' => $ret['data']['url']));
	}

	/**
	 * @desc 上传文件
	 * @access public
	 * @param  void
	 * @return json
	 */
	public function fileAction()
	{
		$ret = $this->dataUploadModel->file('Filedata');
		if ($ret['ret'] == 1)
		{
			return $this->outJson(1001, $ret['msg']);
		}
	
		$this->outJson(0, 'ok', $ret['data']);
	}
}
?>
