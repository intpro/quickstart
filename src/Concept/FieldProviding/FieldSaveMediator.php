<?php

namespace Interpro\QuickStorage\Concept\FieldProviding;

interface FieldSaveMediator
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
     * FieldSaver $extractor
     *
     * @return bool
     */
    public function addSuffix($suffix, FieldSaver $saver);

    /**
     * @param array $smm_save_array
     * @return void
     */
    public function save($suffix, $smm_save_array);

}
