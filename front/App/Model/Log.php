<?php
namespace App\Model;


class Log  extends \App\Model\Hbase
{
    /**
     * @desc 操作日志
     * @var string
     * @access public
     */
    const TABLE_ADMIN_LOG = 'mg_admin_log';
  
    public function test()
    { 
        return 'hello world';
    }

    /**
     *　添加操作日志
     * @access public
     * @param array 操作日志
     * @return void
     */
    public function addLog($data)
    {
        if (!$data)
        {
            return false;
        }
        $data['dateline'] = time(); 
         
        return $this->mysql->write(self::TABLE_ADMIN_LOG, $data);
    }

    public function getAll($option,  $limit)
    { 
        $where = $this->_handleOption($option);
        $where = $where ? ' WHERE ' . $where : '';
        if ($limit)
        {
            $limit = " LIMIT $limit";
        }
        
        return  $this->mysql->fetchAll('SELECT * FROM ' . self::TABLE_ADMIN_LOG .$where.' ORDER BY id DESC  '. $limit); 
    }
 

    public function getCount($option)
    { 
        $where = $this->_handleOption($option);
        $where = $where ? ' WHERE ' . $where : '';
        $sql = 'SELECT count(*) as num FROM ' . self::TABLE_ADMIN_LOG . $where;
        
        $res = $this->mysql->fetch($sql); 
        return $res['num'];
    }
    
    /*
     * @desc 处理条件 数据
    * @access public
    * @param array $option 条件
    * @return array
    */ 
    private function _handleOption($option)
    {
        $where = array();
         
        
        if (isset($option['nickname']) && $option['nickname'])
        {
            $where[] = "nickname LIKE '%{$option['nickname']}%'";
        }
        
        if (isset($option['startTime']) && ! empty($option['startTime']))
        {
            $where[] = 'dateline > ' . strtotime($option['startTime']);
        }
        
        if (isset($option['endTime']) && ! empty($option['endTime']))
        {
            $where[] = 'dateline < ' . (strtotime($option['endTime']) + 86400);
        }

        $where[] = '1=1';
        return join(' AND ', $where);
    }
}