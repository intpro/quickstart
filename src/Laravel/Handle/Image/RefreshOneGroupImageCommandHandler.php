<?php namespace Interpro\QuickStorage\Laravel\Handle\Image;

use Interpro\ImageFileLogic\Laravel\Action\ExistImageAction;
use Interpro\QuickStorage\Concept\Command\Image\RefreshOneGroupImageCommand;
use Interpro\QuickStorage\Laravel\Item\ImageItem;

class RefreshOneGroupImageCommandHandler extends ImageCommandHandler
{
    /**
     * @param  RefreshOneGroupImageCommand  $command
     * @return void
     */
    public function handle(RefreshOneGroupImageCommand $command)
    {

        $config_name = $command->group_name.'_'.$command->image_name;

        $fields = $this->qSource->oneImageQueryForGroup($command->block_name, $command->group_name, $command->group_id, $command->image_name);

        $imageItem = new ImageItem($config_name, $command->group_id, $fields);

        $headAction = new ExistImageAction();

        //К голове присоединяется остальная цепочка
        $this->actionChainFactory->buildChain($headAction, 'refresh', $config_name);

        $headAction->applyFor($imageItem);

    }

}
