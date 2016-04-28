<?php namespace Interpro\QuickStorage\Laravel\Handle\Crop;

use Interpro\QuickStorage\Concept\Command\Crop\InitAllBlockCropCommand;

class InitAllBlockCropCommandHandler extends CropCommandHandler
{
    /**
     * @param  InitAllBlockCropCommand  $command
     * @return void
     */
    public function handle(InitAllBlockCropCommand $command)
    {
        $qstorage = config('qstorage');

        foreach($qstorage as $blockname => $blockstruct)
        {
            $this->initDBForBlock($blockname);
            $this->refreshBlock($blockname);
        }
    }

}
