<?php
/**
 * @Copyright (C),
 * @Author poembro
 * @Date: 2017-11-08 12:37:46
 * @Description  Auth 传统的php框架思维中的控制器
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
