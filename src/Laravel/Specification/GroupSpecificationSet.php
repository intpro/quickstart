<?php

namespace Interpro\QuickStorage\Laravel\Specification;

use Interpro\QuickStorage\Concept\Exception\BuildQueryException;
use Interpro\QuickStorage\Concept\Specification\GroupSpecification;
use Interpro\QuickStorage\Concept\Specification\GroupSpecificationSet as GroupSpecificationSetInterface;
use Interpro\QuickStorage\Laravel\GroupSet;

class GroupSpecificationSet implements GroupSpecificationSetInterface
{
    use GroupSet;

    /**
     * @param string $groupName
     *
     * @param string $field_name
     *
     * @param string $oper
     *
     * @param mixed $val
     *
     * @return void
     */
    private function rawAdd($group_name, $field_name, $oper, $val)
    {
        //Вызываем из конструктора
        //Вызываем из конструктора
        //Вызываем из конструктора
        //Вызываем из конструктора
    }

    public function __construct()
    {
        //Забираем из сессии установки спецификаций
        //Забираем из сессии установки спецификаций
        //Забираем из сессии установки спецификаций
        //Забираем из сессии установки спецификаций
        //Забираем из сессии установки спецификаций
    }

    /**
     * @param string $group_name
     *
     * @param GroupSpecification $specification
     *
     * @return void
     */
    public function add($group_name, GroupSpecification $specification)
    {
        $field_name = $specification->getFieldName();

        if(!($specification->getGroup() == $group_name))
        {
            throw new BuildQueryException('Добавление условия по полю '.$field_name.' не в ту группу '.$group_name);
        }

        $this->addEmptyGroup($group_name);

        if(array_key_exists($field_name, $this->keys[$group_name]))
        {
            $this->items[$group_name][$this->keys[$group_name][$field_name]] = $specification;
        }else{
            $this->items[$group_name][] = $specification;
            $this->keys[$group_name][$field_name] = count($this->items[$group_name])-1;
        }
    }

}
