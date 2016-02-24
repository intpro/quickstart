<?php

namespace Interpro\QuickStorage\Laravel\Sorting;

use Interpro\QuickStorage\Concept\Exception\BuildQueryException;
use Interpro\QuickStorage\Concept\Sorting\GroupSorting;
use Interpro\QuickStorage\Concept\Sorting\GroupSortingSet as GroupSortingSetInterface;
use Interpro\QuickStorage\Laravel\GroupSet;

class GroupSortingSet implements GroupSortingSetInterface
{
    use GroupSet;

    /**
     * @param string $groupName
     *
     * @param string $field_name
     *
     * @param string $way
     *
     * @return void
     */
    private function rawAdd($group_name, $field_name, $way='ASC')
    {
        //Вызываем из конструктора
        //Вызываем из конструктора
        //Вызываем из конструктора
        //Вызываем из конструктора

    }

    public function __construct()
    {
        //Забираем из сессии установки сортировки
        //Забираем из сессии установки сортировки
        //Забираем из сессии установки сортировки
        //Забираем из сессии установки сортировки
        //Забираем из сессии установки сортировки
    }

    /**
     * @param string $group_name
     *
     * @param GroupSorting $sorting
     *
     * @return void
     */
    public function add($group_name, GroupSorting $sorting)
    {
        $field_name = $sorting->getFieldName();

        if(!($sorting->getGroup() == $group_name))
        {
            throw new BuildQueryException('Добавление сортировки по полю '.$field_name.' не в ту группу '.$group_name);
        }

        $this->addEmptyGroup($group_name);

        if(array_key_exists($field_name, $this->keys[$group_name]))
        {
            $this->items[$group_name][$this->keys[$group_name][$field_name]] = $sorting;
        }else{
            $this->items[$group_name][] = $sorting;
            $this->keys[$group_name][$field_name] = count($this->items[$group_name])-1;
        }

    }

}
