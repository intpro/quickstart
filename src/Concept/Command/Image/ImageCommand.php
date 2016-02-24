<?php namespace Interpro\QuickStorage\Concept\Command\Image;

abstract class ImageCommand
{
    public $block_name;
    public $image_name;

    /**
     * @param string $block_name
     * @param string $image_name
     *
     * @return void
     */
    public function __construct($block_name, $image_name)
    {
        $this->block_name = $block_name;
        $this->image_name = $image_name;
    }

}
