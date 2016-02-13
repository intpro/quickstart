<?php namespace Interpro\QuickStorage\Laravel\Handle;

use Illuminate\Support\Facades\Log;
use Interpro\QuickStorage\Concept\Command\DeleteGroupItemCommand;

class DeleteGroupItemCommandHandler {

    /**
     * Update the command handler.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the command.
     *
     * @param DeleteGroupItemCommand  $command
     * @return void
     */
    public function handle(DeleteGroupItemCommand $command)
    {
        Log::info($command->name);
    }

}