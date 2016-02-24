<?php namespace Interpro\QuickStorage\Concept\Command\Image;

class RefreshAllGroupImageCommand extends ImageCommand
{
    public $group_name;

    /**
     * @param string $block_name
     * @param string $group_name
     * @param string $image_name
     *
     * @return void
     */
    public function __construct($block_name, $group_name, $image_name)
    {
        parent::__construct($block_name, $image_name);

        $this->group_name = $group_name;
    }

}
