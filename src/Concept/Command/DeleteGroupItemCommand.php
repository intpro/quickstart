<?php namespace Interpro\QuickStorage\Concept\Command;

class DeleteGroupItemCommand extends Command {

    public $name;
	/**
	 * Delete a new command instance.
	 *
	 * @return void
	 */
	public function __construct($name)
	{
		$this->name = $name;
	}

}
