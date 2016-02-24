<?php namespace Interpro\QuickStorage\Laravel\Handle;

use Illuminate\Support\Facades\Log;
use Interpro\QuickStorage\Concept\Command\UpdateGroupItemCommand;
use Interpro\QuickStorage\Laravel\Model\Block;
use Interpro\QuickStorage\Laravel\Model\Bool;
use Interpro\QuickStorage\Laravel\Model\Group;
use Interpro\QuickStorage\Laravel\Model\Imageitem;
use Interpro\QuickStorage\Laravel\Model\Numb;
use Interpro\QuickStorage\Laravel\Model\Pdatetime;
use Interpro\QuickStorage\Laravel\Model\Stringfield;
use Interpro\QuickStorage\Laravel\Model\Textfield;

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
        $this->updateGroupItem($command->group_id, $command->data_arr);
    }

    public function updateGroupItem($group_id, $dataobj)
    {

        $group_item = Group::find($group_id);

        if($group_item)
        {
            $block_name = $group_item->block_name;
            $group_name = $group_item->group_name;

            if(array_key_exists('show', $dataobj))
            {
                $group_item->show = $dataobj['show'] == "true" ? true : false;
            }

            if(array_key_exists('sorter', $dataobj))
            {
                $group_item->sorter = $dataobj['sorter'];
            }

            if(array_key_exists('owner', $dataobj))
            {
                $group_item->owner_id = $dataobj['owner'];
            }

            if(array_key_exists('slug', $dataobj))
            {
                $group_item->slug = $dataobj['slug'];
            }

            $qstorage = config('qstorage');

            if(array_key_exists($block_name, $qstorage))
            {
                $groupstruct = config('qstorage')[$block_name]['groups'][$group_name];

                foreach(['stringfields', 'textfields', 'numbs', 'images', 'bools', 'pdatetimes'] as $typename) {

                    if(array_key_exists($typename, $dataobj) && array_key_exists($typename, $groupstruct)){

                        $data_fs = $dataobj[$typename];

                        foreach($groupstruct[$typename] as $fieldname)
                        {
                            if(array_key_exists($fieldname, $data_fs)){

                                if($typename == 'stringfields'){
                                    $field = Stringfield::firstOrNew(['block_name'=>$block_name, 'name'=>$fieldname, 'group_id'=>$group_id]);
                                    $field->value = $data_fs[$fieldname];
                                    $group_item->stringfields()->save($field);

                                }else if($typename == 'textfields'){
                                    $field = Textfield::firstOrNew(['block_name'=>$block_name, 'name'=>$fieldname, 'group_id'=>$group_id]);
                                    $field->value = $data_fs[$fieldname];
                                    $group_item->textfields()->save($field);

                                }else if($typename == 'numbs'){
                                    $field = Numb::firstOrNew(['block_name'=>$block_name, 'name'=>$fieldname, 'group_id'=>$group_id]);
                                    $field->value = $data_fs[$fieldname];
                                    $group_item->numbs()->save($field);

                                }else if($typename == 'bools'){
                                    $field = Bool::firstOrNew(['block_name'=>$block_name, 'name'=>$fieldname, 'group_id'=>$group_id]);
                                    $field->value = $data_fs[$fieldname] == "true" ? true : false;
                                    $group_item->bools()->save($field);

                                }else if($typename == 'pdatetimes'){
                                    $field = Pdatetime::firstOrNew(['block_name'=>$block_name, 'name'=>$fieldname, 'group_id'=>$group_id]);
                                    $field->value = $data_fs[$fieldname];
                                    $group_item->pdatetimes()->save($field);

                                }else if($typename == 'images'){
                                    $field = Imageitem::firstOrNew(['block_name'=>$block_name, 'name'=>$fieldname, 'group_id'=>$group_id]);

                                    if(array_key_exists('alt', $data_fs[$fieldname])){
                                        $field->alt = $data_fs[$fieldname]['alt'];
                                    }
                                    if(array_key_exists('original_link', $data_fs[$fieldname])){
                                        $field->original_link = $data_fs[$fieldname]['original_link'];
                                    }
                                    if(array_key_exists('primary_link', $data_fs[$fieldname])){
                                        $field->primary_link = $data_fs[$fieldname]['primary_link'];
                                    }
                                    if(array_key_exists('secondary_link', $data_fs[$fieldname])){
                                        $field->secondary_link = $data_fs[$fieldname]['secondary_link'];
                                    }
                                    if(array_key_exists('icon_link', $data_fs[$fieldname])){
                                        $field->icon_link = $data_fs[$fieldname]['icon_link'];
                                    }
                                    if(array_key_exists('preview_link', $data_fs[$fieldname])){
                                        $field->preview_link = $data_fs[$fieldname]['preview_link'];
                                    }
                                    if(array_key_exists('prefix', $data_fs[$fieldname])){
                                        $field->prefix = $data_fs[$fieldname]['prefix'];
                                    }

                                    $field->cache_index++;

                                    $group_item->images()->save($field);

                                }
                            }
                        }
                    }
                }

                $group_item->save();

            }else{
                throw new \Exception('Не нашел элемент группы по id по имени в БД '.$group_id);
            }
        }
    }



}
