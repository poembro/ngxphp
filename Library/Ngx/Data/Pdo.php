<?php
/**
 * @Copyright (C),
 * @Author poembro
 * @Date: 2017-11-08 12:37:46
 * @Description Mysql PDO封装
 */
namespace Ngx\Data;

use Ngx\Log;

class Pdo extends Schema
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
     * @desc 添加语句执行信息
     * @access public
     * @param string $sql sql语句
     * @return void
     */
    public function addQuery($msg)
    {
        Log::addQuery($msg,  __CLASS__ . ':' . __METHOD__);
    }
    
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
            $this->_conn = @new \PDO($dns, $config['username'], $config['password'], $options);
        }
        catch (\Exception $e)
        { 
            throw new \Exception($e->getMessage());
        }

        $this->addQuery('连接...' . $dns);
        $this->query('SET NAMES UTF8'); 
        $this->_conn->setAttribute(\PDO::ATTR_PERSISTENT, true);
        $this->_conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->_conn->setAttribute(\PDO::ATTR_CASE, \PDO::CASE_LOWER); //将所有字段都小写显示
        
        return $this->_conn;
    }

    /**
     * @desc   格式化用于数据库的字符串
     * @access public 
     * @param  string $str 
     * @return string
     */
    public function escape($str)
    {
        //$str = mysqli_real_escape_string($this->_conn, $str);
        return $str;
    }

    /**
     * @desc   执行 SQL
     * @access public 
     * @param  string $sql 
     * @return PDOStatement
     */
    public function query($sql)
    { 
        $connect = $this->connect();
        $_query = $connect->query($sql);
        if (! $_query)
        {
            $errInfo = $connect->errorInfo();
            if ((in_array($errInfo[1], array(2006, 2013))) && ($this->_relink < 3))
            {
                $this->_relink++;
                $this->close();
                $this->connect();
                return $this->query($sql);
            }
            $this->addQuery('执行SQL失败:' . $sql . ' ');
        }
        else
        {
            $this->_relink = 0;
        }
        $this->addQuery($sql);
        
        return $_query;
    }
    

    /**
     * @desc   获取所有
     * @access public 
     * @param  string $sql 
     * @param  int $fetch_style 
     * @return void
     */
    public function fetchAll($sql, $fetch_style = \PDO::FETCH_ASSOC )
    {
        return $this->query($sql)->fetchAll($fetch_style);
    }
     
    /**
     * @desc   获取一条
     * @access public 
     * @param  string $sql 
     * @param  int $fetch_style 
     * @return void
     */
    public function fetch($sql, $fetch_style = \PDO::FETCH_ASSOC )
    { 
        return $this->query($sql)->fetch($fetch_style);
    }
    
    /**
     * @desc   更新
     * @access public 
     * @param  string $sql 
     * @return void
     */
    public function update($sql)
    { 
        return $this->query($sql)->rowCount();
    }
    
    /**
     * @desc   新增
     * @access public 
     * @param  string $sql 
     * @return void
     */
    public function insert($sql)
    {
        return $this->query($sql) ? $this->connect()->lastInsertId() : null;
    }

    /**
     * @desc   关闭连接
     * @access public 
     * @param  void 
     * @return void
     */
    public function close()
    {
        unset($this->_conn);
    }

    /**
     * @desc   处理插入语句
     * @access public 
     * @param  string $table 
     * @param  array $dataArray 
     * @return void
     */
    public function write($table, $dataArray) 
    {
        $field = "";
        $value = "";
        if( !is_array($dataArray) || count($dataArray)<=0) 
        {
            return false;
        }
        
        foreach ($dataArray as $key => $val)
        {
            $val = $this->escape($val); 
            $field .= $key . ',';
            $value .= "'" . $val . "',";
        }

        $field = substr($field, 0, -1);
        $value = substr($value, 0, -1);
        $sql = 'insert into ' . $table . '(' . $field . ')values(' . $value . ')'; 

        $res = $this->insert($sql);
        // var_dump($res);
        return $res;
    }
  
    /**
     * @desc   处理更新语句
     * @access public 
     * @param  string $table 
     * @param  array $dataArray 
     * @param  string $condition 
     * @return void
     */
    public function change($table, $dataArray, $condition = "") 
    {
        if( !is_array($dataArray) || count($dataArray)<=0) 
        {
            return false;
        }
        $str = ""; 
        foreach ($dataArray as $key => $val)
        {
            $val = $this->escape($val); 
            $str .= $key . "='" . $val . "',";
        }

        $str = substr($str, 0, -1);
        $sql = 'update ' . $table . ' set ' . $str . ' where 1=1 and ' . $condition;
        
        $res = $this->update($sql);
        // var_dump($res); 
        return $res;
    }
}


