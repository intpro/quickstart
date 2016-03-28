<?php

namespace Interpro\QuickStorage\Concept\Sorting;

interface GroupSortingSet extends \Iterator
{
    /**
     * @param string $group_name
     *
     * @param GroupSorting $sorting
     *
     * @return void
     */
    public function add($group_name, GroupSorting $sorting);

    /**
     * @param string $group_name
     *
     * @return void
     */
    public function setCurrentGroup($group_name);

    /**
     * @param string $group_name
     * @return void
     */
    public function reset($group_name);
}
