<?php
/**
 * @Copyright (C),
 * @Author poembro
 * @Date: 2017-11-08 12:37:46
 * @Description Rdb redis封装类
 */
namespace Ngx\Data;

use Ngx\Log;

class Rdb extends Schema
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
        'persistent' => true,  /*是否使用持久链接*/
        'options'    => null,  /*设置选项*/
        'auth'       => null,
        'servers'    => null
    ];

    /**
     * @desc 添加语句执行信息
     * @access public
     * @param string $sql sql语句
     * @return void
     */
    public function addQuery($msg)
    {
        Log::addQuery($msg,  __CLASS__);
    }

    /**
     * @desc   初始化配置
     * @access public 
     * @param  array $policy 
     * @return void
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

        if (isset($policy['auth']) && !empty($policy['auth']))
        {
            $conn->auth($policy['auth']);
        }

        $this->_conn = $conn;
        /**
        $options = $policy['options'];
        //$options[\Redis::OPT_SERIALIZER] = \Redis::SERIALIZER_IGBINARY;  加上这句就502
        foreach ($options as $k => $v) 
        {
            $conn->setOption($k, $v);
        }
        */
        $this->addQuery('连接...' . print_r($policy, true));

        return $conn;
    }

    /**
     * @desc   调用连接实例的函数
     * @access public 
     * @param string $method 函数名称
     * @param array  $params 参数组 
     * @return unknown type
     */
    public function __call($method, $params)
    {
        $this->addQuery('执行...' . $method . print_r($params, true));
        return call_user_func_array([$this->connect(), $method], $params);
    }

    /**
     * @desc 关闭或者销毁实例和链接
     * @access public 
     * @param  void 
     * @return void
     */ 
    public function close()
    {
        $this->connect()->close();
    }
}