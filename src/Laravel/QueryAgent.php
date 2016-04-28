<?php

namespace Interpro\QuickStorage\Laravel;

use Interpro\QuickStorage\Concept\Param\GroupParam;
use Interpro\QuickStorage\Concept\QueryAgent as QueryAgentInterface;
use Interpro\QuickStorage\Concept\Repository as RepositoryInterface;
use Interpro\QuickStorage\Concept\Sorting\GroupSortingSet;
use Interpro\QuickStorage\Concept\Specification\GroupSpecificationSet;
use Interpro\QuickStorage\Laravel\Collection\GroupCollection;
use Interpro\QuickStorage\Laravel\Item\BlockItem;
use Interpro\QuickStorage\Laravel\Item\GroupItem;
use Interpro\QuickStorage\Laravel\Sorting\GroupSorting;
use Interpro\QuickStorage\Laravel\Sorting\RandomGroupSorting;
use Interpro\QuickStorage\Laravel\Specification\GroupSpecificationEq;

class QueryAgent implements QueryAgentInterface{

    protected $repository;
    private $groupSortingSet;
    private $groupSpecificationSet;
    private $groupParam;

    /**
     * @param  \Interpro\QuickStorage\Concept\StorageStructure $storageStructure
     * @param  \Interpro\QuickStorage\Concept\Repository $repository
     * @param  \Interpro\QuickStorage\Concept\Sorting\GroupSortingSet $groupSortingSet
     * @param  \Interpro\QuickStorage\Concept\Specification\GroupSpecificationSet $groupSpecificationSet
     * @param  \Interpro\QuickStorage\Concept\Param\GroupParam $groupParam
     * @return void
     */
    public function __construct(
        RepositoryInterface $repository,
        GroupSortingSet $groupSortingSet,
        GroupSpecificationSet $groupSpecificationSet,
        GroupParam $groupParam
    ){
        $this->repository = $repository;
        $this->groupSortingSet = $groupSortingSet;
        $this->groupSpecificationSet = $groupSpecificationSet;
        $this->groupParam = $groupParam;
    }

    protected function setSorts($sorts)
    {
        foreach($sorts as $group_name=>$sort_arr)
        {
            $this->groupSortingSet->reset($group_name);

            if(is_array($sort_arr))
            {
                foreach($sort_arr as $field_name=>$sort_way)
                {
                    if($field_name == 'random')
                    {
                        $sort_obj = new RandomGroupSorting($group_name);
                    }else{
                        $sort_obj = new GroupSorting($group_name, $field_name, $sort_way);
                    }

                    $this->groupSortingSet->add($group_name, $sort_obj);
                }
            }
        }
    }

    protected function setEqSpecs($specs)
    {
        foreach($specs as $group_name=>$spec_arr)
        {
            $this->groupSpecificationSet->reset($group_name);

            if(is_array($spec_arr))
            {
                foreach($spec_arr as $field_name=>$spec_val)
                {
                    $spec_obj = new GroupSpecificationEq($group_name, $field_name, $spec_val);

                    $this->groupSpecificationSet->add($group_name, $spec_obj);
                }
            }
        }
    }

    protected function setParams($params)
    {
        foreach($params as $group_name=>$param_arr)
        {
            if(is_array($param_arr))
            {
                foreach($param_arr as $param_name=>$param_val)
                {
                    $this->groupParam->set($group_name, $param_name, $param_val);
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
     * @param array $params
     * @return \Interpro\QuickStorage\Concept\Item\BlockItem
     */
    public function getBlock($name, $sorts, $specs, $params=[])
    {
        $this->setSorts($sorts);

        $this->setEqSpecs($specs);

        $this->setParams($params);

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
     * @param array $params
     * @return \Interpro\QuickStorage\Concept\Collection\GroupCollection
     */
    public function getGroup($block_name, $name, $sorts, $specs, $params=[])
    {
        $this->setSorts($sorts);

        $this->setEqSpecs($specs);

        $this->setParams($params);

        $items_arr = $this->repository->getGroup($block_name, $name);

        $group_coll = new GroupCollection($block_name, $name, $items_arr);

        return $group_coll;
    }

    /**
     * Получить коллекцию элементов группы по имени
     *
     * @param string $block_name
     * @param string $name
     * @param array $specs
     * @return int
     */
    public function getGroupCount($block_name, $name, $specs)
    {
        $this->setEqSpecs($specs);

        $this->repository->getGroupCount($block_name, $name);
    }

    /**
     * Получить коллекцию элементов группы по имени
     *
     * @param string $block_name
     * @param string $name
     * @param array $sorts
     * @param array $specs
     * @param array $params
     * @return \Interpro\QuickStorage\Concept\Collection\GroupCollection
     */
    public function getGroupFlat($block_name, $name, $sorts, $specs, $params=[])
    {
        $this->setSorts($sorts);

        $this->setEqSpecs($specs);

        $this->setParams($params);

        $items_arr = $this->repository->getGroupFlat($block_name, $name);

        $group_coll = new GroupCollection($block_name, $name, $items_arr);

        return $group_coll;
    }

    /**
     * Получить коллекцию элементов группы по имени
     *
     * @param string $block_name
     * @param string $group_name
     * @param int $group_id
     * @return \Interpro\QuickStorage\Concept\Item\GroupItem
     */
    public function getGroupItem($block_name, $group_name, $group_id)
    {
        $item_arr = $this->repository->getGroupItem($block_name, $group_name, $group_id);

        $item = new GroupItem($item_arr);

        return $item;

    }

    /**
     * Получить коллекцию элементов группы по имени
     *
     * @param string $block_name
     * @param string $group_name
     * @param string $slug
     * @return \Interpro\QuickStorage\Concept\Item\GroupItem
     */
    public function getGroupItemBySlug($block_name, $group_name, $slug)
    {
        $item_arr = $this->repository->getGroupItemBySlug($block_name, $group_name, $slug);

        $item = new GroupItem($item_arr);

        return $item;
    }

}




















