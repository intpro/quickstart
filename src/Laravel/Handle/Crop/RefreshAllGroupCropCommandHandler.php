<?php namespace Interpro\QuickStorage\Laravel\Handle\Crop;

use Interpro\QuickStorage\Concept\Command\Crop\RefreshAllGroupCropCommand;

class RefreshAllGroupCropCommandHandler extends CropCommandHandler
{
    /**
     * @param  RefreshAllGroupCropCommand  $command
     * @return void
     */
    public function handle(RefreshAllGroupCropCommand $command)
    {
        $this->refreshGroup($command->block_name, $command->group_name);
    }

}
