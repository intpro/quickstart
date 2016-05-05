<?php namespace Interpro\QuickStorage\Laravel\Handle\Image;

use Illuminate\Support\Facades\App;
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
        $extension = $command->req_orig_file->getClientOriginalExtension();
        //Заглушка для векторных форматов, где не надо ни ресайзить ни кропить, один файл для всех назначений
        if($extension == 'svg'){

            $pathResolver = App::make('Interpro\ImageFileLogic\Concept\PathResolver');
            $report       = App::make('Interpro\ImageFileLogic\Concept\Report');

            $images_dir = $pathResolver->getImageDir();

            $new_image_name = $config_name.'_0.svg';

            $command->req_orig_file->move(
                $images_dir,
                $new_image_name
            );

            $report->setImageResize($config_name.'_0', 'preview',   $new_image_name);
            $report->setImageResize($config_name.'_0', 'original',  $new_image_name);
            $report->setImageResize($config_name.'_0', 'primary',   $new_image_name);
            $report->setImageResize($config_name.'_0', 'secondary', $new_image_name);
            $report->setImageResize($config_name.'_0', 'icon',      $new_image_name);

        }else{

            $fields = $this->imageRepository->getBlockImage($command->block_name, $command->image_name);

            $imageItem = new ImageItem($config_name.'_0', $fields);

            $headAction = new CreateImageAction($command->req_orig_file);

            //К голове присоединяется остальная цепочка
            $this->actionChainFactory->buildChain($headAction, 'update', $config_name);

            $headAction->applyFor($imageItem);
        }

    }

}
