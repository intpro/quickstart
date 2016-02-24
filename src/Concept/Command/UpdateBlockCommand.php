<?php namespace Interpro\QuickStorage\Concept\Command;

class UpdateBlockCommand extends Command {

    public $block_name;
    public $data_arr;
	/**
	 * Update a new command instance.
	 *
	 * @return void
	 */
	public function __construct($block_name, $data_arr)
	{
		$this->block_name = $block_name;
        $this->data_arr = $data_arr;
	}

}
