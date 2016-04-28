<?php namespace Interpro\QuickStorage\Laravel\Handle\Crop;

use Interpro\QuickStorage\Concept\Command\Crop\RefreshBlockCropCommand;

class RefreshBlockCropCommandHandler extends CropCommandHandler
{
    /**
     * @param  RefreshBlockCropCommand  $command
     * @return void
     */
    public function handle(RefreshBlockCropCommand $command)
    {
        $this->refreshBlock($command->block_name);
    }

}
