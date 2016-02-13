<?php namespace Interpro\QuickStorage\Concept\Command;

class UpdateGroupItemCommand extends Command {

    public $name;
	/**
	 * Update a new command instance.
	 *
	 * @return void
	 */
	public function __construct($name)
	{
		$this->name = $name;
	}

}
