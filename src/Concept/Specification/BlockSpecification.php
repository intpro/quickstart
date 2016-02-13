<?php

namespace Interpro\QuickStorage\Concept\Specification;

use Interpro\QuickStorage\Concept\Item\BlockItem;

interface BlockSpecification
{

    /**
     * Checks if given item meets all criteria
     *
     * @param BlockItem $item
     *
     * @return bool
     */
    public function isSatisfiedBy(BlockItem $item);

    /**
     * Checks scope
     *
     * @param $query
     *
     * @return mixed
     */
    public function asScope($query);

}
