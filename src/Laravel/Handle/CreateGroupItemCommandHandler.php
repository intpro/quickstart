<?php namespace Interpro\QuickStorage\Laravel\Handle;


use Illuminate\Support\Facades\Log;
use Interpro\QuickStorage\Concept\Command\CreateGroupItemCommand;

class CreateGroupItemCommandHandler {

    /**
     * Create the command handler.
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
     * @param  CreateGroupItemCommand  $command
     * @return void
     */
    public function handle(CreateGroupItemCommand $command)
    {
        Log::info($command->name);
    }

}