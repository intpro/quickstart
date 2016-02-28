<?php namespace Interpro\QuickStorage\Concept\Command;

class ReinitGroupCommand extends Command {

    public $block_name;
    public $group_name;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct($block_name, $group_name)
    {
        $this->block_name = $block_name;
        $this->group_name = $group_name;
    }

}
