<?php

namespace Interpro\QuickStorage\Concept;

interface Repository //Переименовать в ШЛЮЗ!
{
    /**
     * @param string $block_name
     *
     * @return mixed
     */
    public function getBlock($block_name);

    /**
     * @param string $block_name
     *
     * @param string $group_name
     *
     * @param int $owner_id
     *
     * @return mixed
     */
    public function getGroup($block_name, $group_name, $owner_id=0);

    /**
     * @param string $block_name
     *
     * @param string $group_name
     *
     * @param int $group_id
     *
     * @return mixed
     */
    public function getGroupItem($block_name, $group_name, $group_id);

    /**
     * @param string $block_name
     *
     * @param string $group_name
     *
     * @param string $slug
     *
     * @return mixed
     */
    public function getGroupItemBySlug($block_name, $group_name, $slug);

}
