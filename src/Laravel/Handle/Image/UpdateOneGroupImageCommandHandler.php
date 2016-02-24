<?php namespace Interpro\QuickStorage\Laravel\Handle\Image;

use Interpro\ImageFileLogic\Laravel\Action\CreateImageAction;
use Interpro\QuickStorage\Concept\Command\Image\UpdateOneGroupImageCommand;
use Interpro\QuickStorage\Laravel\Item\ImageItem;

class UpdateOneGroupImageCommandHandler extends ImageCommandHandler
{
    /**
     * @param  UpdateOneGroupImageCommand  $command
     * @return void
     */
    public function handle(UpdateOneGroupImageCommand $command)
    {

        $config_name = $command->group_name.'_'.$command->image_name;

        $fields = $this->qSource->oneImageQueryForGroup($command->block_name, $command->group_name, $command->group_id, $command->image_name);

        $imageItem = new ImageItem($config_name.'_'.$command->group_id, $fields);

        $headAction = new CreateImageAction($command->req_orig_file);

        //К голове присоединяется остальная цепочка
        $this->actionChainFactory->buildChain($headAction, 'update', $config_name);

        $headAction->applyFor($imageItem);

    }

}
