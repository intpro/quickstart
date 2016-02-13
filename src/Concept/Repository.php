<?php

namespace Interpro\QuickStorage\Concept;

interface Repository
{
    /**
     * @param string $blockName
     *
     * @param bool $addshow
     *
     * @return mixed
     */
    public function getBlock($block_name, $addshow = false);
}
