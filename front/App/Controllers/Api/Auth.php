<?php 
/**
 * @Copyright (C),
 * @Author poembro
 * @Date: 2017-11-08 12:37:46
 * @Description Auth 传统的php框架思维中的控制器
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