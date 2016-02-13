<?php

namespace Interpro\QuickStorage\Laravel\Specification;

use Interpro\QuickStorage\Concept\Item\GroupItem;

use Interpro\QuickStorage\Concept\Specification\GroupSpecification as GroupSpecificationInterface;

class GroupSpecification implements GroupSpecificationInterface
{

    /**
     * Checks if given item meets all criteria
     *
     * @param GroupItem $item
     *
     * @return bool
     */
    public function isSatisfiedBy(GroupItem $item)
    {

    }

    /**
     * Checks scope
     *
     * @param $query
     *
     * @return mixed
     */
    public function asScope($query)
    {

    }

}
