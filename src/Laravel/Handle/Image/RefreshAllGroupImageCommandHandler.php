<?php namespace Interpro\QuickStorage\Laravel\Handle\Image;

use Interpro\ImageFileLogic\Laravel\Action\ExistImageAction;
use Interpro\QuickStorage\Concept\Command\Image\RefreshAllGroupImageCommand;
use Interpro\QuickStorage\Laravel\Item\ImageItem;

class RefreshAllGroupImageCommandHandler extends ImageCommandHandler
{
    /**
     * @param  RefreshAllGroupImageCommand  $command
     * @return void
     */
    public function handle(RefreshAllGroupImageCommand $command)
    {

        //throw new CommandDsNotWorkException('Груповая команда обработки картинок RefreshAllGroupImageCommandHandler отключена!');

        $config_name = $command->group_name.'_'.$command->image_name;

        $headAction = new ExistImageAction();

        //К голове присоединяется остальная цепочка
        $this->actionChainFactory->buildChain($headAction, 'refresh', $config_name);


        $images = $this->imageRepository->getAllGroupImages($command->block_name, $command->group_name, $command->image_name);

        foreach($images as $fields)
        {
            $imageItem = new ImageItem($config_name, $images['group_id'], $fields);

            $headAction->applyFor($imageItem);
        }

    }

}
