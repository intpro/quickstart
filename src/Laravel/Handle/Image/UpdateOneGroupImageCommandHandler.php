<?php namespace Interpro\QuickStorage\Laravel\Handle\Image;

use Illuminate\Support\Facades\App;
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
        $extension = $command->req_orig_file->getClientOriginalExtension();
        //Заглушка для векторных форматов, где не надо ни ресайзить ни кропить, один файл для всех назначений
        if($extension == 'svg'){

            $pathResolver = App::make('Interpro\ImageFileLogic\Concept\PathResolver');
            $report       = App::make('Interpro\ImageFileLogic\Concept\Report');

            $images_dir = $pathResolver->getImageDir();

            $new_image_name = $config_name.'_'.$command->group_id.'.svg';

            $command->req_orig_file->move(
                $images_dir,
                $new_image_name
            );

            $report->setImageResize($config_name.'_'.$command->group_id, 'preview',   $new_image_name);
            $report->setImageResize($config_name.'_'.$command->group_id, 'original',  $new_image_name);
            $report->setImageResize($config_name.'_'.$command->group_id, 'primary',   $new_image_name);
            $report->setImageResize($config_name.'_'.$command->group_id, 'secondary', $new_image_name);
            $report->setImageResize($config_name.'_'.$command->group_id, 'icon',      $new_image_name);

        }else{

            $fields = $this->qSource->oneImageQueryForGroup($command->block_name, $command->group_name, $command->group_id, $command->image_name);

            $imageItem = new ImageItem($config_name.'_'.$command->group_id, $fields);

            $headAction = new CreateImageAction($command->req_orig_file);

            //К голове присоединяется остальная цепочка
            $this->actionChainFactory->buildChain($headAction, 'update', $config_name);

            $headAction->applyFor($imageItem);
        }

    }

}
