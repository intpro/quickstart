<?php namespace Interpro\QuickStorage\Concept\Command\Crop;

class InitGroupCropCommand extends CropCommand
{
    public $block_name;
    public $group_name;

    /**
     * @param string $block_name
     * @param string $group_name
     *
     * @return void
     */
    public function __construct($block_name, $group_name)
    {
        parent::__construct();

        $this->block_name = $block_name;
        $this->group_name = $group_name;
    }

}
