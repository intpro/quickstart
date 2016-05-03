<?php

namespace Interpro\QuickStorage\Laravel\FieldProviding;

use Interpro\QuickStorage\Concept\Exception\FieldProvideException;
use Interpro\QuickStorage\Concept\FieldProviding\FieldSaveMediator as FieldSaveMediatorInterface;
use Interpro\QuickStorage\Concept\FieldProviding\FieldSaver;

class FieldSaveMediator implements FieldSaveMediatorInterface
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
     * FieldSaver $saver
     *
     * @return bool
     */
    public function addSuffix($suffix, FieldSaver $saver)
    {
        $this->list[$suffix] = $saver;
    }

    /**
     * @param array $smm_save_array
     * @return void
     */
    public function save($suffix, $smm_save_array)
    {
        if($this->suffixRegistered($suffix))
        {
            $saver = $this->list[$suffix];
            return $saver->save($smm_save_array);
        }else{
            throw new FieldProvideException('Суффикс поля не зарегестрирован ('.$suffix.')');
        }
    }

}
