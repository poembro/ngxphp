<?php
/**
 * @Copyright (C), 2011-, King.
 * @Name: Main.php
 * @Author: King
 * @Version: Beta 1.0
 * @Date: 2015-4-23下午10:11:39
 * @Description:
 * @Class List:
 * 1.
 * @Function List:
 * 1.
 * @History:
 * <author> <time> <version > <desc>
 * King 2015-4-23下午10:11:39 Beta 1.0 第一次建立该文件
 */ 

namespace App\Controllers;

use App\Lib\Page; 

class Login extends Base
{ 
    /**
     * @desc   列表页
     * @access public
     * @param void
     * @return void
     */
    public function index($req, $res)
    {
        $res->view->display('/mg/login.php');
    }
    
    public function test($req, $res)
    {
        $userModel = new \App\Model\Log(); 
        $userInfo = $userModel->test();
        var_dump($userInfo);
    }
    
}
