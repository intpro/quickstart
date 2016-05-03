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
        $suffix_pos = strripos($req_name, '_');

        if($suffix_pos === false)
        {
            throw new WrongGroupFieldNameException('Обращение к полю (подгруппе, картинке) группы ->'.$req_name.' '.$this->getField('group_name').' не соответствует формату ggg->xxx_field.');
        }

        $suffix = substr($req_name, $suffix_pos+1);
        $field_name = substr($req_name, 0, $suffix_pos);

        if ($suffix == 'field'){

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

        }elseif ($suffix == 'image'){

            if($this->groupFieldExist(
                $this->getField('block_name'),
                $this->getField('group_name'),
                $field_name
            ))
            {
                $value = $this->getImage($field_name);
            }else{
                throw new WrongGroupFieldNameException('Картинка '.$field_name.' группы '.$this->getField('group_name').' не найдена в настройке.');
            }

        }elseif ($suffix == 'group'){

            if($this->subGroupExist(
               $this->getField('block_name'),
               $this->getField('group_name'),
                $field_name
            ))
            {
                $value = $this->getGroupCollection(
                    $this->getField('block_name'),
                    $field_name,
                    $this->getField('id')
                );
            }else{
                throw new WrongGroupNameException('Группа '.$field_name.' в составе группы '.$this->getField('group_name').' не найдена.');
            }

        }elseif ($this->fieldsProvider->suffixRegistered($suffix)){

            $value = $this->fieldsProvider->getField($suffix, $this->getField('group_name'), $field_name, $this->getField('id'));

        }else{

            throw new WrongGroupFieldNameException('Обращение к полю (подгруппе, картинке) группы ->'.$req_name.' '.$this->getField('group_name').' не соответствует формату ggg->xxx_field.');
        }

        return $value;
    }

}
