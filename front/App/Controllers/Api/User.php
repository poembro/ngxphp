<?php 
/**
 * @Copyright (C),
 * @Author poembro
 * @Date: 2017-11-08 12:37:46
 * @Description User 传统的php框架思维中的控制器
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