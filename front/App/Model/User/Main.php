<?php 
/**
 * @Copyright (C), 2017 poembro
 * @Name Main.php
 * @Author poembro 269724033@qq.com
 * @Version Beta 1.0
 * @Date: 2017-09-17 下午12:30:37
 * @Description   User 传统的php框架思维中的模型层
 * @Class List
 * 1. Common
 * @Function List
 * 1.
 * @History
 * <author> <time> <version > <desc>
 * poembro 2017-09-17 下午16:30:37 Beta 1.0 第一次建立该文件
 */
namespace App\Model\User;
 
class Main extends \Nig\Data\Base
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
}