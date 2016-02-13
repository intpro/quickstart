<?php

namespace Interpro\QuickStorage\Laravel\Specification;

use Interpro\QuickStorage\Concept\Item\BlockItem;
use \Interpro\QuickStorage\Concept\Specification\BlockSpecification as BlockSpecificationInterface;

class BlockSpecification implements BlockSpecificationInterface
{

    /**
     * Checks if given item meets all criteria
     *
     * @param BlockItem $item
     *
     * @return bool
     */
    public function isSatisfiedBy(BlockItem $item)
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
