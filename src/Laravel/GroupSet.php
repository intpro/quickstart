<?php

namespace Interpro\QuickStorage\Laravel;

use Interpro\QuickStorage\Concept\Exception\BuildQueryException;

trait GroupSet
{
    private $items = [];
    private $keys = [];
    private $position = 0;
    private $currentGroup;

    function getGroups()
    {
        //Костыль
        return array_keys($this->items);
    }

    function rewind()
    {
        $this->position = 0;
    }

    function current()
    {
        return $this->items[$this->currentGroup][$this->position];
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
        if(!isset($this->currentGroup))
        {
            throw new BuildQueryException('Не установлена текущая группа для итерации.');
        }

        return isset($this->items[$this->currentGroup][$this->position]);
    }

    /**
     * @param string $group_name
     *
     * @return void
     */
    public function setCurrentGroup($group_name)
    {
        $this->addEmptyGroup($group_name);

        $this->currentGroup = $group_name;
    }

    private function addEmptyGroup($group_name)
    {
        if(!array_key_exists($group_name, $this->items))
        {
            $this->items[$group_name] = [];
            $this->keys[$group_name] = [];
        }
    }

    public function reset($group_name)
    {
        if(array_key_exists($group_name, $this->items))
        {
            $this->items[$group_name] = [];
            $this->keys[$group_name] = [];
        }
    }


}
