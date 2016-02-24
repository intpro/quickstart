<?php

namespace Interpro\QuickStorage\Laravel;

use Interpro\QuickStorage\Concept\QueryAgent as QueryAgentInterface;
use Interpro\QuickStorage\Concept\Repository;
use Interpro\QuickStorage\Concept\Sorting\GroupSortingSet;
use Interpro\QuickStorage\Concept\Specification\GroupSpecificationSet;
use Interpro\QuickStorage\Laravel\Collection\GroupCollection;
use Interpro\QuickStorage\Laravel\Item\BlockItem;
use Interpro\QuickStorage\Laravel\Item\GroupItem;
use Interpro\QuickStorage\Laravel\Sorting\GroupSorting;

class QueryAgent implements QueryAgentInterface{

    private $repository;
    private $groupSortingSet;
    private $groupSpecificationSet;

    /**
     * @param  \Interpro\QuickStorage\Concept\StorageStructure $storageStructure
     * @param  \Interpro\QuickStorage\Concept\Repository $repository
     * @param  \Interpro\QuickStorage\Concept\Sorting\GroupSortingSet $groupSortingSet
     * @param  \Interpro\QuickStorage\Concept\Specification\GroupSpecificationSet $groupSpecificationSet
     * @return void
     */
    public function __construct(
        Repository $repository,
        GroupSortingSet $groupSortingSet,
        GroupSpecificationSet $groupSpecificationSet
    ){
        $this->repository = $repository;
        $this->groupSortingSet = $groupSortingSet;
        $this->groupSpecificationSet = $groupSpecificationSet;
    }

    private function setSorts($sorts)
    {
        foreach($sorts as $group_name=>$sort_arr)
        {
            if(is_array($sort_arr))
            {
                foreach($sort_arr as $field_name=>$sort_way)
                {
                    $sort_obj = new GroupSorting($group_name, $field_name, $sort_way);

                    $this->groupSortingSet->add($group_name, $sort_obj);
                }
            }
        }
    }

    /**
     * Получить элемент блока с полями
     *
     * @param string $name
     * @param array $sorts
     * @param array $specs
     * @return \Interpro\QuickStorage\Concept\Item\BlockItem
     */
    public function getBlock($name, $sorts, $specs)
    {
        $this->setSorts($sorts);

        $fields_arr = $this->repository->getBlock($name);

        $block_item = new BlockItem($fields_arr, 0);

        return $block_item;
    }

    /**
     * Получить коллекцию элементов группы по имени
     *
     * @param string $block_name
     * @param string $name
     * @param array $sorts
     * @param array $specs
     * @return \Interpro\QuickStorage\Concept\Collection\GroupCollection
     */
    public function getGroup($block_name, $name, $sorts, $specs)
    {
        $this->setSorts($sorts);

        $items_arr = $this->repository->getGroup($block_name, $name);

        $group_coll = new GroupCollection($block_name, $name, $items_arr);

        return $group_coll;
    }

    /**
     * Получить коллекцию элементов группы по имени
     *
     * @param string $block_name
     * @param string $name
     * @param int $group_id
     * @return \Interpro\QuickStorage\Concept\Item\GroupItem
     */
    public function getGroupItem($block_name, $name, $group_id)
    {
        //Потом
        return 'еще не реализовано';

    }

}
