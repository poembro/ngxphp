<?php
/**
 * @Copyright (C), 2017 poembro
 * @Name Tree.php
 * @Author poembro 269724033@qq.com
 * @Version Beta 1.0
 * @Date: 2017-09-17 下午12:30:37
 * @Description   Tree 树 挂载节点类
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

        return false;
    }
}



class Tree
{
    public static $root;
    
    private static $instance;
     
    public static function  getInstance()
    {
        if (self::$instance instanceof Tree)
        {
            return self::$instance;
        }
        
        self::$instance = new self();
        self::$root = new Node();
        
        return self::$instance;
    }
    
    private static function _getOrAdd(Node $parent, $frags)
    {
        $node = $parent->findChild($frags);
        
        if ($node) 
        {
            return $node;
        }
        
        $node = clone $parent;
        $node->children = [];
        $node->handlers = [];
        $node->name = $frags;
         
        $parent->children[] = $node;  
        return $node;
    }
    
    public static function addNode(Node $parent, array $frags)
    {
        $item = array_shift($frags);
        $child = self::_getOrAdd($parent, $item);
       
        if (empty($frags))
        {
            return $child;
        }
        
        return self::addNode($child, $frags);
    }
      
    public static function getChildNode(Node $parent, $segment)
    {
        if (! $parent)
        {
            return false;
        }   
        return $parent->findChild($segment);
    }
}
