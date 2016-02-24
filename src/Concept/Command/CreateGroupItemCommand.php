<?php namespace Interpro\QuickStorage\Concept\Command;

class CreateGroupItemCommand extends Command {

    public $block_name;
    public $group_name;
    public $owner_id;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct($block_name, $group_name, $owner_id)
    {
        $this->block_name = $block_name;
        $this->group_name = $group_name;
        $this->owner_id   = $owner_id;
    }

}
