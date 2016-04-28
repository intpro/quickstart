<?php namespace Interpro\QuickStorage\Laravel\Handle\Crop;

use Interpro\QuickStorage\Concept\Command\Crop\InitGroupCropCommand;

class InitGroupCropCommandHandler extends CropCommandHandler
{
    /**
     * @param  InitGroupCropCommand  $command
     * @return void
     */
    public function handle(InitGroupCropCommand $command)
    {
        $this->initDBForGroup($command->block_name, $command->group_name);
        $this->refreshGroup($command->block_name, $command->group_name);
    }

}
