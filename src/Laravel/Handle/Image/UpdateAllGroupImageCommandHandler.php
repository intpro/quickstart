<?php namespace Interpro\QuickStorage\Laravel\Handle\Image;

use Interpro\ImageFileLogic\Laravel\Action\CreateImageAction;
use Interpro\QuickStorage\Concept\Exception\CommandDsNotWorkException;
use Interpro\QuickStorage\Concept\Command\Image\UpdateAllGroupImageCommand;
use Interpro\QuickStorage\Laravel\Item\ImageItem;

class UpdateAllGroupImageCommandHandler extends ImageCommandHandler
{
    /**
     * @param  UpdateAllGroupImageCommand  $command
     * @return void
     */
    public function handle(UpdateAllGroupImageCommand $command)
    {

        //throw new CommandDsNotWorkException('Груповая команда обработки картинок UpdateAllGroupImageCommandHandler отключена!');

        $config_name = $command->group_name.'_'.$command->image_name;

        $headAction = new CreateImageAction($command->req_orig_file);

        //К голове присоединяется остальная цепочка
        $this->actionChainFactory->buildChain($headAction, 'update', $config_name);


        $images = $this->imageRepository->getAllGroupImages($command->block_name, $command->group_name, $command->image_name);

        foreach($images as $fields)
        {
            $imageItem = new ImageItem($config_name.'_'.$images['group_id'], $fields);

            $headAction->applyFor($imageItem);
        }


    }

}
