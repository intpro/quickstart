<?php

namespace Interpro\QuickStorage\Laravel\Item;

use Interpro\ImageFileLogic\Concept\Item\ImageItem as ImageItemInterface;
use Interpro\QuickStorage\Concept\Exception\WrongImageFieldException;

class ImageItem implements ImageItemInterface
{
    private $names;

    private $id;
    private $value;
    private $name;
    private $block_name;
    private $group_name;
    private $group_id;
    private $prefix;
    private $alt;

    private $primary_link;
    private $secondary_link;
    private $icon_link;
    private $preview_link;

    private $cache_index;

    private $ext;
    private $image_name;

    /**
     * string $name
     * array $fields
     * @return void
     */
    public function __construct($image_name, $fields)
    {
        $this->image_name = $image_name;

        $field_names = ['id', 'value', 'name', 'block_name', 'group_name', 'group_id', 'prefix',
            'alt', 'original_link', 'primary_link', 'secondary_link', 'icon_link', 'preview_link', 'cache_index'];

        $this->names = $field_names;

        foreach($field_names as $field_name)
        {
            if(array_key_exists($field_name, $fields))
            {
                $this->$field_name = $fields[$field_name];
            }
        }
    }

    public function __get($req_name)
    {
        if(in_array($req_name, $this->names))
        {
            return $this->$req_name;
        }else{
            throw new WrongImageFieldException('Обращение к несуществующему полю '.$req_name.' картинки '.$this->image_name);
        }
    }

    public function getName()
    {
        return $this->image_name.'.'.$this->ext;
    }

    public function getNameWoExt()
    {
        return $this->image_name;
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
