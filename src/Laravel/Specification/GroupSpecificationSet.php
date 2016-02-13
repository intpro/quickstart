<?php

namespace Interpro\QuickStorage\Laravel\Specification;

use Interpro\QuickStorage\Concept\Specification\GroupSpecificationSet as GroupSpecificationSetInterface;

class GroupSpecificationSet implements GroupSpecificationSetInterface
{
    /**
     *
     * @param GroupSpecification $specification
     *
     * @return void
     */
    public function add(GroupSpecification $specification)
    {

    }

    /**
     * Checks scope
     *
     * @param string $key
     *
     * @return void
     */
    public function rawAdd($blockName, $key, $value)
    {
        //Используя StorageStructure интерфес проверяем, есть ли вообще такое поле

    }

}
