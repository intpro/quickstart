<?php

namespace Interpro\QuickStorage\Laravel\Collection;

use Interpro\QuickStorage\Concept\Collection\GroupCollection as GroupCollectionInterface;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Interpro\QuickStorage\Concept\StorageStructure;
use Interpro\QuickStorage\Laravel\Item\GroupItem;

class GroupCollection implements GroupCollectionInterface
{
    private $group_name;
    private $items = [];
    private $position = 0;
    private $storageStruct;
    private $depth;

    private function init($collection)
    {
        $this->items = [];

        foreach($collection as $item){
            if($item->group_name == $this->group_name)
            {
                $this->items[] = $item;
            }
        }
    }

    public function __construct(
        $group_name,
        StorageStructure $storageStruct,
        EloquentCollection $collection,
        $depth
    )
    {
        $this->group_name    = $group_name;
        $this->storageStruct = $storageStruct;
        $this->position      = 0;
        $this->depth         = $depth;

        $this->init($collection);
    }

    function rewind()
    {
        $this->position = 0;
    }

    function current()
    {
        return new GroupItem(
            $this->storageStruct,
            $this->items[$this->position],
            $this->items[$this->position]->groups,
            $this->depth
        );
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
