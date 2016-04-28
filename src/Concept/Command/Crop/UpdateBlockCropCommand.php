<?php namespace Interpro\QuickStorage\Concept\Command\Crop;

class UpdateBlockCropCommand extends CropCommand
{
    public $crops;
    public $block_name;

    /**
     * @param string $block_name
     * @param array $crops
     *
     * @return void
     */
    public function __construct($block_name, $crops)
    {
        parent::__construct($block_name);

        $this->block_name = $block_name;
        $this->crops = $crops;
    }

}
