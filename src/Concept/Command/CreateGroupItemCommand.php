<?php namespace Interpro\QuickStorage\Concept\Command;

class CreateGroupItemCommand extends Command {

    public $name;
	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct($name)
	{
		$this->name = $name;
	}

}
