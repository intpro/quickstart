<?php namespace Interpro\QuickStorage\Concept\Command;

class UpdateGroupItemCommand extends Command {

    public $group_id;
    public $data_arr;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct($group_id, $data_arr)
    {
        $this->group_id   = $group_id;
        $this->data_arr   = $data_arr;
    }

}
