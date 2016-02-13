<?php

namespace Interpro\QuickStorage\Laravel\Item;

use Interpro\QuickStorage\Concept\Item\GroupItem as GroupItemInterface;
use Interpro\QuickStorage\Concept\Exception\WrongGroupFieldNameException;
use Interpro\QuickStorage\Concept\Exception\WrongGroupNameException;

class GroupItem extends EntityItem implements GroupItemInterface
{
    protected function initDepFields()
    {
        $this->setName('group_name');

        $this->setField('name',         $this->getAttr('group_name'));
        $this->setField('block_name',   $this->getAttr('block_name'));
        $this->setField('title',        $this->getAttr('title'));

        $this->setField('name_field',       $this->getField('name'));
        $this->setField('block_name_field', $this->getField('block_name'));
        $this->setField('title_field',      $this->getField('title'));

        $this->setField('sorter',       $this->getAttr('sorter'));
        $this->setField('show',         $this->getAttr('show'));

        $this->setField('sorter_field', $this->getField('sorter'));
        $this->setField('show_field',   $this->getField('show'));
    }

    //Перехватываем магическими методами обращения к полям и возвращаем значения из связанных таблиц (по типам)
    public function __get($req_name)
    {

        if($req_name == 'name' or
            $req_name == 'title' or
            $req_name == 'block_name' or
            $req_name == 'sorter' or
            $req_name == 'show' or
            $req_name == 'name_field' or
            $req_name == 'title_field' or
            $req_name == 'block_name_field' or
            $req_name == 'sorter_field' or
            $req_name == 'show_field'
        ){

            $value = $this->getField($req_name);

        }elseif (substr($req_name, -5) == 'field'){

            $field_name = substr($req_name, 0, -6); //+прочерк между именем и field

            if($this->groupFieldExist(
               $this->getField('block_name'),
               $this->getField('name'),
               $field_name
            ))
            {
                $value = $this->getField($field_name);
            }else{
                throw new WrongGroupFieldNameException('Поле '.$field_name.' блока '.$this->getField('block_name').' не найдено в настройке.');
            }

        }elseif (substr($req_name, -5) == 'group'){

            $group_name = substr($req_name, 0, -6); //+прочерк между именем и field

            if($this->subGroupExist(
               $this->getField('block_name'),
               $this->getField('name'),
               $group_name
            ))
            {
                $value = $this->createGroupCollection($group_name);
            }else{
                throw new WrongGroupNameException('Группа '.$group_name.' в составе группы '.$this->getField('name').' блока '.$this->getField('block_name').' не найдена.');
            }

        }else{
            throw new WrongGroupFieldNameException('Поле блока '.$this->getField('name').' не найдено в настройке.');
        }

        return $value;
    }



}
