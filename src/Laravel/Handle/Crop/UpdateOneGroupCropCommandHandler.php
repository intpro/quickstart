<?php namespace Interpro\QuickStorage\Laravel\Handle\Crop;

use Interpro\QuickStorage\Concept\Command\Crop\UpdateOneGroupCropCommand;

class UpdateOneGroupCropCommandHandler extends CropCommandHandler
{

    /**
     * @param  UpdateOneGroupCropCommand  $command
     * @return void
     */
    public function handle(UpdateOneGroupCropCommand $command)
    {
        $this->updateDBForGroupItem($command->block_name, $command->group_name, $command->group_id, $command->crops);
        $this->refreshGroupItem($command->block_name, $command->group_name, $command->group_id);
    }

}
