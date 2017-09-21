<?php 
/**
 * @Copyright (C), 2017 poembro
 * @Name Node.php
 * @Author poembro 269724033@qq.com
 * @Version Beta 1.0
 * @Date: 2017-09-17 下午12:30:37
 * @Description   Node 节点类
 * @Class List
 * 1. Common
 * @Function List
 * 1.
 * @History
 * <author> <time> <version > <desc>
 * poembro 2017-09-17 下午16:30:37 Beta 1.0 第一次建立该文件
 */
namespace Nig;

class Node
{ 
    public $children;  
    public $handlers; 
    public $name;
    public $original;
    
    public function __construct()
    {
        $this->children = []; 
        $this->handlers = [];
        $this->name = NULL;
        
        return $this;
    }
    
    public function findChild($key)
    {
        foreach ($this->children as $node) 
        {   
            if ($key === $node->name) 
            {
                return $node;  
            }   
        }  

        return FALSE;
    }
}
