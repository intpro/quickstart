<?php

namespace Interpro\QuickStorage\Laravel\Item;

use Illuminate\Database\Eloquent\Model;
use Interpro\QuickStorage\Concept\Exception\BottomReachedException;
use Interpro\QuickStorage\Concept\StorageStructure;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Interpro\QuickStorage\Laravel\Collection\GroupCollection;

abstract class EntityItem
{
    private $fields;
    private $storageStruct;
    private $model;
    private $groups;
    private $depth;
    private $name;


    protected function getAttr($attr_name)
    {
        if(isset($this->model->$attr_name))
        {
            return $this->model->$attr_name;
        }else{
            return null;
        }
    }

    protected function getDepth()
    {
        return $this->depth;
    }

    protected function getField($field_name)
    {
        return $this->fields[$field_name];
    }

    protected function setField($field_name, $value)
    {
        return $this->fields[$field_name] = $value;
    }

    protected function setName($name)
    {
        return $this->name = $this->model[$name];
    }

    abstract protected function initDepFields();

    private function initSameFields()
    {
        foreach($this->model->stringfields as $stringfield)
        {
            $this->fields[$stringfield->name] = $stringfield->value;
        }

        foreach($this->model->textfields as $textfield)
        {
            $this->fields[$textfield->name] = $textfield->value;
        }

        foreach($this->model->images as $image)
        {
            $this->fields[$image->name] = [
                'alt'=>$image->alt,
                'original_link'=>$image->original_link,
                'primary_link'=>$image->primary_link,
                'secondary_link'=>$image->secondary_link,
                'icon_link'=>$image->icon_link,
                'preview_link'=>$image->preview_link,
                'prefix'=>$image->prefix
            ];
        }

        foreach($this->model->bools as $boolitem)
        {
            $this->fields[$boolitem->name] = $boolitem->value;
        }

        foreach($this->model->pdatetimes as $dtitem)
        {
            $this->fields[$dtitem->name] = $dtitem->value;
        }

        foreach($this->model->numbs as $numb)
        {
            $this->fields[$numb->name] = $numb->value;
        }
    }

    public function __construct(StorageStructure $storageStruct, Model $model, EloquentCollection $groups, $depth)
    {
        $this->storageStruct = $storageStruct;

        $this->model = $model;
        $this->groups = $groups;
        $this->depth = $depth;

        $this->initDepFields();
        $this->initSameFields();
    }

    protected function createGroupCollection($group_name)
    {
        $nextdepth = $this->getDepth();
        $nextdepth--;

        if($nextdepth < 0){
            throw new BottomReachedException('Нет группы '.$group_name.' глубже текущей!');
        }

        return new GroupCollection(
            $group_name,
            $this->storageStruct,
            $this->groups,
            $nextdepth);
    }

    protected function blockFieldExist($name, $field_name)
    {
       return $this->storageStruct->blockFieldExist($name, $field_name);
    }

    protected function groupFieldExist($blockName, $groupName, $fieldName)
    {
        return $this->storageStruct->groupFieldExist($blockName, $groupName, $fieldName);
    }

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
