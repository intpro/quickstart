<?php

namespace Interpro\QuickStorage\Concept\FieldProviding;

interface FieldExtractor
{
    /**
     * @param string $entity_name
     *
     * @param string $field_name
     *
     * @param int $entity_id
     *
     * @return mixed
     */
    public function getField($entity_name, $field_name, $entity_id=0);

}
