<?php namespace Interpro\QuickStorage\Laravel\Handle\Crop;

use Interpro\QuickStorage\Concept\Command\Crop\RefreshOneGroupCropCommand;

class RefreshOneGroupCropCommandHandler extends CropCommandHandler
{
    /**
     * @param  RefreshOneGroupCropCommand  $command
     * @return void
     */
    public function handle(RefreshOneGroupCropCommand $command)
    {
        $this->refreshGroupItem($command->block_name, $command->group_name, $command->group_id);
    }

}
