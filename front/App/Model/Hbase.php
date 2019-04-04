<?php 
/**
 * @Copyright (C),
 * @Author poembro
 * @Date: 2017-11-08 12:37:46
 * @Description  User 传统的php框架思维中的模型层
 */
namespace App\Model;

use \Ngx\Data\Base;

class Hbase extends Base
{
    public $mysql;
    
    public function __construct()
    {
        $this->mysql = $this->mysql('mysql');
    }
    
}
