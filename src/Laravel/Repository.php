<?php

namespace Interpro\QuickStorage\Laravel;

use Interpro\QuickStorage\Concept\Repository as RepositoryInterface;
use Interpro\QuickStorage\Concept\QSource as QSourceInterface;
use Interpro\QuickStorage\Concept\StorageStructure as StorageStructureInterface;

class Repository implements RepositoryInterface
{
    private $qSource;
    private $storageStruct;

    private $blocks;
    private $groups_blended;
    private $groups_ordered;


    public function __construct(StorageStructureInterface $storageStruct, QSourceInterface $qSource)
    {
        $this->qSource = $qSource;
        $this->storageStruct = $storageStruct;
        $this->blocks = [];

        //Элементы групп запрашиваются в перемешку от разных владельцев
        $this->groups_blended = [];

        //Упорядочиваться будут по мере запроса для каждого владельца
        //Внутри массив групп с массивом элементов (массив) id_$id ключем со значением - массив полей
        $this->groups_ordered = [];
    }

    public function getBlock($block_name)
    {
        if(!array_key_exists($block_name, $this->blocks))
        {
            $block_array = $this->qSource->blockQuery($block_name);
            $this->blocks[$block_name] = $block_array;

            return $block_array;
        }else{

            return $this->blocks[$block_name];
        }
    }

    public function getGroup($block_name, $group_name, $owner_id=0)
    {
        $this->checkGroupOwnedExistence($block_name, $group_name, $owner_id);

        return $this->groups_ordered[$group_name]['id_'.$owner_id];
    }

    private function orderGroup($group_name)
    {
        $ordered = & $this->groups_ordered[$group_name];

        foreach($this->groups_blended[$group_name] as $key => $fields)
        {
            $idkey = 'id_'.$fields['owner_id'];

            if(!array_key_exists($idkey, $ordered))
            {
                $ordered[$idkey] = [];
            }
            $ordered[$idkey][] = $fields;
        }
    }

    private function checkGroupExistence($block_name, $group_name)
    {
        if(!array_key_exists($group_name, $this->groups_blended))
        {
            //Извлекаем из хранилища элементы целевой группы с полями
            $group_array = $this->qSource->groupQuery($block_name, $group_name);
            $this->groups_blended[$group_name] = $group_array;
        }
    }

    private function checkGroupOwnedExistence($block_name, $group_name, $owner_id=0)
    {
        $this->checkGroupExistence($block_name, $group_name);

        if(!array_key_exists($group_name, $this->groups_ordered))
        {
            $this->groups_ordered[$group_name]=[];

            $this->orderGroup($group_name);
        }

        $ordered = & $this->groups_ordered[$group_name];

        $owner_key = 'id_'.$owner_id;

        if(!array_key_exists($owner_key, $ordered))
        {
            $ordered[$owner_key] = [];
        }

        return $ordered[$owner_key];
    }

}
