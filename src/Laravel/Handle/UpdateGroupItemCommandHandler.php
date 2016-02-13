<?php namespace Interpro\QuickStorage\Laravel\Handle;

use Illuminate\Support\Facades\Log;
use Interpro\QuickStorage\Concept\Command\UpdateGroupItemCommand;

class UpdateGroupItemCommandHandler {

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
     * @param  UpdateGroupItemCommand  $command
     * @return void
     */
    public function handle(UpdateGroupItemCommand $command)
    {
        Log::info($command->name);
    }

}