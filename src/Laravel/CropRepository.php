<?php
namespace Interpro\QuickStorage\Laravel;

use Interpro\QuickStorage\Concept\QSource as QSourceInterface;
use Interpro\QuickStorage\Concept\CropRepository as CropRepositoryInterface;
use Interpro\QuickStorage\Laravel\Item\CropItem;

class CropRepository implements CropRepositoryInterface
{
    private $crops;

    public function __construct(QSourceInterface $qSource)
    {
        $this->qSource = $qSource;
        $this->crops = [];
    }


    public function getCrop($block_name, $group_name, $image_name, $crop_name, $group_id)
    {
        if($group_id) //Если кроп принадлежит элементу группы
        {
            $crop_key = $group_name.'_'.$image_name.'_'.$crop_name;
            $crop_key_id = $crop_key.'_'.$group_id;

            $crop = null;

            if(array_key_exists($crop_key_id, $this->crops))
            {
                $crop = $this->crops[$crop_key_id];
            }else{
                $id_finded = false;

                $crops_array = $this->qSource->cropQueryForGroupForCrop($block_name, $group_name, $image_name, $crop_name);

                foreach($crops_array as $crop_fields)
                {
                    $current_group_id = $crop_fields['group_id'];
                    $current_key = $crop_key.'_'.$current_group_id;

                    $new_crop = new CropItem($current_key, $crop_fields);

                    $this->crops[$current_key] = $new_crop;

                    if($current_group_id == $group_id)
                    {
                        $crop = $new_crop;
                        $id_finded = true;
                    }
                }

                if(!$id_finded)
                {
                    throw new \Exception('Кроп '.$crop_key_id.' не найден в БД.');
                }
            }
        }else{

            $crop_key = $block_name.'_'.$image_name.'_'.$crop_name;

            if(array_key_exists($crop_key, $this->crops))
            {
                $crop = $this->crops[$crop_key];
            }else{

                $crop_fields = $this->qSource->cropQueryForBlockForCrop($block_name, $image_name, $crop_name);

                $crop = new CropItem($crop_key, $crop_fields);

                $this->crops[$crop_key] = $crop;
            }
        }

        return $crop;
    }

}
