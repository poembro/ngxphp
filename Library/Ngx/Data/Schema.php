<?php
/**
 * @Copyright (C),
 * @Author 张三
 * @Date: 2017-11-08 12:37:46
 * @Description 父类
 */
namespace Ngx\Data;

abstract class Schema 
{
    /**
     * @desc 添加语句执行信息
     * @access public
     * @param string $msg 语句
     * @return void
     */
    abstract public function addQuery($msg);

    /**
     * @desc   开始连接
     * @access public 
     * @param  void 
     * @return bool
     */
    abstract public function connect();
}