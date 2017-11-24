<?php  
/**
 * @Copyright (C),
 * @Author poembro
 * @Date: 2017-11-08 12:37:46
 * @Description  User 传统的php框架思维中的控制器
 */
namespace App\Controllers;

use Lib\Page;
use Lib\Image\ImageWrapper;

class Room
 
    /**
     * @desc   列表页
     * @access public
     * @param void
     * @return void
     */
    public function indexAction() 
    { 
        $roomid = trim($this->getRequest()->getParam('roomid'));
       
        $option = array();
        $option['roomid'] = $roomid;
        
        $counts = 100;
        $result = array();
        $showPage = array(); 
        if ($counts > 0)
        { 
            $page = Page::get($counts, 10,  '',  $this->page);
            $result = $this->dataChatRoomModel->getRoomList($option, $page['limit']);
            $showPage = $page['page'];
        }
         
        $this->_view->assign('list',  $result);
        $this->_view->assign('page', $showPage); 
        $this->_view->assign('option', $option);
        $this->getView()->display('room/index.phtml');
    }
    
    public function codeAction()
    {
    	ob_end_clean();
    	ImageWrapper::imgVerify(4, 3, 'gif', 90, 38);
    }
    
}
