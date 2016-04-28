<?php namespace Interpro\QuickStorage\Laravel\Handle\Crop;

use Interpro\QuickStorage\Concept\Command\Crop\InitOneGroupCropCommand;

class InitOneGroupCropCommandHandler extends CropCommandHandler
{
    /**
     * @param  InitOneGroupCropCommand  $command
     * @return void
     */
    public function handle(InitOneGroupCropCommand $command)
    {
        $this->initDBForGroupItem($command->block_name, $command->group_name, $command->group_id);
        $this->refreshGroupItem($command->block_name, $command->group_name, $command->group_id);
    }

}
