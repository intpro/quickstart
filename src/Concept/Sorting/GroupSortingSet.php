<?php

namespace Interpro\QuickStorage\Concept\Sorting;

interface GroupSortingSet extends \Iterator
{
    /**
     *
     * @param GroupSorting $sorting
     *
     * @return void
     */
    public function add(GroupSorting $sorting);

    /**
     * Checks scope
     *
     * @param string $key
     *
     * @return void
     */
    public function rawAdd($groupName, $key, $way);
    //Используя StorageStructure интерфес проверяем, есть ли вообще такое поле
}
