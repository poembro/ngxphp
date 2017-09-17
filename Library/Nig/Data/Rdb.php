<?php
/**
 * @Copyright (C), 2017 poembro
 * @Name Rdb.php
 * @Author poembro 269724033@qq.com
 * @Version Beta 1.0
 * @Date: 2017-09-17 下午12:30:37
 * @Description   Rdb redis封装类
 * @Class List
 * 1. Common
 * @Function List
 * 1.
 * @History
 * <author> <time> <version > <desc>
 * poembro 2017-09-17 下午16:30:37 Beta 1.0 第一次建立该文件
 */
namespace Nig\Data;

class Rdb
{

    /**
     * @desc   连接实例
     * @var Redis
     * @access protected
     */
    protected $_conn;

    /**
     * @desc    默认的服务器缓存策略
     * @var  array
     * @access  protected
     */
    protected $_policy = [
        'host'       => '127.0.0.1',
        'port'       => 6379,
        'lifetime'   => 3600,  /*缓存生命周期*/
        'persistent' => true, /*是否使用持久链接*/
        'options'    => null,    /*设置选项*/
        'auth'       => null,
        'servers'    => null
    ];

    
    /**
     * @desc View当前实例
     * @var View
     * @access private
     */
    private static $_instance;
    
    /**
     * @desc 获取当前视图实例
     * @access public
     * @param void
     * @return View
     */
    public static function getInstance(array $policy)
    {
        if (! self::$_instance)
        {
            self::$_instance = new self($policy);
        }
        return self::$_instance;
    }
    
    /**
     * @param array $policy
     */
    public function __construct(array $policy)
    {
        $this->_policy = array_merge($this->_policy, $policy); 
    }

    /**
     * @desc   开始连接
     * @access public 
     * @param  void 
     * @return bool
     */
    public function connect()
    {
        if ($this->_conn) 
        {     
            return $this->_conn;
        }
        
        $policy = $this->_policy;
        $policy['port'] = isset($policy['port']) ? (int)$policy['port'] : 6379;
        
        $conn = new \Redis();
        if ($policy['persistent']) 
        { 
            $conn->pconnect($policy['host'], $policy['port'], $policy['lifetime']);
        } 
        else 
       {  
            $conn->connect($policy['host'], $policy['port'], $policy['lifetime']);
        }

        $this->_conn = $conn;
        $options = [];
        //$options[\Redis::OPT_SERIALIZER] = \Redis::SERIALIZER_IGBINARY;  加上这句就502
        foreach ($options as $k => $v) 
        {
            $conn->setOption($k, $v);
        }

        return $conn;
    }

    /**
     * @desc   调用连接实例的函数
     * @access public
     *
     * @param string $method 函数名称
     * @param array  $params 参数组
     *
     * @return unknown type
     */
    public function __call($method, $params)
    {
        return call_user_func_array([$this->connect(), $method], $params);
    }

    /**
     * @desc   关闭或者销毁实例和链接
     * @access void
     *
     * @param void
     *
     * @return void
     */
    public function close()
    {
        $this->connect()->close();
    }
}