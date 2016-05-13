<?php namespace Interpro\QuickStorage\Laravel\Handle\Flat;

use Illuminate\Support\Facades\Log;
use Interpro\QuickStorage\Concept\Command\Flat\UpdateGroupItemFlatCommand;
use Interpro\QuickStorage\Concept\FieldProviding\FieldSaveMediator;
use Interpro\QuickStorage\Laravel\Model\Bool;
use Interpro\QuickStorage\Laravel\Model\Group;
use Interpro\QuickStorage\Laravel\Model\Imageitem;
use Interpro\QuickStorage\Laravel\Model\Numb;
use Interpro\QuickStorage\Laravel\Model\Pdatetime;
use Interpro\QuickStorage\Laravel\Model\Stringfield;
use Interpro\QuickStorage\Laravel\Model\Textfield;

class UpdateGroupItemFlatCommandHandler {

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
     * @param  UpdateGroupItemFlatCommand  $command
     * @return void
     */
    public function handle(UpdateGroupItemFlatCommand $command)
    {
        $qstorage = config('qstorage');

        $data_arr = &$command->data_arr;

        $block_name = $data_arr['block_name'];
        $group_id   = $data_arr['group_id'];
        $group_name = $data_arr['group_name'];

        $group_item = Group::find($group_id);

        if($group_item)
        {
            if(array_key_exists($block_name, $qstorage))
            {
                $blockstruct = &$qstorage[$block_name];
                $groupstruct = &$blockstruct['groups'][$group_name];

                $field_types = [];
                foreach(['stringfields', 'textfields', 'numbs', 'images', 'bools', 'pdatetimes'] as $typename) {
                    if (array_key_exists($typename, $groupstruct)){
                        foreach($groupstruct[$typename] as $field_name){
                            $field_types[$field_name] = $typename;
                        }
                    }
                }

                if(array_key_exists('show', $data_arr))
                {
                    $group_item->show = $data_arr['show'] == "true" ? true : false;
                }

                if(array_key_exists('sorter', $data_arr))
                {
                    $group_item->sorter = $data_arr['sorter'];
                }

                if(array_key_exists('owner', $data_arr))
                {
                    $group_item->owner_id = $data_arr['owner'];
                }

                if(array_key_exists('slug', $data_arr))
                {
                    $group_item->slug = $data_arr['slug'];
                }

                $group_item->save();


                foreach($data_arr as $field_name => $field_val)
                {
                    if($field_name == 'block_name' or $field_name == 'group_name' or $field_name == 'show' or $field_name == 'sorter' or $field_name == 'owner' or $field_name == 'slug'){
                        continue;
                    }

                    if(array_key_exists($field_name, $field_types)){

                        $typename = $field_types[$field_name];

                        if($typename == 'stringfields'){
                            $field = Stringfield::firstOrNew(['block_name'=>$block_name, 'name'=>$field_name, 'group_id'=>$group_id, 'group_name'=>$group_name]);
                            $field->value = $field_val;
                            $group_item->stringfields()->save($field);

                        }else if($typename == 'textfields'){
                            $field = Textfield::firstOrNew(['block_name'=>$block_name, 'name'=>$field_name, 'group_id'=>$group_id, 'group_name'=>$group_name]);
                            $field->value = $field_val;
                            $group_item->textfields()->save($field);

                        }else if($typename == 'numbs'){
                            $field = Numb::firstOrNew(['block_name'=>$block_name, 'name'=>$field_name, 'group_id'=>$group_id, 'group_name'=>$group_name]);
                            $field->value = $field_val;
                            $group_item->numbs()->save($field);

                        }else if($typename == 'bools'){
                            $field = Bool::firstOrNew(['block_name'=>$block_name, 'name'=>$field_name, 'group_id'=>$group_id, 'group_name'=>$group_name]);
                            $field->value = $field_val == "true" ? true : false;
                            $group_item->bools()->save($field);

                        }else if($typename == 'pdatetimes'){
                            $field = Pdatetime::firstOrNew(['block_name'=>$block_name, 'name'=>$field_name, 'group_id'=>$group_id, 'group_name'=>$group_name]);
                            $field->value = $field_val;
                            $group_item->pdatetimes()->save($field);

                        }else if($typename == 'images'){
                            $field = Imageitem::firstOrNew(['block_name'=>$block_name, 'name'=>$field_name, 'group_id'=>$group_id, 'group_name'=>$group_name]);

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

                            $group_item->images()->save($field);

                        }

                    }else{
                        Log::info('Ошибка при сохранении элемента группы('.$group_name.'): нет поля '.$field_name.' ни в одном типе в настройках блока!');
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
            throw new \Exception('Не нашел элемент группы по id в БД '.$group_id);
        }
    }



}
