<?php namespace Interpro\QuickStorage\Concept\Command;

class ReinitOneBlockCommand extends Command {

    public $block_name;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct($block_name)
    {
        $this->block_name = $block_name;
    }

}
