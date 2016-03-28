<?php

namespace Interpro\QuickStorage\Concept;

interface QSource //Переименовать в Querier
{
    /**
     * @param string $block_name
     *
     * @return array
     */
    public function blockQuery($block_name);

    /**
     * @param string $block_name
     *
     * @return array
     */
    public function groupQuery($block_name, $group_name);

    /**
     * @param string $block_name
     * @param string $group_name
     *
     * @return array
     */
    public function groupCount($block_name, $group_name);

    /**
     * @param string $block_name
     * @param string $group_name
     * @param int $group_id
     *
     * @return array
     */
    public function groupItemQuery($block_name, $group_name, $group_id);

    /**
     * @param string $block_name
     * @param string $group_name
     * @param string $slug
     *
     * @return array
     */
    public function groupItemBySlugQuery($block_name, $group_name, $slug);

    /**
     * @param string $block_name
     * @param string $image_name
     *
     * @return array
     */
    public function oneImageQueryForBlock($block_name, $image_name);

    /**
     * @param string $block_name
     * @param string $group_name
     * @param int $group_id
     * @param string $image_name
     *
     * @return array
     */
    public function oneImageQueryForGroup($block_name, $group_name, $group_id, $image_name);

    /**
     * @param string $block_name
     *
     * @return array
     */
    public function imageQueryForBlock($block_name);

    /**
     * @param string $block_name
     * @param string $group_name
     *
     * @return array
     */
    public function imageQueryForGroup($block_name, $group_name);
}
