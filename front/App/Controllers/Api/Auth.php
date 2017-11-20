<?php 
/**
 * @Copyright (C), 2017 poembro
 * @Name Auth.php
 * @Author poembro 269724033@qq.com
 * @Version Beta 1.0
 * @Date: 2017-09-17 下午12:30:37
 * @Description   Auth 传统的php框架思维中的控制器
 * @Class List
 * 1. Common
 * @Function List
 * 1.
 * @History
 * <author> <time> <version > <desc>
 * poembro 2017-09-17 下午16:30:37 Beta 1.0 第一次建立该文件
 */
namespace App\Controllers\Api;

use App\Lib\Mcrypt;

class Auth 
{
    public $nig;
     
    public function test($res, $req) 
    {  
          $Mcrypt = new Mcrypt();
          echo $r = $Mcrypt->encrypt('hello world');
          echo PHP_EOL;
              	
          echo  $Mcrypt->decrypt($r);
          echo PHP_EOL;
          echo '999999999';
          return ; 
    }
    

    public function hello($res, $req)
    {
        echo '88888888';
        return ;
    }
    
    
}