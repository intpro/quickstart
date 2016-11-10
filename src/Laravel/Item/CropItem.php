<?php

namespace Interpro\QuickStorage\Laravel\Item;

use Interpro\ImageFileLogic\Concept\Item\CropItem as CropItemInterface;
use Interpro\QuickStorage\Concept\Exception\WrongImageFieldException;

class CropItem implements CropItemInterface
{
    private $names;

    private $id;
    private $value;
    private $name;
    private $crop_name;
    private $crop_config_name;
    private $block_name;
    private $group_name;
    private $group_id;
    private $image_id;
    private $prefix;
    private $alt;
    private $man_sufix;
    private $target_sufix;

    private $link;

    private $cache_index;

    private $man_x1;
    private $man_y1;
    private $man_x2;
    private $man_y2;
    private $target_x1;
    private $target_y1;
    private $target_x2;
    private $target_y2;


    /**
     * string $name
     * string $id
     * array $fields
     * @return void
     */
    public function __construct($crop_config_name, $id, $fields)
    {
        $this->crop_name = $crop_config_name.'_'.$id;
        $this->crop_config_name = $crop_config_name;
        $this->id = $id;

        $field_names = ['id', 'value', 'name', 'image_name', 'block_name', 'group_name', 'group_id', 'image_id', 'prefix',
            'alt', 'man_sufix', 'target_sufix', 'link', 'cache_index',
            'man_x1', 'man_y1', 'man_x2', 'man_y2', 'target_x1', 'target_y1', 'target_x2', 'target_y2'];

        $this->names = $field_names;

        foreach($field_names as $field_name)
        {
            if(array_key_exists($field_name, $fields))
            {
                $this->$field_name = $fields[$field_name];
            }
        }
    }

    public function getConfigName()
    {
        return $this->config_name;
    }

    public function getId()
    {
        return $this->id;
    }

    public function __get($req_name)
    {
        if(in_array($req_name, $this->names))
        {
            return $this->$req_name;
        }else{
            throw new WrongImageFieldException('Обращение к несуществующему полю '.$req_name.' кропа картинки '.$this->crop_name);
        }
    }

    public function getName()
    {
        return $this->crop_name.'.'.$this->ext;
    }

    public function getNameWoExt()
    {
        return $this->crop_name;
    }

    public function getExt()
    {
        return $this->ext;
    }

    public function setExt($ext)
    {
        $this->ext = $ext;
    }

}
