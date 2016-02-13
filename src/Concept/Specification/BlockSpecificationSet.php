<?php

namespace Interpro\QuickStorage\Concept\Specification;

interface BlockSpecificationSet extends \Iterator
{
    /**
     *
     * @param BlockSpecification $specification
     *
     * @return void
     */
    public function add(BlockSpecification $specification);

    /**
     * Checks scope
     *
     * @param string $key
     *
     * @return void
     */
    public function rawAdd($blockName, $key, $value);
    //Используя StorageStructure интерфес проверяем, есть ли вообще такое поле
}
