<?php
/**
 * @Copyright (C),
 * @Author poembro
 * @Date: 2017-11-08 12:37:46
 * @Description Mysql PDO封装
 */
namespace Nig\Data;
 
class Mysql
{ 
    /**
     * 策略配置数组
     * @var array
     * @access protected
     */
    protected $_policy;
    
    /**
     * 连接标示
     * @var 资源 或者null
     * @access protected
     */
    protected $_conn;
    
    /** 
     * 重连次数
     * @var int
     * @access private
     */
    private $_relink = 0;
    
    /**
     * 统一的构造函数
     * @access public
     * @param array $policy 默认为空函数
     */
    public function __construct(array $policy = array())
    {
        $this->_policy = $policy; 
    }
    
    /**
     *  日子
     * @var array
     * @access private
     */
    public $log = array();

    /**
     * 开始连接
     * @return 资源|\PDO
     * @throws Exception
     */
    public function connect()
    {
        if ($this->_conn)
        {
            return $this->_conn;
        }
        $config = $this->_policy;  
        $options = (bool)$config['pconnect'] ? array ( \PDO::ATTR_PERSISTENT => true) : array ();  
        $dns = 'mysql:host=' . $config['hostname'] . ';port=' . $config['port'];
        $dns .= ';dbname=' . $config['dbname']; 
        try
        {
            $this->_conn = new \PDO($dns, $config['username'], $config['password'], $options);
        }
        catch (\Exception $e)
        {
            throw new \Exception($e->getMessage());
        } 
        $this->query('SET NAMES UTF8'); 
        $this->_conn->setAttribute(\PDO::ATTR_CASE, \PDO::CASE_LOWER); 
        return $this->_conn;
    }
  
    /**
     * 执行 SQL
     * @return \PDOStatement
     */
    public function query($sql)
    { 
        $connect = $this->connect();
        $this->_query = $connect->query($sql);
        if (! $this->_query)
        {
            $errInfo = $connect->errorInfo();
            if ((in_array($errInfo[1], array(2006, 2013))) && ($this->_relink < 3))
            {
                $this->_relink++;
                $this->close();
                $this->connect();
                return $this->query($sql);
            } 
        }
        else
       {
            $this->_relink = 0;
        } 
        return $this->_query;
    }
    
    /**
     * 关闭或者销毁实例和链接
     * @access void
     * @param void
     * @return void
     */
    public function close()
    {
        unset($this->_conn);
    }  
    
    public function fetchAll($sql, $fetch_style = \PDO::FETCH_BOTH )
    {
        return $this->query($sql)->fetchAll($fetch_style);
    }
     
    public function fetch($sql, $fetch_style = \PDO::FETCH_BOTH )
    {
        return $this->query($sql)->fetch($fetch_style);
    }
    
    public function update($sql)
    {
        return $this->query($sql)->rowCount();
    }
    
    public function insert($sql)
    {
         return $this->query($sql) ? $this->connect()->lastInsertId() : null;
    } 
}


