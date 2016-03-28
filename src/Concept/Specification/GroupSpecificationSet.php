<?php

namespace Interpro\QuickStorage\Concept\Specification;

interface GroupSpecificationSet extends \Iterator
{
    /**
     * @param string $group_name
     *
     * @param GroupSpecification $specification
     *
     * @return void
     */
    public function add($group_name, GroupSpecification $specification);

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
