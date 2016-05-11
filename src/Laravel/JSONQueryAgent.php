<?php

namespace Interpro\QuickStorage\Laravel;

use Interpro\QuickStorage\Concept\JSONQueryAgent as JSONQueryAgentInterface;
use Interpro\QuickStorage\Concept\Param\GroupParam;
use Interpro\QuickStorage\Concept\Sorting\GroupSortingSet;
use Interpro\QuickStorage\Concept\Specification\GroupSpecificationSet;
use Interpro\QuickStorage\Laravel\Sorting\GroupSorting;
use Interpro\QuickStorage\Laravel\Sorting\RandomGroupSorting;
use Interpro\QuickStorage\Laravel\Specification\GroupSpecificationEq;
use Interpro\QuickStorage\Concept\QSource as QSourceInterface;


class JSONQueryAgent implements JSONQueryAgentInterface {

    private $groupSortingSet;
    private $groupSpecificationSet;
    private $groupParam;
    private $qSource;

    /**
     * @param  \Interpro\QuickStorage\Concept\Sorting\GroupSortingSet $groupSortingSet
     * @param  \Interpro\QuickStorage\Concept\Specification\GroupSpecificationSet $groupSpecificationSet
     * @param  \Interpro\QuickStorage\Concept\Param\GroupParam $groupParam
     * @param  \Interpro\QuickStorage\Concept\QSource $qSource
     * @return void
     */
    public function __construct(
        GroupSortingSet $groupSortingSet,
        GroupSpecificationSet $groupSpecificationSet,
        GroupParam $groupParam,
        QSourceInterface $qSource
    ){
        $this->groupSortingSet       = $groupSortingSet;
        $this->groupSpecificationSet = $groupSpecificationSet;
        $this->groupParam            = $groupParam;
        $this->qSource               = $qSource;
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
     * @return array
     */
    public function getBlock($name, $sorts, $specs, $params=[])
    {
        $this->setSorts($sorts);

        $this->setEqSpecs($specs);

        $this->setParams($params);

        $block_array = $this->qSource->blockQuery($name);

        return $block_array;
    }

    /**
     * Получить коллекцию элементов группы по имени
     *
     * @param string $block_name
     * @param string $name
     * @param array $sorts
     * @param array $specs
     * @param array $params
     * @return array
     */
    public function getGroupFlat($block_name, $name, $sorts, $specs, $params=[])
    {
        $this->setSorts($sorts);

        $this->setEqSpecs($specs);

        $this->setParams($params);

        $group_array = $this->qSource->groupQuery($block_name, $name);

        return $group_array;
    }

}
