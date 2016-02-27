<?php namespace Interpro\QuickStorage\Laravel\Handle\Image;

use Interpro\ImageFileLogic\Laravel\Action\ExistImageAction;
use Interpro\QuickStorage\Concept\Command\Image\CheckGroupImagePlaceholdCommand;
use Interpro\QuickStorage\Laravel\Item\ImageItem;

class CheckGroupImagePlaceholdCommandHandler extends ImageCommandHandler
{
    /**
     * @param  CheckGroupImagePlaceholdCommand  $command
     * @return void
     */
    public function handle(CheckGroupImagePlaceholdCommand $command)
    {
//        $path_resolver = App::make('Interpro\ImageFileLogic\Concept\PathResolver');
//
//        $config_name = $command->group_name.'_'.$command->image_name;
//
//        $images_dir = $this->pathResolver->getImageDir();
//
//        $image_path = $images_dir.'/'.$config_name;
//
//        if(file_exists())

    }

}
