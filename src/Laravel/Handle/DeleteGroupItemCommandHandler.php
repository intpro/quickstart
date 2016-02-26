<?php namespace Interpro\QuickStorage\Laravel\Handle;

use Illuminate\Support\Facades\Log;
use Interpro\QuickStorage\Concept\Command\DeleteGroupItemCommand;
use Interpro\QuickStorage\Laravel\Model\Block;
use Interpro\QuickStorage\Laravel\Model\Bool;
use Interpro\QuickStorage\Laravel\Model\Group;
use Interpro\QuickStorage\Laravel\Model\Imageitem;
use Interpro\QuickStorage\Laravel\Model\Numb;
use Interpro\QuickStorage\Laravel\Model\Pdatetime;
use Interpro\QuickStorage\Laravel\Model\Stringfield;
use Interpro\QuickStorage\Laravel\Model\Textfield;

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
     * @return array
     */
    public function handle(DeleteGroupItemCommand $command)
    {
        $deleted=[];

        $group_item = Group::find($command->group_id);

        if($group_item)
        {
            $this->deleteGroupItem($group_item, $deleted);
        }

        return $deleted;
    }

    public function deleteGroupItem($group_item, & $deleted)
    {
        $id = $group_item->id;

        $groups = Group::where('owner_id', '=', $id)->get();

        foreach($groups as $item)
        {
            $this->deleteGroupItem($item, $deleted);
        }

        $collection = Stringfield::where('group_id', '=', $id)->get();
        foreach($collection as $field){
            $field->delete();
        }

        $collection = Textfield::where('group_id', '=', $id)->get();
        foreach($collection as $field){
            $field->delete();
        }

        $collection = Numb::where('group_id', '=', $id)->get();
        foreach($collection as $field){
            $field->delete();
        }

        $collection = Bool::where('group_id', '=', $id)->get();
        foreach($collection as $field){
            $field->delete();
        }

        $collection = Pdatetime::where('group_id', '=', $id)->get();
        foreach($collection as $field){
            $field->delete();
        }

        $collection = Imageitem::where('group_id', '=', $id)->get();
        foreach($collection as $field){
            $field->delete();
        }

        $group_item->delete();

        $deleted[] = $id;

    }

}
