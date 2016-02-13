<?php

namespace Interpro\QuickStorage\Concept\Specification;

interface GroupSpecificationSet extends \Iterator
{
    /**
     *
     * @param GroupSpecification $specification
     *
     * @return void
     */
    public function add(GroupSpecification $specification);

    /**
     * Checks scope
     *
     * @param string $key
     *
     * @return void
     */
    public function rawAdd($groupName, $key, $value);
    //Используя StorageStructure интерфес проверяем, есть ли вообще такое поле
}
