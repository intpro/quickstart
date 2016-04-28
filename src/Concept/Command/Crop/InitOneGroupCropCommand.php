<?php namespace Interpro\QuickStorage\Concept\Command\Crop;

class InitOneGroupCropCommand extends CropCommand
{
    public $group_name;
    public $group_id;
    public $block_name;

    /**
     * @param string $block_name
     * @param string $group_name
     * @param int $group_id
     *
     * @return void
     */
    public function __construct($block_name, $group_name, $group_id)
    {
        parent::__construct();

        $this->block_name = $block_name;
        $this->group_name = $group_name;
        $this->group_id = $group_id;
    }

}
