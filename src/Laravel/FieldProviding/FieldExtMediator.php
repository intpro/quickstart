<?php

namespace Interpro\QuickStorage\Laravel\FieldProviding;

use Interpro\QuickStorage\Concept\Exception\FieldProvideException;
use Interpro\QuickStorage\Concept\FieldProviding\FieldExtMediator as FieldExtMediatorInterface;
use Interpro\QuickStorage\Concept\FieldProviding\FieldExtractor;
use Interpro\QuickStorage\Concept\Item\Item;

class FieldExtMediator implements FieldExtMediatorInterface
{
    public $list;//Не защищенный (пока не прояснилась вся архитектура), чтобы не усложнять

    public function __construct()
    {
        $this->list = [];
    }

    /**
     * @param string $suffix
     *
     * @return bool
     */
    public function suffixRegistered($suffix)
    {
        return array_key_exists($suffix, $this->list);
    }

    /**
     * @param string $suffix
     *
     * FieldExtractor $extractor
     *
     * @return bool
     */
    public function addSuffix($suffix, FieldExtractor $extractor)
    {
        $this->list[$suffix] = $extractor;
    }

    /**
     * @param string $entity_name
     *
     * @param int $entity_id
     *
     * @return Item $field_item
     */
    public function getField($suffix, $entity_name, $field_name, $entity_id=0)
    {
        if($this->suffixRegistered($suffix))
        {
            $extractor = $this->list[$suffix];
            return $extractor->getField($entity_name, $field_name, $entity_id);
        }else{
            throw new FieldProvideException('Суффикс поля не зарегестрирован ('.$suffix.')');
        }
    }


}
