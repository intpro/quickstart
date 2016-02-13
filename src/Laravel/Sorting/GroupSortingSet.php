<?php

namespace Interpro\QuickStorage\Laravel\Sorting;

use Interpro\QuickStorage\Concept\Sorting\GroupSorting;
use Interpro\QuickStorage\Concept\Sorting\GroupSortingSet as GroupSortingSetInterface;

class GroupSortingSet implements GroupSortingSetInterface
{
    /**
     *
     * @param GroupSorting $sorting
     *
     * @return void
     */
    public function add(GroupSorting $sorting)
    {

    }

    /**
     * Checks scope
     *
     * @param string $key
     *
     * @return void
     */
    public function rawAdd($groupName, $key, $way)
    {
        //Используя StorageStructure интерфес проверяем, есть ли вообще такое поле

    }

}
