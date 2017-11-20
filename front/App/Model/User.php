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
}