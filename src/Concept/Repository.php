<?php

namespace Interpro\QuickStorage\Concept;

interface Repository
{
    /**
     * @param string $blockName
     *
     * @return mixed
     */
    public function getBlock($block_name);

    /**
     * @param string $blockName
     *
     * @param bool $addshow
     *
     * @return mixed
     */
    public function getGroup($block_name, $group_name, $owner_id=0);

}
