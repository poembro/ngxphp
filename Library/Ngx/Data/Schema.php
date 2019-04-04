<?php
/**
 * @Copyright (C),
 * @Author poembro
 * @Date: 2017-11-08 12:37:46
 * @Description 父类
 */
namespace Ngx\Data;

abstract class Schema 
{
    /**
     * @desc 添加语句执行信息
     * @access public
     * @param string $sql sql语句
     * @return void
     */
    abstract protected function addQuery($msg);
}