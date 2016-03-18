<?php

namespace Interpro\QuickStorage\Concept\Param;

interface GroupParam
{
    /**
     * @param string $group_name
     * @param string $param_name
     * @param $value
     *
     * @return void
     */
    public function set($group_name, $param_name, $value);

    /**
     * @param string $group_name
     * @param string $param_name
     *
     * @return mixed
     */
    public function get($group_name, $param_name);


    /**
     * @param string $group_name
     * @param $group_q
     *
     * @return void
     */
    public function apply($group_name, $group_q);

}
