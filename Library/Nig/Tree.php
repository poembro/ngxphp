<?php
/**
 * @Copyright (C),
 * @Author poembro
 * @Date: 2017-11-08 12:37:46
 * @Description 节点类
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
}

/**
 * @Copyright (C),
 * @Author poembro
 * @Date: 2017-11-08 12:37:46
 * @Description Tree 树 挂载节点类
 */
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
     
    public static function getNode(array $frags)
    {
    	$parent = self::$root;
    	$fallbackStack = [];
    	 
    	foreach ($frags as $v)
    	{
    		$node = self::getChildNode($parent, $v);
    		if (!$node)
    		{
    			continue;
    		}
    		$fallbackStack[] = $node;
    		$parent = $node;
    	}
    
    	return $fallbackStack;
    }
      
    public static function getChildNode(Node $node, $segment)
    { 
        foreach ($node->children as $it)
        {
            if ($segment === $it->name)
            {
                return $it;
            }
        }
        
        return false; 
    }

    private static function _getOrAdd(Node $parent, $segment)
    {
        $node = self::getChildNode($parent, $segment);
    
        if ($node)
        {
            return $node;
        }
    
        $node = clone $parent;
        $node->children = [];
        $node->handlers = [];
        $node->name = $segment;
         
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
}
