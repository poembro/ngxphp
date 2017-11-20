<?php
/**
 * @Copyright (C),
 * @Author poembro
 * @Date: 2017-11-08 12:37:46
 * @Description  AES加解密
 */
namespace App\Lib;

class Mcrypt
{
	const IV  = 'fedcba9876543210';
	const SALT = '0123456789abcdef';
	const KEY = 'AKeyForAES128CBC';
	
	public function encrypt($data)
	{ 
		return bin2hex(openssl_encrypt($data, "AES-128-CBC", 
				(Mcrypt::KEY).(Mcrypt::SALT), OPENSSL_RAW_DATA, Mcrypt::IV)); 
	}

	public function decrypt($res)
	{  
		return openssl_decrypt(hex2bin($res), "AES-128-CBC", 
				(Mcrypt::KEY).(Mcrypt::SALT), OPENSSL_RAW_DATA, Mcrypt::IV);
	}
}