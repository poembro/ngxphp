<?php 
/**
 * @Copyright (C),
 * @Author poembro
 * @Date: 2017-11-08 12:37:46
 * @Description  Auth 传统的php框架思维中的控制器
 */
namespace App\Controllers;
 
class Auth extends Base
{   
	
	//存储uid 和 domain
	public function _get($key)
	{
		if (empty($key))
		{
			exit('获取uid-domain失败');
		}	
		$m = new \App\Model\User();
		return $m->getOne($key); 
	}
	 
	public function _set($key, $value)
	{
		if (empty($key))
		{
			exit('设置uid-domain失败');
		}
		$m = new \App\Model\User();
		return $m->setOne($key, $value);
	}
	 
	//云雷
    public function index($res, $req)
    {
    	$uid = $_GET['uid'];
    	$domain = $_GET['domain']; 
    	if (empty($uid) || empty($domain))
    	{
    		exit('认证失败');
    	}
    
        //判断是否授权
        if (!isset($_COOKIE['_TOOKEN']))
        {
        	$code = $_GET['code'];
    	    $state = $_GET['state'];
    	
        	//去授权
            if (empty($code)) 
            {
                $callback = urlencode('http://m.exiaotao.com/auth/index?domain=' . urlencode($domain) . '&uid='.$uid) ;
            	$appid = BASE::APPID;
            	$state = BASE::CODE;
            	$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$appid}&redirect_uri="
            	    . $callback . "&response_type=code&scope=snsapi_base&state={$state}#wechat_redirect";
            	header('location:' . $url);
            	exit();
            }
   
            $r = $this->_getOpenid($code, BASE::APPID, BASE::APPSECRET);
            setcookie("_TOOKEN", '1', time()+300);
        }
      
    	file_put_contents('./login.log', date("Y-m-d H:i:s", time()) . '--回到自来水--：' . json_encode([$_GET]) . "\n\r", FILE_APPEND);
    	  
        // 1. 回到自来水
        header('location: ' . urldecode($domain)); exit();
        return ;
    }
    
    //拿到openid
    private function _getOpenid($code, $appid, $appsecret)
    {
    	$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $appid 
    	    . "&secret=" . $appsecret . "&code=" . $code . "&grant_type=authorization_code"; 
    	return  $this->curlGet($url);
    }
     
    public function curlGet($url)
    {
    	$ch = curl_init();
    	// 设置选项，包括URL
    	curl_setopt($ch, CURLOPT_URL, $url);
    	curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    	curl_setopt($ch, CURLOPT_HEADER, 0);
    
    	if (stripos($url, "https://") !== FALSE)
    	{
    		//curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
    		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    	}
    	else
    	{
    		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
    		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);//严格校验
    	}
    	// 执行并获取HTML文档内容
    	$output = curl_exec($ch);
    	// 释放curl句柄
    	$err_code = curl_errno($ch);
    	curl_close($ch);
    
    	return json_decode($output, true);
    }
    
}
