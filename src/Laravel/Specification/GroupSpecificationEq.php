<?php

namespace Interpro\QuickStorage\Laravel\Specification;

use Interpro\QuickStorage\Concept\Item\GroupItem;

class GroupSpecificationEq extends GroupSpecification
{
    private $val;

    public function __construct($group_name, $field_name, $val)
    {
        parent::__construct($group_name, $field_name);

        $this->val = $val;
    }

    /**
     * Checks if given item meets all criteria
     *
     * @param GroupItem $item
     *
     * @return bool
     */
    public function isSatisfiedBy(GroupItem $item)
    {
        $field_name = $this->field_name;

        if(in_array($item->$field_name, $this->val)){
            return ($item->$field_name == $this->val);
        }else{
            return ($item->$field_name == $this->val);
        }
    }

    /**
     * Checks scope
     *
     * @param $query
     *
     * @return mixed
     */
    public function asScope($query)
    {
        if(is_array($this->val)){
            return $query->whereIn($this->getFieldName(), $this->val);
        }else{
            return $query->where($this->getFieldName(), '=', $this->val);
        }
    }

}
