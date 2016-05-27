<?php

namespace Interpro\QuickStorage\Laravel\Item;

use Interpro\QuickStorage\Concept\Item\BlockItem as BlockItemInterface;
use Interpro\QuickStorage\Concept\Exception\WrongBlockFieldNameException;
use Interpro\QuickStorage\Concept\Exception\WrongGroupNameException;

class BlockItem extends EntityItem implements BlockItemInterface
{

    public function getEntityName()
    {
        return $this->getField('name');
    }

    public function getId()
    {
        return 0;
    }

    public function getImageFields($image_name)
    {
        return $this->imageRepository->getBlockImage($this->getField('name'), $image_name);
    }

    //Перехватываем магическими методами обращения к полям и возвращаем значения из связанных таблиц (по типам)
    public function __get($req_name)
    {
        $suffix_pos = strripos($req_name, '_');

        if($suffix_pos === false)
        {
            throw new WrongBlockFieldNameException('Обращение к полю (группе, картинке) блока ->'.$req_name.' '.$this->getField('name').' не соответствует формату ggg->xxx_type.');
        }

        $suffix = substr($req_name, $suffix_pos+1);
        $field_name = substr($req_name, 0, $suffix_pos);

        if ($suffix == 'field'){

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

        }elseif ($suffix == 'image'){

            $image_name = substr($req_name, 0, -6); //+прочерк между именем и image

            if($this->blockImageExist(
                $this->getField('name'),
                $image_name
            ))
            {
                $value = $this->getImage($image_name);
            }else{
                throw new WrongBlockFieldNameException('Картинка '.$image_name.' блока '.$this->getField('name').' не найдена в настройке.');
            }

        }elseif ($suffix == 'group'){

            $group_name = substr($req_name, 0, -6); //+прочерк между именем и field

            if($this->groupExist(
               $this->getField('name'),
               $group_name
            ))
            {
                $value = $this->getGroupCollection($this->getField('name'), $group_name, 0);
            }else{
                throw new WrongGroupNameException('Группа 1 уровня '.$group_name.' в составе блока '.$this->getField('name').' не найдена.');
            }

        }elseif ($this->fieldsProvider->suffixRegistered($suffix)){

            $value = $this->fieldsProvider->getField($suffix, $this->getField('name'), $field_name, 0);

        }else{

            throw new WrongBlockFieldNameException('Обращение к полю (группе, картинке) блока ->'.$req_name.' блока '.$this->getField('name').' не соответствует формату bbb->xxx_field.');
        }

        return $value;
    }


}
