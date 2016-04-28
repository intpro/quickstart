<?php namespace Interpro\QuickStorage\Laravel\Handle\Crop;

use Interpro\ImageFileLogic\Concept\CropConfig;
use Interpro\ImageFileLogic\Concept\Croper;
use Interpro\ImageFileLogic\Concept\ImageConfig;
use Interpro\QuickStorage\Concept\Exception\CropNotFoundException;
use Interpro\QuickStorage\Concept\QSource;
use Interpro\QuickStorage\Laravel\Model\Cropitem;

abstract class CropCommandHandler
{
    protected $qSource;
    protected $crop_config;
    protected $image_config;
    protected $croper;

    /**
     * Interpro\ImageFileLogic\Concept\QSource $qSource
     * Interpro\ImageFileLogic\Concept\Croper $croper
     * Interpro\ImageFileLogic\Concept\CropConfig $crop_config
     * Interpro\ImageFileLogic\Concept\ImageConfig $image_config
     *
     * @return void
     */
    public function __construct(QSource $qSource, Croper $croper, CropConfig $crop_config, ImageConfig $image_config)
    {
        $this->qSource = $qSource;
        $this->croper = $croper;
        $this->crop_config = $crop_config;
        $this->image_config = $image_config;
    }

    public function refreshBlock($block_name)
    {
        $crop_models = $this->qSource->cropQueryForBlock($block_name);

        $this->cropBlock($crop_models);
    }

    public function refreshGroupItem($block_name, $group_name, $group_id)
    {
        $crop_models = $this->qSource->oneCropQueryForGroup($block_name, $group_name, $group_id);

        $this->cropGroupItems($crop_models);
    }

    public function refreshGroup($block_name, $group_name)
    {
        $crop_models = $this->qSource->cropQueryForGroup($block_name, $group_name);

        $this->cropGroupItems($crop_models);
    }

    private function cropBlock($crop_models)
    {
        foreach($crop_models as $item)
        {
            $image_name = $item['block_name'].'_'.$item['image_name'];
            $file_name = $image_name.'_0';
            $target_name = $file_name.'_'.$item['target_sufix'];
            $result_name = $file_name.'_'.$item['name'];

            $this->croper->crop(
                $target_name,
                $result_name,
                $item['target_x1'],
                $item['target_y1'],
                $item['target_x2'],
                $item['target_y2']
            );

            $cropModel = Cropitem::find($item['id']);
            $cropModel->cache_index++;
            $cropModel->save();
        }
    }

    private function cropGroupItems($crop_models)
    {
        foreach($crop_models as $item)
        {
            $image_name = $item['group_name'].'_'.$item['image_name'];
            $file_name = $image_name.'_'.$item['group_id'];
            $target_name = $file_name.'_'.$item['target_sufix'];
            $result_name = $file_name.'_'.$item['name'];

            $this->croper->crop(
                $target_name,
                $result_name,
                $item['target_x1'],
                $item['target_y1'],
                $item['target_x2'],
                $item['target_y2']
            );

            $cropModel = Cropitem::find($item['id']);
            $cropModel->cache_index++;
            $cropModel->save();
        }
    }

    public function initDBForBlock($block_name)
    {
        $config = $this->crop_config->getConfigAll();

        $images_collection = $this->qSource->imageQueryForBlock($block_name);

        foreach($images_collection as $image_fields)
        {
            $image_name = $image_fields['name'];

            $image_key  = $block_name.'_'.$image_name;
            $image_id   = $image_fields['id'];

            if(array_key_exists($image_key, $config))
            {
                $crops = &$config[$image_key];

                foreach($crops as $crop_name => $params)
                {
                    $crop = Cropitem::where('block_name', $block_name)->
                        where('name', $crop_name)->
                        where('image_name', $image_name)->
                        where('image_id', $image_id);

                    if(!$crop)
                    {
                        $man_width = $this->image_config->getWidth($image_key, $params['man']);
                        $man_height = $this->image_config->getHeight($image_key, $params['man']);

                        $target_width = $this->image_config->getWidth($image_key, $params['target']);
                        $target_height = $this->image_config->getHeight($image_key, $params['target']);

                        $result_width = floor($target_width*($params['width']/$man_width));
                        $result_height = floor($target_height*($params['height']/$man_height));

                        $crop = Cropitem::firstOrNew(['block_name' => $block_name, 'name' => $crop_name, 'image_name' => $image_name, 'image_id' => $image_id]);

                        $crop->link         = $image_key.'_0.jpg';
                        $crop->man_sufix    = $params['man'];
                        $crop->target_sufix = $params['target'];
                        $crop->cache_index  = 0;
                        $crop->man_x1       = 0;
                        $crop->man_y1       = 0;
                        $crop->man_x2       = $params['width'];
                        $crop->man_y2       = $params['height'];
                        $crop->target_x1    = 0;
                        $crop->target_y1    = 0;
                        $crop->target_x2    = $result_width;
                        $crop->target_y2    = $result_height;

                        $crop->save();
                    }
                }
            }
        }
    }

    public function initDBForGroup($block_name, $group_name)
    {
        $config = $this->crop_config->getConfigAll();

        $images_collection = $this->qSource->imageQueryForGroup($block_name, $group_name);

        foreach($images_collection as $image_fields)
        {
            $image_name = $image_fields['name'];
            $group_id = $image_fields['group_id'];
            $image_id   = $image_fields['id'];

            $image_key  = $group_name.'_'.$image_name;

            if(array_key_exists($image_key, $config))
            {
                $crops = &$config[$image_key];

                foreach($crops as $crop_name => $params)
                {
                    $crop = Cropitem::where('block_name', $block_name)->
                        where('group_name', $group_name)->
                        where('group_id', $group_id)->
                        where('name', $crop_name)->
                        where('image_name', $image_name)->
                        where('image_id', $image_id);

                    if(!$crop)
                    {
                        $man_width = $this->image_config->getWidth($image_key, $params['man']);
                        $man_height = $this->image_config->getHeight($image_key, $params['man']);

                        $target_width = $this->image_config->getWidth($image_key, $params['target']);
                        $target_height = $this->image_config->getHeight($image_key, $params['target']);

                        $result_width = floor($target_width*($params['width']/$man_width));
                        $result_height = floor($target_height*($params['height']/$man_height));

                        $crop = Cropitem::firstOrNew(['block_name' => $block_name, 'group_name' => $group_name, 'group_id' => $group_id, 'name' => $crop_name, 'image_name' => $image_name, 'image_id' => $image_id]);

                        $crop->link         = $image_key.'_'.$group_id.'_'.$crop_name.'.jpg';
                        $crop->man_sufix    = $params['man'];
                        $crop->target_sufix = $params['target'];
                        $crop->cache_index  = 0;
                        $crop->man_x1       = 0;
                        $crop->man_y1       = 0;
                        $crop->man_x2       = $params['width'];
                        $crop->man_y2       = $params['height'];
                        $crop->target_x1    = 0;
                        $crop->target_y1    = 0;
                        $crop->target_x2    = $result_width;
                        $crop->target_y2    = $result_height;

                        $crop->save();
                    }
                }
            }
        }
    }

    public function initDBForGroupItem($block_name, $group_name, $group_id)
    {
        $config = $this->crop_config->getConfigAll();

        $image_fields = $this->qSource->oneImageQueryForGroup($block_name, $group_name, $group_id);

        $image_name = $image_fields['name'];
        $image_id   = $image_fields['id'];

        $image_key  = $group_name.'_'.$image_name;

        if(array_key_exists($image_key, $config))
        {
            $crops = &$config[$image_key];

            foreach($crops as $crop_name => $params)
            {
                $crop = Cropitem::where('block_name', $block_name)->
                    where('group_name', $group_name)->
                    where('group_id', $group_id)->
                    where('name', $crop_name)->
                    where('image_name', $image_name)->
                    where('image_id', $image_id);

                if(!$crop)
                {
                    $man_width = $this->image_config->getWidth($image_key, $params['man']);
                    $man_height = $this->image_config->getHeight($image_key, $params['man']);

                    $target_width = $this->image_config->getWidth($image_key, $params['target']);
                    $target_height = $this->image_config->getHeight($image_key, $params['target']);

                    $result_width = floor($target_width*($params['width']/$man_width));
                    $result_height = floor($target_height*($params['height']/$man_height));

                    $crop = Cropitem::firstOrNew(['block_name' => $block_name, 'group_name' => $group_name, 'group_id' => $group_id, 'name' => $crop_name, 'image_name' => $image_name, 'image_id' => $image_id]);

                    $crop->link         = $image_key.'_'.$group_id.'_'.$crop_name.'.jpg';
                    $crop->man_sufix    = $params['man'];
                    $crop->target_sufix = $params['target'];
                    $crop->cache_index  = 0;
                    $crop->man_x1       = 0;
                    $crop->man_y1       = 0;
                    $crop->man_x2       = $params['width'];
                    $crop->man_y2       = $params['height'];
                    $crop->target_x1    = 0;
                    $crop->target_y1    = 0;
                    $crop->target_x2    = $result_width;
                    $crop->target_y2    = $result_height;

                    $crop->save();
                }
            }
        }

    }

    public function updateDBForBlock($block_name, $crops_config)
    {
        $images_collection = $this->qSource->imageQueryForBlock($block_name);

        foreach($images_collection as $image_fields)
        {
            $image_name = $image_fields['name'];

            $image_key  = $block_name.'_'.$image_name;
            $image_id   = $image_fields['id'];

            if(array_key_exists($image_key, $crops_config))
            {
                $crops = &$crops_config[$image_key];

                foreach($crops as $crop_name => $params)
                {
                    $man_x1 = $params['x1'];
                    $man_x2 = $params['x2'];
                    $man_y1 = $params['y1'];
                    $man_y2 = $params['y2'];

                    $man_name = $this->crop_config->getMan($image_key, $crop_name);
                    $target_name = $this->crop_config->getTarget($image_key, $crop_name);

                    $man_width = $this->image_config->getWidth($image_key, $man_name);
                    $man_height = $this->image_config->getHeight($image_key, $man_name);

                    $target_width = $this->image_config->getWidth($image_key, $target_name);
                    $target_height = $this->image_config->getHeight($image_key, $target_name);

                    $x_prop = ($man_width/$target_width);
                    $y_prop = ($man_height/$target_height);

                    $target_x1 = floor($man_x1*$x_prop);
                    $target_y1 = floor($man_y1*$y_prop);
                    $target_x2 = floor($man_x2*$x_prop);
                    $target_y2 = floor($man_y2*$y_prop);

                    $crop = Cropitem::where('block_name',$block_name)->
                        where('group_id', 0)->
                        where('name', $crop_name)->
                        where('image_name', $image_name)->
                        where('image_id', $image_id)->first();

                    if(!$crop)
                    {
                        throw new CropNotFoundException('Не найден кроп '.$crop_name.' в базе данных для картинки '.$image_name.' блока '.$block_name);
                    }

                    $crop->man_x1       = $man_x1;
                    $crop->man_y1       = $man_y1;
                    $crop->man_x2       = $man_x2;
                    $crop->man_y2       = $man_y2;
                    $crop->target_x1    = $target_x1;
                    $crop->target_y1    = $target_y1;
                    $crop->target_x2    = $target_x2;
                    $crop->target_y2    = $target_y2;

                    $crop->save();
                }
            }
        }
    }

    public function updateDBForGroupItem($block_name, $group_name, $group_id, $crops_config)
    {
        $image_fields = $this->qSource->oneImageQueryForGroup($block_name, $group_name, $group_id);

        $image_name = $image_fields['name'];

        $image_key  = $group_name.'_'.$image_name;
        $image_id   = $image_fields['id'];

        if(array_key_exists($image_key, $crops_config))
        {
            $crops = &$crops_config[$image_key];

            foreach($crops as $crop_name => $params)
            {
                $man_x1 = $params['x1'];
                $man_x2 = $params['x2'];
                $man_y1 = $params['y1'];
                $man_y2 = $params['y2'];

                $man_name = $this->crop_config->getMan($image_key, $crop_name);
                $target_name = $this->crop_config->getTarget($image_key, $crop_name);

                $man_width = $this->image_config->getWidth($image_key, $man_name);
                $man_height = $this->image_config->getHeight($image_key, $man_name);

                $target_width = $this->image_config->getWidth($image_key, $target_name);
                $target_height = $this->image_config->getHeight($image_key, $target_name);

                $x_prop = ($man_width/$target_width);
                $y_prop = ($man_height/$target_height);

                $target_x1 = floor($man_x1*$x_prop);
                $target_y1 = floor($man_y1*$y_prop);
                $target_x2 = floor($man_x2*$x_prop);
                $target_y2 = floor($man_y2*$y_prop);

                $crop = Cropitem::where('block_name',$block_name)->
                    where('group_name', $group_name)->
                    where('group_id', $group_id)->
                    where('name', $crop_name)->
                    where('image_name', $image_name)->
                    where('image_id', $image_id)->first();

                if(!$crop)
                {
                    throw new CropNotFoundException('Не найден кроп '.$crop_name.' в базе данных для картинки '.$image_name.' группы '.$group_name);
                }

                $crop->man_x1       = $man_x1;
                $crop->man_y1       = $man_y1;
                $crop->man_x2       = $man_x2;
                $crop->man_y2       = $man_y2;
                $crop->target_x1    = $target_x1;
                $crop->target_y1    = $target_y1;
                $crop->target_x2    = $target_x2;
                $crop->target_y2    = $target_y2;

                $crop->save();
            }
        }
    }

}