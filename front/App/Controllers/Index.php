<?php  
/**
 * @Copyright (C),
 * @Author poembro
 * @Date: 2017-11-08 12:37:46
 * @Description  User 传统的php框架思维中的控制器
 */
namespace App\Controllers;

use App\Lib\Page;
use App\Lib\Image\ImageWrapper;

class Index
{
    /**
     * @desc   列表页
     * @access public
     * @param void
     * @return void
     */
    public function index($req, $res)
    {
        $roomid = 1000; 
        $option = array();
        $option['roomid'] = $roomid;
        
        $m = new \App\Model\User();
        $counts = $m->getCount($option);
        $result = array();
        $showPage = array(); 
        
        if ($counts > 0)
        {
            $p = isset($_GET['page']) ? $_GET['page']:1;
            $page = Page::get($counts, 5,  '',  $p);   
            $result =  $m->getUserList($option, $page['limit']); 
            $showPage = $page['page'];
        }
 
        $res->view->assign('list',  $result);
        $res->view->assign('page', $showPage); 
        $res->view->assign('option', $option);
        $res->view->display('/Main/index.php');
    }
}