<?php namespace Interpro\QuickStorage\Laravel\Handle\Crop;

use Interpro\QuickStorage\Concept\Command\Crop\UpdateBlockCropCommand;

class UpdateBlockCropCommandHandler extends CropCommandHandler
{
    /**
     * @param  UpdateBlockCropCommand  $command
     * @return void
     */
    public function handle(UpdateBlockCropCommand $command)
    {
        $this->updateDBForBlock($command->block_name);
        $this->refreshBlock($command->block_name);
    }

}
