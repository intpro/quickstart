<?php

namespace Interpro\QuickStorage\Laravel\Item;

use Illuminate\Support\Facades\App;
use Interpro\QuickStorage\Concept\Exception\WrongGroupFieldNameException;
use Interpro\QuickStorage\Laravel\Collection\GroupCollection;

abstract class EntityItem
{
    private $fields;
    private $storageStruct;
    private $repository;
    protected $imageRepository;
    protected $fieldsProvider;
    private $group_collections;

    public function __construct($fields)
    {
        $this->storageStruct   = App::make('Interpro\QuickStorage\Concept\StorageStructure');
        $this->repository      = App::make('Interpro\QuickStorage\Concept\Repository');
        $this->imageRepository = App::make('Interpro\QuickStorage\Concept\ImageRepository');
        $this->fieldsProvider  = App::make('Interpro\QuickStorage\Concept\FieldProviding\FieldExtMediator');

        $this->fields = $fields;
        $this->group_collections = [];
    }

    abstract public function getImageFields($image_name);

    abstract public function getEntityName();

    abstract public function getId();

    protected function getField($field_name)
    {
        return $this->fields[$field_name];
    }

    public function setField($field_name, $value)
    {
        //Метод открыт как публичный по просьбе Ромы, чтобы умножать сумму в валюте на курс
        if($field_name === 'group_name' or $field_name === 'block_name' or $field_name === 'id')
        {
            throw new WrongGroupFieldNameException('Нельзя менять поля ключи - group_name, block_name, id');
        }

        $this->fields[$field_name] = $value;
    }

    protected function getImage($image_name)
    {
        $config_name = $this->getEntityName().'_'.$image_name;

        $id = $this->getId();

        $fields = $this->getImageFields($image_name);

        $imageItem = new ImageItem($config_name, $id, $fields);

        return $imageItem;
    }

    protected function createGroupIfNotExist($block_name, $group_name, & $group_array, $owner_id)
    {
        if(!array_key_exists($group_name, $this->group_collections))
        {
            $collection = new GroupCollection(
            $block_name,
            $group_name,
            $group_array,
            $owner_id);

            $this->group_collections[$group_name] = $collection;
        }
    }

    protected function getGroupCollection($block_name, $group_name, $owner_id)
    {
        $group_array = $this->repository->getGroup($block_name, $group_name, $owner_id);

        $this->createGroupIfNotExist($block_name, $group_name, $group_array, $owner_id);

        return $this->group_collections[$group_name];
    }

    protected function blockFieldExist($name, $field_name)
    {
       return $this->storageStruct->blockFieldExist($name, $field_name);
    }

    protected function groupFieldExist($blockName, $groupName, $fieldName)
    {
        return $this->storageStruct->groupFieldExist($blockName, $groupName, $fieldName);
    }

    //--------------------------
    protected function blockImageExist($name, $field_name)
    {
        return $this->storageStruct->blockImageExist($name, $field_name);
    }

    protected function groupImageExist($blockName, $groupName, $fieldName)
    {
        return $this->storageStruct->groupImageExist($blockName, $groupName, $fieldName);
    }
    //---------------------------

    protected function groupExist($name, $group_name)
    {
        return $this->storageStruct->groupExist($name, $group_name);
    }

    protected function subGroupExist($block_name, $group_name, $sub_group_name)
    {
        return $this->storageStruct->subGroupExist($block_name, $group_name, $sub_group_name);
    }

    abstract public function __get($req_name);

}
