<?php namespace Interpro\QuickStorage\Laravel\Handle\Crop;

use Illuminate\Support\Facades\Log;
use Interpro\ImageFileLogic\Concept\CropConfig;
use Interpro\ImageFileLogic\Concept\Croper;
use Interpro\ImageFileLogic\Concept\Exception\ImageFileSystemException;
use Interpro\ImageFileLogic\Concept\ImageConfig;
use Interpro\ImageFileLogic\Concept\PathResolver;
use Interpro\Placeholder\Concept\PlaceholderAgent;
use Interpro\QuickStorage\Concept\Exception\CropNotFoundException;
use Interpro\QuickStorage\Concept\QSource;
use Interpro\QuickStorage\Laravel\Model\Cropitem;
use Intervention\Image\Facades\Image;

abstract class CropCommandHandler
{
    protected $qSource;
    protected $crop_config;
    protected $image_config;
    protected $croper;
    protected $phAgent;
    protected $path_prefix;
    protected $pathResolver;

    /**
     * Interpro\ImageFileLogic\Concept\QSource $qSource
     * Interpro\ImageFileLogic\Concept\Croper $croper
     * Interpro\ImageFileLogic\Concept\CropConfig $crop_config
     * Interpro\ImageFileLogic\Concept\ImageConfig $image_config
     *
     * @return void
     */
    public function __construct(QSource $qSource, Croper $croper, CropConfig $crop_config, ImageConfig $image_config, PlaceholderAgent $phAgent, PathResolver $pathResolver)
    {
        $this->qSource      = $qSource;
        $this->croper       = $croper;
        $this->crop_config  = $crop_config;
        $this->image_config = $image_config;
        $this->phAgent      = $phAgent;
        $this->pathResolver = $pathResolver;

        $config_path_prefix = config('imagefilelogic.crop_path_prefix');

        if($config_path_prefix){
            $this->path_prefix = $config_path_prefix;
        }else{
            $this->path_prefix = 'crops/';
        }
    }

    //Тех. долг неправильной архитектуре картинок-кропов костыльный метод получения расширения
    private function getExt($path_wo_ext)
    {
        foreach (glob($path_wo_ext.'*.*') as $file)
        {
            $inf = pathinfo($file);
            return $inf['extension'];
        }

        throw new ImageFileSystemException('Не найден оригинал картинки для пути '.$path_wo_ext.'!');
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
//            $image_name = $item['block_name'].'_'.$item['image_name'];
//            $file_name = $image_name.'_0';
//            $target_name = $file_name.'_'.$item['target_sufix'];
//            $result_name = $file_name.'_'.$item['name'];
//
//            $color = $this->crop_config->getColor($image_name, $item['name']);
//
//            $this->croper->crop(
//                $file_name,
//                $target_name,
//                $result_name,
//                $item['target_x1'],
//                $item['target_y1'],
//                $item['target_x2'],
//                $item['target_y2'],
//                $color
//            );
//
            $cropModel = Cropitem::find($item['id']);
            $cropModel->cache_index++;
            $cropModel->save();
        }
    }

    private function cropGroupItems($crop_models)
    {
        foreach($crop_models as $item)
        {
//            $image_name = $item['group_name'].'_'.$item['image_name'];
//            $file_name = $image_name.'_'.$item['group_id'];
//            $target_name = $file_name.'_'.$item['target_sufix'];
//            $result_name = $file_name.'_'.$item['name'];
//
//            $color = $this->crop_config->getColor($image_name, $item['name']);
//
//            $this->croper->crop(
//                $file_name,
//                $target_name,
//                $result_name,
//                $item['target_x1'],
//                $item['target_y1'],
//                $item['target_x2'],
//                $item['target_y2'],
//                $color
//            );
//
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

                        $crop->link         = $this->phAgent->getLink($params['width'], $params['height'], $color = '#808080');
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

                        $crop = Cropitem::firstOrNew(['block_name' => $block_name, 'group_name' => $group_name, 'group_id' => $group_id, 'name' => $crop_name, 'image_name' => $image_name]);

                        $crop->image_id     = $image_id;
                        $crop->link         = $this->phAgent->getLink($params['width'], $params['height'], $color = '#808080');
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

        $images_collection = $this->qSource->oneImageQueryForGroup($block_name, $group_name, $group_id);

        foreach($images_collection as $image_fields)
        {
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
                        where('image_id', $image_id)->first();

                    if(!$crop)
                    {
                        $man_width = $this->image_config->getWidth($image_key, $params['man']);
                        $man_height = $this->image_config->getHeight($image_key, $params['man']);

                        $target_width = $this->image_config->getWidth($image_key, $params['target']);
                        $target_height = $this->image_config->getHeight($image_key, $params['target']);

                        $result_width = floor($target_width*($params['width']/$man_width));
                        $result_height = floor($target_height*($params['height']/$man_height));

                        $crop = Cropitem::firstOrNew(['block_name' => $block_name, 'group_name' => $group_name, 'group_id' => $group_id, 'name' => $crop_name, 'image_name' => $image_name]);

                        $crop->image_id     = $image_id;
                        $crop->link         = $this->phAgent->getLink($params['width'], $params['height'], $color = '#808080');
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

    public function updateDBForBlock($block_name)
    {
        $images_collection = $this->qSource->imageQueryForBlock($block_name);

        $crop_dir = $this->pathResolver->getImageCropDir();

        foreach($images_collection as $image_fields)
        {
            $image_name = $image_fields['name'];

            $image_key  = $block_name.'_'.$image_name;
            $image_id   = $image_fields['id'];

            $crops = $this->crop_config->getConfig($image_key);

            foreach($crops as $crop_name => $params)
            {
                $man_name = $this->crop_config->getMan($image_key, $crop_name);
                $target_name = $this->crop_config->getTarget($image_key, $crop_name);

                $crop = Cropitem::where('block_name',$block_name)->
                    where('group_id', 0)->
                    where('name', $crop_name)->
                    where('image_name', $image_name)->first();


                $crop_half_path = $crop_dir.'/'.$image_key.'_0_'.$crop_name;

                $crop_file = $this->phAgent->getLink($params['width'], $params['height'], $color = '#808080');

                foreach (glob($crop_half_path.'*.*') as $file)
                {
                    $inf = pathinfo($file);
                    $ext = $inf['extension'];

                    $crop_file = 'crops/'.$image_key.'_0_'.$crop_name.'.'.$ext;
                }

                if(!$crop)
                {
                    $crop = new Cropitem();
                    $crop->name = $crop_name;
                    $crop->block_name = $block_name;
                    $crop->image_name = $image_name;
                    $crop->group_id = 0;
                    $crop->cache_index = 0;
                }

                $crop->image_id = $image_id;
                $crop->man_sufix = $man_name;
                $crop->target_sufix = $target_name;
                $crop->cache_index++;
                $crop->image_id    = $image_id;
                $crop->link        = $crop_file;

                $crop->save();
            }
        }
    }

    public function updateDBForGroupItem($block_name, $group_name, $group_id)
    {
        $image_dir = $this->pathResolver->getImageDir();

        //На середину - по умолчанию
        $images_collection = $this->qSource->oneImageQueryForGroup($block_name, $group_name, $group_id);

        $crop_dir = $this->pathResolver->getImageCropDir();

        foreach($images_collection as $image_fields)
        {
            $image_name = $image_fields['name'];

            $image_key  = $group_name.'_'.$image_name;
            $image_id   = $image_fields['id'];

            $crops = $this->crop_config->getConfig($image_key);

            foreach($crops as $crop_name => $params)
            {
                $man_name = $this->crop_config->getMan($image_key, $crop_name);
                $target_name = $this->crop_config->getTarget($image_key, $crop_name);

                $crop = Cropitem::where('block_name',$block_name)->
                    where('group_name', $group_name)->
                    where('group_id', $group_id)->
                    where('name', $crop_name)->
                    where('image_name', $image_name)->first();

                $crop_half_path = $crop_dir.'/'.$image_key.'_'.$group_id.'_'.$crop_name;

                $crop_file = $this->phAgent->getLink($params['width'], $params['height'], $color = '#808080');

                foreach (glob($crop_half_path.'*.*') as $file)
                {
                    $inf = pathinfo($file);
                    $ext = $inf['extension'];

                    $crop_file = 'crops/'.$image_key.'_'.$group_id.'_'.$crop_name.'.'.$ext;
                }

                if(!$crop)
                {
                    $crop = new Cropitem();
                    $crop->block_name = $block_name;
                    $crop->group_name = $group_name;
                    $crop->group_id   = $group_id;
                    $crop->name       = $crop_name;
                    $crop->image_name = $image_name;
                    $crop->cache_index = 0;
                }

                $crop->image_id = $image_id;
                $crop->man_sufix = $man_name;
                $crop->target_sufix = $target_name;
                $crop->cache_index++;
                $crop->link        = $crop_file;

                $crop->save();
            }
        }
    }

}
