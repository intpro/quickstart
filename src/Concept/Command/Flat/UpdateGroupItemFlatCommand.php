<?php namespace Interpro\QuickStorage\Concept\Command\Flat;

use Interpro\QuickStorage\Concept\Command\Command;

class UpdateGroupItemFlatCommand extends Command {

    public $data_arr;

    /**
     * @param array $data_arr
     *
     * @return void
     */
    public function __construct($data_arr)
    {
        $this->data_arr = $data_arr;
    }

}
