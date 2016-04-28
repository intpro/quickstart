<?php

namespace Interpro\QuickStorage\Laravel\Sorting;

use Interpro\QuickStorage\Concept\Sorting\GroupSorting as GroupSortingInterface;

class RandomGroupSorting implements GroupSortingInterface
{

    private $group_name;

    public function __construct($group_name)
    {
        $this->group_name = $group_name;
    }

    public function getGroup()
    {
        return $this->group_name;
    }

    public function getFieldName()
    {
        return 'random';
    }

    /**
     *
     * @param $query
     *
     * @return void
     */
    public function apply($query)
    {
        $query->orderByRaw('RAND()');
    }
}
