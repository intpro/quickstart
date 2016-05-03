<?php

namespace Interpro\QuickStorage\Concept\FieldProviding;

interface FieldSaver
{
    /**
     * @param array $smm_save_array
     * @return bool
     */
    public function save($smm_save_array);

}
