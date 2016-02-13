<?php

namespace Interpro\QuickStorage\Concept\Collection;

interface GroupCollection extends \Iterator
{
    /**
     * @return int
     */
    public function count();
}
