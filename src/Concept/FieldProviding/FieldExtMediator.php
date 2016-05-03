<?php

namespace Interpro\QuickStorage\Concept\FieldProviding;

interface FieldExtMediator
{
    /**
     * @param string $suffix
     *
     * @return bool
     */
    public function suffixRegistered($suffix);

    /**
     * @param string $suffix
     *
     * FieldExtractor $extractor
     *
     * @return bool
     */
    public function addSuffix($suffix, FieldExtractor $extractor);

    /**
     * @param string $entity_name
     *
     * @param string $field_name
     *
     * @param int $entity_id
     *
     * @return mixed
     */
    public function getField($suffix, $entity_name, $field_name, $entity_id=0);

}
