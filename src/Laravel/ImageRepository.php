<?php
namespace Interpro\QuickStorage\Laravel;

use Interpro\QuickStorage\Concept\QSource as QSourceInterface;

class ImageRepository implements \Interpro\QuickStorage\Concept\ImageRepository
{
    private $block_images;
    private $group_images;
    private $qSource;

    public function __construct(QSourceInterface $qSource)
    {
        $this->qSource = $qSource;
        $this->block_images = [];
        $this->group_images = [];
    }

    private function createIfNotExistBlock($block_name)
    {
        if(!array_key_exists($block_name, $this->block_images))
        {
            $images_array = $this->qSource->imageQueryForBlock($block_name);

            $this->block_images[$block_name] = [];

            $block_arr = & $this->block_images[$block_name];

            foreach($images_array as $image)
            {
                $block_arr[$image['name']] = $image;
            }
        }
    }

    private function createIfNotExistGroup($block_name, $group_name)
    {
        if(!array_key_exists($group_name, $this->group_images))
        {
            $images_array = $this->qSource->imageQueryForGroup($block_name, $group_name);

            $this->group_images[$group_name] = [];

            $group_arr = & $this->group_images[$group_name];

            foreach($images_array as $image)
            {
                if(!array_key_exists($image['name'], $group_arr))
                {
                    $group_arr[$image['name']] = [];
                }

                $group_arr[$image['name']]['id_'.$image['group_id']] = $image;
            }
        }
    }

    /**
     * @param string $block_name
     * @param string $image_name
     *
     * @return array
     */
    public function getBlockImage($block_name, $image_name)
    {
        $this->createIfNotExistBlock($block_name);

        return $this->block_images[$block_name][$image_name];
    }

    /**
     * @param string $block_name
     * @param string $group_name
     * @param string $group_id
     * @param string $image_name
     *
     * @return array
     */
    public function getGroupImage($block_name, $group_name, $group_id, $image_name)
    {
        $this->createIfNotExistGroup($block_name, $group_name);

        return $this->group_images[$group_name][$image_name]['id_'.$group_id];
    }

    /**
     * @param string $block_name
     * @param string $group_name
     * @param string $image_name
     *
     * @return array
     */
    public function getAllGroupImages($block_name, $group_name, $image_name)
    {
        $this->createIfNotExistGroup($block_name, $group_name);

        return $this->group_images[$group_name][$image_name];
    }

}