<?php

namespace Interpro\QuickStorage\Concept\Sorting;

interface GroupSorting
{

    /**
     *
     * @param $query
     *
     * @return mixed
     */
    public function apply($query);
}
