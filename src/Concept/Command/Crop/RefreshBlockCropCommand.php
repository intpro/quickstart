<?php namespace Interpro\QuickStorage\Concept\Command\Crop;

class RefreshBlockCropCommand extends CropCommand
{
    public $block_name;
    /**
     * @param string $block_name
     *
     * @return void
     */
    public function __construct($block_name)
    {
        parent::__construct($block_name);

        $this->block_name = $block_name;
    }
}
