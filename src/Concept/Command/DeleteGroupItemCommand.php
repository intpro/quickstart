<?php namespace Interpro\QuickStorage\Concept\Command;

class DeleteGroupItemCommand extends Command {

    public $group_id;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct($group_id)
    {
        $this->group_id   = $group_id;
    }

}
