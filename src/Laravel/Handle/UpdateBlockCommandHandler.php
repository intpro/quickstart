<?php namespace Interpro\QuickStorage\Laravel\Handle;


use Illuminate\Support\Facades\Log;
use Interpro\QuickStorage\Concept\Command\UpdateBlockCommand;
use Interpro\QuickStorage\Laravel\Model\Block;
use Interpro\QuickStorage\Laravel\Model\Bool;
use Interpro\QuickStorage\Laravel\Model\Imageitem;
use Interpro\QuickStorage\Laravel\Model\Numb;
use Interpro\QuickStorage\Laravel\Model\Pdatetime;
use Interpro\QuickStorage\Laravel\Model\Stringfield;
use Interpro\QuickStorage\Laravel\Model\Textfield;

class UpdateBlockCommandHandler {

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
     * @param  UpdateBlockCommand  $command
     * @return void
     */
    public function handle(UpdateBlockCommand $command)
    {
        $this->updateBlock($command->block_name, $command->data_arr);
    }

    public function updateBlock($block_name, $dataobj)
    {
        $qstorage = config('qstorage');

        $block = Block::find($block_name);

        if($block)
        {
            if(array_key_exists($block->name, $qstorage))
            {

                $blockstruct = $qstorage[$block->name];

                if(array_key_exists('title', $dataobj))
                {
                    $block->title = $dataobj['title'];
                }

                foreach(['stringfields', 'textfields', 'numbs', 'images', 'bools', 'pdatetimes'] as $typename) {

                    if(array_key_exists($typename, $dataobj) && array_key_exists($typename, $blockstruct)){

                        $data_fs = $dataobj[$typename];

                        foreach($blockstruct[$typename] as $fieldname)
                        {
                            if(array_key_exists($fieldname, $data_fs)){

                                if($typename == 'stringfields'){
                                    $field = Stringfield::firstOrNew(['block_name'=>$block->name, 'name'=>$fieldname, 'group_id'=>0]);
                                    $field->value = $data_fs[$fieldname];
                                    $field->save();

                                }else if($typename == 'textfields'){
                                    $field = Textfield::firstOrNew(['block_name'=>$block->name, 'name'=>$fieldname, 'group_id'=>0]);
                                    $field->value = $data_fs[$fieldname];
                                    $field->save();

                                }else if($typename == 'numbs'){
                                    $field = Numb::firstOrNew(['block_name'=>$block->name, 'name'=>$fieldname, 'group_id'=>0]);
                                    $field->value = $data_fs[$fieldname];
                                    $field->save();

                                }else if($typename == 'bools'){
                                    $field = Bool::firstOrNew(['block_name'=>$block->name, 'name'=>$fieldname, 'group_id'=>0]);
                                    $field->value = $data_fs[$fieldname] == "true" ? true : false;
                                    $field->save();

                                }else if($typename == 'pdatetimes'){
                                    $field = Pdatetime::firstOrNew(['block_name'=>$block->name, 'name'=>$fieldname, 'group_id'=>0]);
                                    $field->value = $data_fs[$fieldname];
                                    $field->save();

                                }else if($typename == 'images'){
                                    $field = Imageitem::firstOrNew(['block_name'=>$block->name, 'name'=>$fieldname, 'group_id'=>0]);

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

                                    $field->save();

                                }
                            }
                        }
                    }
                }

                $block->save();

            }else{
                throw new \Exception('Не нашел блок по имени '.$block_name);
            }
        }else{
            throw new \Exception('Не нашел блок по имени в БД '.$block_name);
        }
    }


}