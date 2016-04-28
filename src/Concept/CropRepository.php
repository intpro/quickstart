<?php

namespace Interpro\QuickStorage\Concept;

interface CropRepository
{
    /**
     * @param string $block_name
     * @param string $group_name
     * @param string $image_name
     * @param string $crop_name
     * @param int $group_id
     *
     * @return array
     */
    public function getCrop($block_name, $group_name, $image_name, $crop_name, $group_id);
}
