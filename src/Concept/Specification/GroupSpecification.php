<?php

namespace Interpro\QuickStorage\Concept\Specification;

use Interpro\QuickStorage\Concept\Item\GroupItem;

interface GroupSpecification
{
    /**
     * Checks if given item meets all criteria
     *
     * @param GroupItem $item
     *
     * @return bool
     */
    public function isSatisfiedBy(GroupItem $item);

    /**
     * Checks scope
     *
     * @param $query
     *
     * @return mixed
     */
    public function asScope($query);

    /**
     *
     * @return $string
     */
    public function getGroup();

    /**
     *
     * @return string
     */
    public function getFieldName();

}
