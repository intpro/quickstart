<?php

namespace Interpro\QuickStorage\Laravel\Specification;

use Interpro\QuickStorage\Concept\Item\GroupItem;
use Interpro\QuickStorage\Concept\Specification\GroupSpecification as GroupSpecificationInterface;

abstract class GroupSpecification implements GroupSpecificationInterface
{
    protected $group_name;
    protected $field_name;


    public function __construct($group_name, $field_name)
    {
        $this->group_name = $group_name;
        $this->field_name = $field_name;
    }

    /**
     * Checks if given item meets all criteria
     *
     * @param GroupItem $item
     *
     * @return bool
     */
    abstract public function isSatisfiedBy(GroupItem $item);

    /**
     * Checks scope
     *
     * @param $query
     *
     * @return mixed
     */
    abstract public function asScope($query);

    /**
     * @param $string
     *
     * @return bool
     */
    public function getGroup()
    {
        return $this->group_name;
    }


    /**
     *
     * @return string
     */
    public function getFieldName()
    {
        return $this->field_name;
    }

}
