<?php namespace Interpro\QuickStorage\Laravel\Handle\Image;

use Interpro\ImageFileLogic\Laravel\Action\CreateImageAction;
use Interpro\QuickStorage\Concept\Command\Image\UpdateBlockImageCommand;
use Interpro\QuickStorage\Laravel\Item\ImageItem;

class UpdateBlockImageCommandHandler extends ImageCommandHandler
{
    /**
     * @param  UpdateBlockImageCommand  $command
     * @return void
     */
    public function handle(UpdateBlockImageCommand $command)
    {

        $config_name = $command->block_name.'_'.$command->image_name;

        $fields = $this->imageRepository->getBlockImage($command->block_name, $command->image_name);

        $imageItem = new ImageItem($config_name.'_0', $fields);

        $headAction = new CreateImageAction($command->req_orig_file);

        //К голове присоединяется остальная цепочка
        $this->actionChainFactory->buildChain($headAction, 'update', $config_name);

        $headAction->applyFor($imageItem);

    }

}
