<?php namespace Interpro\QuickStorage\Laravel\Handle\Image;

use Interpro\ImageFileLogic\Laravel\Action\ExistImageAction;
use Interpro\QuickStorage\Concept\Command\Image\RefreshBlockImageCommand;
use Interpro\QuickStorage\Laravel\Item\ImageItem;

class RefreshBlockImageCommandHandler extends ImageCommandHandler
{
    /**
     * @param  RefreshBlockImageCommand  $command
     * @return void
     */
    public function handle(RefreshBlockImageCommand $command)
    {

        $config_name = $command->block_name.'_'.$command->image_name;

        $fields = $this->imageRepository->getBlockImage($command->block_name, $command->image_name);

        $imageItem = new ImageItem($config_name.'_0', $fields);

        $headAction = new ExistImageAction();

        //К голове присоединяется остальная цепочка
        $this->actionChainFactory->buildChain($headAction, 'refresh', $config_name);

        $headAction->applyFor($imageItem);

    }

}
