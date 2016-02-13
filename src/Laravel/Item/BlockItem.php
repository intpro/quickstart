<?php

namespace Interpro\QuickStorage\Laravel\Item;

use Interpro\QuickStorage\Concept\Item\BlockItem as BlockItemInterface;
use Interpro\QuickStorage\Concept\Exception\WrongBlockFieldNameException;
use Interpro\QuickStorage\Concept\Exception\WrongGroupNameException;

class BlockItem extends EntityItem implements BlockItemInterface
{
    protected function initDepFields()
    {
        $this->setName('name');

        $this->setField('name', $this->getAttr('name'));
        $this->setField('title', $this->getAttr('title'));

        $this->setField('name_field', $this->getField('name'));
        $this->setField('title_field', $this->getField('title'));
    }

    //Перехватываем магическими методами обращения к полям и возвращаем значения из связанных таблиц (по типам)
    public function __get($req_name)
    {

        if($req_name == 'name' or
           $req_name == 'title' or
           $req_name == 'name_field' or
           $req_name == 'title_field'
        ){
            $value = $this->getField($req_name);

        }elseif (substr($req_name, -5) == 'field'){

            $field_name = substr($req_name, 0, -6); //+прочерк между именем и field

            if($this->blockFieldExist(
               $this->getField('name'),
               $field_name
            ))
            {
                $value = $this->getField($field_name);
            }else{
                throw new WrongBlockFieldNameException('Поле '.$field_name.' блока '.$this->getField('name').' не найдено в настройке.');
            }

        }elseif (substr($req_name, -5) == 'group'){

            $group_name = substr($req_name, 0, -6); //+прочерк между именем и field

            if($this->groupExist(
               $this->getField('name'),
               $group_name
            ))
            {
              $value = $this->createGroupCollection($group_name);
            }else{
                throw new WrongGroupNameException('Группа 1 уровня '.$group_name.' в составе блока '.$this->getField('name').' не найдена.');
            }

        }else{
            throw new WrongBlockFieldNameException('Поле блока '.$this->getField('name').' не найдено в настройке.');
        }

        return $value;
    }



}
