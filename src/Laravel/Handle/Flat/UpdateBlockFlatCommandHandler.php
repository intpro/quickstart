<?php namespace Interpro\QuickStorage\Laravel\Handle\Flat;

use Illuminate\Support\Facades\Log;
use Interpro\QuickStorage\Concept\Command\Flat\UpdateBlockFlatCommand;
use Interpro\QuickStorage\Concept\FieldProviding\FieldSaveMediator;
use Interpro\QuickStorage\Laravel\Model\Block;
use Interpro\QuickStorage\Laravel\Model\Bool;
use Interpro\QuickStorage\Laravel\Model\Imageitem;
use Interpro\QuickStorage\Laravel\Model\Numb;
use Interpro\QuickStorage\Laravel\Model\Pdatetime;
use Interpro\QuickStorage\Laravel\Model\Stringfield;
use Interpro\QuickStorage\Laravel\Model\Textfield;

class UpdateBlockFlatCommandHandler {

    private $saveMediator;

    /**
     * Create the command handler.
     *
     * @return void
     */
    public function __construct(FieldSaveMediator $saveMediator)
    {
        $this->saveMediator = $saveMediator;
    }

    /**
     * Handle the command.
     *
     * @param  UpdateBlockFlatCommand  $command
     * @return void
     */
    public function handle(UpdateBlockFlatCommand $command)
    {
        $qstorage = config('qstorage');

        $data_arr = &$command->data_arr;

        $block_name = $data_arr['block_name'];

        $block = Block::find($block_name);

        if($block)
        {
            if(array_key_exists($block_name, $qstorage))
            {

                $blockstruct = $qstorage[$block_name];
                $field_types = [];
                foreach(['stringfields', 'textfields', 'numbs', 'images', 'bools', 'pdatetimes'] as $typename) {
                    if (array_key_exists($typename, $blockstruct)){
                        foreach($blockstruct[$typename] as $field_name){
                            $field_types[$field_name] = $typename;
                        }
                    }
                }

                if(array_key_exists('title', $data_arr))
                {
                    $block->title = $data_arr['title'];
                }

                $block->save();


                foreach($data_arr as $field_name => $field_val)
                {
                    if($field_name == 'block_name' or $field_name == 'title'){
                        continue;
                    }

                    if(array_key_exists($field_name, $field_types)){

                        $typename = $field_types[$field_name];

                        if($typename == 'stringfields'){
                            $field = Stringfield::firstOrNew(['block_name'=>$block_name, 'name'=>$field_name, 'group_id'=>0]);
                            $field->value = $field_val;
                            $field->save();

                        }else if($typename == 'textfields'){
                            $field = Textfield::firstOrNew(['block_name'=>$block_name, 'name'=>$field_name, 'group_id'=>0]);
                            $field->value = $field_val;
                            $field->save();

                        }else if($typename == 'numbs'){
                            $field = Numb::firstOrNew(['block_name'=>$block_name, 'name'=>$field_name, 'group_id'=>0]);
                            $field->value = $field_val;
                            $field->save();

                        }else if($typename == 'bools'){
                            $field = Bool::firstOrNew(['block_name'=>$block_name, 'name'=>$field_name, 'group_id'=>0]);
                            $field->value = $field_val == "true" ? true : false;
                            $field->save();

                        }else if($typename == 'pdatetimes'){
                            $field = Pdatetime::firstOrNew(['block_name'=>$block_name, 'name'=>$field_name, 'group_id'=>0]);
                            $field->value = $field_val;
                            $field->save();

                        }else if($typename == 'images'){
                            $field = Imageitem::firstOrNew(['block_name'=>$block_name, 'name'=>$field_name, 'group_id'=>0]);

                            if(array_key_exists('alt', $field_val)){
                                $field->alt = $field_val['alt'];
                            }
                            if(array_key_exists('original_link', $field_val)){
                                $field->original_link = $field_val['original_link'];
                            }
                            if(array_key_exists('primary_link', $field_val)){
                                $field->primary_link = $field_val['primary_link'];
                            }
                            if(array_key_exists('secondary_link', $field_val)){
                                $field->secondary_link = $field_val['secondary_link'];
                            }
                            if(array_key_exists('icon_link', $field_val)){
                                $field->icon_link = $field_val['icon_link'];
                            }
                            if(array_key_exists('preview_link', $field_val)){
                                $field->preview_link = $field_val['preview_link'];
                            }
                            if(array_key_exists('prefix', $field_val)){
                                $field->prefix = $field_val['prefix'];
                            }

                            $field->cache_index++;

                            $field->save();

                        }

                    }else{
                        Log::info('Ошибка при сохранении блока('.$block_name.'): нет поля '.$field_name.' ни в одном типе в настройках блока!');
                    }

                }

                //С доп. полями пока не ясно

                /*Сохраняем все внешние (отностиельно quickstorage поля)
                foreach($this->saveMediator->list as $suffix=>$saver) {
                    if(array_key_exists($suffix, $dataobj)){
                        $dataobj['entity_name'] = $block_name;
                        $saver->save($suffix, $dataobj[$suffix]);
                    }
                }*/


            }else{
                throw new \Exception('Не нашел блок по имени '.$block_name);
            }
        }else{
            throw new \Exception('Не нашел блок по имени в БД '.$block_name);
        }



    }


}