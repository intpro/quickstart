<?php

namespace Interpro\QuickStorage\Laravel\Item;

use Interpro\QuickStorage\Concept\Item\GroupItem as GroupItemInterface;
use Interpro\QuickStorage\Concept\Exception\WrongGroupFieldNameException;
use Interpro\QuickStorage\Concept\Exception\WrongGroupNameException;

class GroupItem extends EntityItem implements GroupItemInterface
{

    public function getEntityName()
    {
        return $this->getField('group_name');
    }

    public function getId()
    {
        return $this->getField('id');
    }

    public function getImageFields($image_name)
    {
        return $this->imageRepository->getGroupImage($this->getField('block_name'), $this->getField('group_name'), $this->getId(), $image_name);
    }

    //Перехватываем магическими методами обращения к полям и возвращаем значения из связанных таблиц (по типам)
    public function __get($req_name)
    {

        if (substr($req_name, -5) == 'field'){

            $field_name = substr($req_name, 0, -6); //+прочерк между именем и field

            if($this->groupFieldExist(
               $this->getField('block_name'),
               $this->getField('group_name'),
               $field_name
            ))
            {
                $value = $this->getField($field_name);
            }else{
                throw new WrongGroupFieldNameException('Поле '.$field_name.' группы '.$this->getField('group_name').' не найдено в настройке.');
            }

        }elseif (substr($req_name, -5) == 'image'){

            $image_name = substr($req_name, 0, -6); //+прочерк между именем и image

            if($this->groupFieldExist(
                $this->getField('block_name'),
                $this->getField('group_name'),
                $image_name
            ))
            {
                $value = $this->getImage($image_name);
            }else{
                throw new WrongGroupFieldNameException('Картинка '.$image_name.' группы '.$this->getField('group_name').' не найдена в настройке.');
            }

        }elseif (substr($req_name, -5) == 'group'){

            $group_name = substr($req_name, 0, -6); //+прочерк между именем и field

            if($this->subGroupExist(
               $this->getField('block_name'),
               $this->getField('group_name'),
               $group_name
            ))
            {
                $value = $this->getGroupCollection(
                    $this->getField('block_name'),
                    $group_name,
                    $this->getField('id')
                );
            }else{
                throw new WrongGroupNameException('Группа '.$group_name.' в составе группы '.$this->getField('group_name').' не найдена.');
            }

        }else{
            throw new WrongGroupFieldNameException('Обращение к полю (подгруппе, картинке) группы ->'.$req_name.' '.$this->getField('group_name').' не соответствует формату ggg->xxx_field.');
        }

        return $value;
    }

}
