<?php

namespace Interpro\QuickStorage\Concept;

interface ImageRepository
{
    /**
     * @param string $block_name
     * @param string $image_name
     *
     * @return array
     */
    public function getBlockImage($block_name, $image_name);

    /**
     * @param string $block_name
     * @param string $group_name
     * @param string $group_id
     * @param string $image_name
     *
     * @return array
     */
    public function getGroupImage($block_name, $group_name, $group_id, $image_name);

    /**
     * @param string $block_name
     * @param string $group_name
     * @param string $image_name
     *
     * @return array
     */
    public function getAllGroupImages($block_name, $group_name, $image_name);

}
