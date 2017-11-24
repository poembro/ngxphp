<?php 
/**
 * @Copyright (C),
 * @Author poembro
 * @Date: 2017-11-08 12:37:46
 * @Description  User 传统的php框架思维中的模型层
 */
namespace App\Model;
 
class User extends \Nig\Data\Base
{
    public function getOne($key) 
    { 
        return $this->redisConn()->get($key);
    }

    public function setOne($key, $val)
    {
        return $this->redisConn()->set($key, $val);
    }
    
    public function getAll($key, $val)
    {
        return $this->mysqlConn()->query('select * form test');
    }
    

    /**
     * @desc 统计记录数
     * @access public
     * @param array $option 条件 数组
     * @return int
     */
    public function getCount($option)
    { 
    	return 16;
    }

    /**
     * @desc 用户列表
     * @access public
     * @param int $uid 用户uid
     * @return array
     */
    public function getUserList($option, $limit='')
    {
    	//$option['no_admin_id'] = 1;
    	$where = $this->_handleOption($option);
    	$where = $where ? ' WHERE ' . $where : '';
    	if($limit)
    	{
    		$limit = " LIMIT $limit";
    	}
    	$sql = 'SELECT * FROM mg_user' . $where . ' ORDER BY uid DESC ' . $limit;
    	
    	$data = [
	        ['id' => 1, 'name'=> 'hello world', 'age' =>24, 'sex'=>1 ],
	        ['id' => 2, 'name'=> 'hello world', 'age' =>24, 'sex'=>1 ],
	        ['id' => 3, 'name'=> 'hello world', 'age' =>24, 'sex'=>1 ],
	        ['id' => 4, 'name'=> 'hello world', 'age' =>24, 'sex'=>1 ],
	        ['id' => 5, 'name'=> 'hello world', 'age' =>24, 'sex'=>1 ],
	        ['id' => 6, 'name'=> 'hello world', 'age' =>24, 'sex'=>1 ],
	        ['id' => 7, 'name'=> 'hello world', 'age' =>24, 'sex'=>1 ],
	        ['id' => 8, 'name'=> 'hello world', 'age' =>24, 'sex'=>1 ],
	        ['id' => 9, 'name'=> 'hello world', 'age' =>24, 'sex'=>1 ],
	        ['id' => 10, 'name'=> 'hello world', 'age' =>24, 'sex'=>1 ],
	        ['id' => 11, 'name'=> 'hello world', 'age' =>24, 'sex'=>1 ],
	        ['id' => 12, 'name'=> 'hello world', 'age' =>24, 'sex'=>1 ],
	        ['id' => 13, 'name'=> 'hello world', 'age' =>24, 'sex'=>1 ],
	        ['id' => 14, 'name'=> 'hello world', 'age' =>24, 'sex'=>1 ],
	        ['id' => 15, 'name'=> 'hello world', 'age' =>24, 'sex'=>1 ],
	        ['id' => 16, 'name'=> 'hello world', 'age' =>24, 'sex'=>1 ],
    	];
    	return $data;
    }
    
    private function _handleOption($option)
    {
    	$where = array();
    	if (isset($option['uid']) && $option['uid'])
    	{
    		$uid = $option['uid'];
    		$where[] = is_array($uid) ? 'uid IN ('.join(',', $uid).')' : 'uid='.(int)$uid;
    	} 
    	
    	if (isset($option['group_id']) && $option['group_id'])
    	{
    		$where[] = 'group_id=' . (int)$option['group_id'];
    	}
    	
    	$where[] = 'visible=1';
    	return join(' AND ', $where);
    }
}