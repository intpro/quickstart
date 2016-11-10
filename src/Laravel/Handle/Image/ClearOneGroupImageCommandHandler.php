<?php namespace Interpro\QuickStorage\Laravel\Handle\Image;

use Interpro\ImageFileLogic\Concept\CropConfig;
use Interpro\ImageFileLogic\Concept\Croper;
use Interpro\ImageFileLogic\Concept\ImageLogicAgent;
use Interpro\ImageFileLogic\Laravel\Action\ExistImageAction;
use Interpro\QuickStorage\Concept\Command\Image\ClearOneGroupImageCommand;
use Interpro\QuickStorage\Concept\Exception\WrongImageFieldException;
use Interpro\QuickStorage\Laravel\Item\ImageItem;
use Interpro\QuickStorage\Laravel\Model\Cropitem;

class ClearOneGroupImageCommandHandler extends ImageCommandHandler
{
    private $imageLogicAgent;
    protected $croper;
    private $crop_config;

    /**
     * Interpro\ImageFileLogic\Concept\ImageLogicAgent $imageLogicAgent
     * Interpro\ImageFileLogic\Concept\Croper $croper
     *
     * @return void
     */
    public function __construct(ImageLogicAgent $imageLogicAgent, Croper $croper, CropConfig $crop_config)
    {
        $this->imageLogicAgent = $imageLogicAgent;
        $this->croper = $croper;
        $this->crop_config = $crop_config;
    }

    /**
     * @param  ClearOneGroupImageCommand  $command
     * @return void
     */
    public function handle(ClearOneGroupImageCommand $command)
    {

        $config_name = $command->group_name.'_'.$command->image_name;

        $config = $this->crop_config->getConfig($config_name);

        $fields = $this->qSource->oneImageQueryForGroup($command->block_name, $command->group_name, $command->group_id, $command->image_name);

        $imageItem = new ImageItem($config_name, $command->group_id, $fields);

        $headAction = new ExistImageAction();

        $this->actionChainFactory->buildChain($headAction, 'clear', $config_name);

        //1. Начиная с головы выполняем все действия над картинкой
        $headAction->applyFor($imageItem);


        //2. Картинки физически удалены вместе с ресайзами и оригиналом, теперь
        //заменяем картинки плэйсхолдерами
        $imageDB = Imageitem::where('block_name', $command->block_name)->
            where('group_name', $command->group_name)->
            where('group_id', $command->group_id)->
            where('image_name', $command->image_name)->first();

        if(!$imageDB)
        {
            throw new WrongImageFieldException('Не найдена картинка '.$command->image_name.' в базе данных для группы '.$command->group_name.' блока '.$command->block_name);
        }

        $imageDB->preview_link   = $this->imageLogicAgent->getPlaceholder($command->image_name, 'preview');
        $imageDB->primary_link   = $this->imageLogicAgent->getPlaceholder($command->image_name, 'primary');
        $imageDB->original_link  = $this->imageLogicAgent->getPlaceholder($command->image_name, 'primary');
        $imageDB->secondary_link = $this->imageLogicAgent->getPlaceholder($command->image_name, 'secondary');
        $imageDB->icon_link      = $this->imageLogicAgent->getPlaceholder($command->image_name, 'icon');

        $imageDB->save();

        //3. Накропим из плэйсхолдеров кропы
        //копипаст из CropCommandHandler (возможно переписать красивее?)
        //для кропов плэйсхолдеров сделать хранение как для ресайзов (не повторяться)
        $crop_models = $this->qSource->oneCropQueryForGroup($command->block_name, $command->group_name, $command->group_id);

        foreach($crop_models as $crop_item)
        {
            $image_conf_name = $crop_item['group_name'].'_'.$crop_item['image_name'];
            $file_name = $image_conf_name.'_'.$crop_item['group_id'];
            $target_name = $file_name.'_'.$crop_item['target_sufix'];
            $result_name = $file_name.'_'.$crop_item['name'];

            $color = $this->crop_config->getColor($image_conf_name, $crop_item['name']);

            $this->croper->crop(
                $target_name,
                $result_name,
                $crop_item['target_x1'],
                $crop_item['target_y1'],
                $crop_item['target_x2'],
                $crop_item['target_y2'],
                $color
            );

            $cropModel = Cropitem::find($crop_item['id']);
            $cropModel->cache_index++;
            $cropModel->save();
        }
    }
}
