<?php

namespace Interpro\QuickStorage\Laravel\Collection;

use Interpro\QuickStorage\Concept\Collection\GroupCollection as GroupCollectionInterface;
use Interpro\QuickStorage\Laravel\Item\GroupItem;

class GroupCollection implements GroupCollectionInterface
{
    private $group_name;
    private $items = [];
    private $object_items = [];
    private $position = 0;

    public function __construct(
        $block_name,
        $group_name,
        $group_array,
        $owner_id = 0
    ){
        $this->group_name    = $group_name;
        $this->position      = 0;

        $this->items = & $group_array;
    }

    private function createItem($position)
    {
        if(array_key_exists('pos_'.$position, $this->object_items))
        {
            $item = $this->object_items['pos_'.$position];
        }else{
            $item = new GroupItem($this->items[$this->position]);
            $this->object_items['pos_'.$position] = $item;
        }

        return $item;
    }

    function rewind()
    {
        $this->position = 0;
    }

    function current()
    {
        return $this->createItem($this->position);
    }

    function key()
    {
        return $this->position;
    }

    function next()
    {
        ++$this->position;
    }

    function valid()
    {
        return isset($this->items[$this->position]);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->items);
    }

}
