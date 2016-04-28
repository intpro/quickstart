<?php namespace Interpro\QuickStorage\Laravel\Handle;


use Illuminate\Support\Facades\Log;
use Interpro\QuickStorage\Concept\Command\Command;

class InitAllBlockCommandHandler extends InitOneBlockCommandHandler{

    /**
     * Handle the command.
     *
     * @param  Command  $command
     * @return void
     */
    public function handle(Command $command)
    {
        //Без параметра - все блоки
        $this->initBlocks();
    }

}
