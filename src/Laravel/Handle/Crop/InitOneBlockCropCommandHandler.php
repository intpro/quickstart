<?php namespace Interpro\QuickStorage\Laravel\Handle\Crop;

use Interpro\QuickStorage\Concept\Command\Crop\InitOneBlockCropCommand;

class InitOneBlockCropCommandHandler extends CropCommandHandler
{
    /**
     * @param  InitOneBlockCropCommand  $command
     * @return void
     */
    public function handle(InitOneBlockCropCommand $command)
    {
        $this->initDBForBlock($command->block_name);
    }

}
