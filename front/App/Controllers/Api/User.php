<?php 
/**
 * @Copyright (C), 2017 poembro
 * @Name User.php
 * @Author poembro 269724033@qq.com
 * @Version Beta 1.0
 * @Date: 2017-09-17 下午12:30:37
 * @Description   User 传统的php框架思维中的控制器
 * @Class List
 * 1. Common
 * @Function List
 * 1.
 * @History
 * <author> <time> <version > <desc>
 * poembro 2017-09-17 下午16:30:37 Beta 1.0 第一次建立该文件
 */
namespace App\Controllers\Api;
 
class User 
{
    public $nig;
     
    public function get($res, $req) 
    {
        $m = new \App\Model\User();
        $resc = $m->getOne('user:001'); 
         
        $res->view->assign('res', $resc);
        $res->view->display('/Main/index.php');
    }
     
    public function set($res, $req)
    {
        $m = new \App\Model\User();
        return $m->setOne('user:001', '{a:3,b:4,name:zhangsan}'); 
    }
}