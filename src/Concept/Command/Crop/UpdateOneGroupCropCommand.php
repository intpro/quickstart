<?php namespace Interpro\QuickStorage\Concept\Command\Crop;

class UpdateOneGroupCropCommand extends CropCommand
{
    public $block_name;
    public $group_name;
    public $group_id;
    public $crops;

    /**
     * @param string $block_name
     * @param string $group_name
     * @param int $group_id
     *
     * @return void
     */
    public function __construct($block_name, $group_name, $group_id, $crops)
    {
        parent::__construct($block_name);

        $this->block_name = $block_name;
        $this->group_name = $group_name;
        $this->group_id = $group_id;
        $this->crops = $crops;
    }

}
