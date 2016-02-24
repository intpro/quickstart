<?php

namespace Interpro\QuickStorage\Laravel\Collection;

use Interpro\ImageFileLogic\Concept\Collection\ImageCollection as ImageCollectionInterface;

class ImageCollection implements ImageCollectionInterface
{
    private $items = [];
    private $position = 0;

    private function init()
    {

    }

    public function __construct(
    )
    {


        $this->init();
    }

    function rewind()
    {
        $this->position = 0;
    }

    function current()
    {
        return 0;
    }

    function key()
    {
        return $this->position;
    }

    function next()
    {
        ++$this->position;
    }

    function valid()
    {
        return isset($this->items[$this->position]);
    }

}
