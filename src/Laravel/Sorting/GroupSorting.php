<?php

namespace Interpro\QuickStorage\Laravel\Sorting;

use Interpro\QuickStorage\Concept\Sorting\GroupSorting as GroupSortingInterface;

class GroupSorting implements GroupSortingInterface
{

    private $group_name;
    private $field_name;
    private $sort_way;

    public function __construct($group_name, $field_name, $sort_way='ASC')
    {
        $this->group_name = $group_name;
        $this->field_name = $field_name;
        $this->sort_way = $sort_way;
    }

    public function getGroup()
    {
        return $this->group_name;
    }

    public function getFieldName()
    {
        return $this->field_name;
    }

    /**
     *
     * @param $query
     *
     * @return void
     */
    public function apply($query)
    {
        $query->orderBy($this->field_name, $this->sort_way);
    }
}
