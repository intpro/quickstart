<?php

namespace Interpro\QuickStorage\Laravel\Specification;

use Interpro\QuickStorage\Concept\Specification\BlockSpecificationSet as BlockSpecificationSetInterface;

class BlockSpecificationSet implements BlockSpecificationSetInterface
{
    /**
     *
     * @param BlockSpecification $specification
     *
     * @return void
     */
    public function add(BlockSpecification $specification)
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
