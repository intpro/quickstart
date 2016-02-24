<?php

namespace Interpro\QuickStorage\Laravel\Model;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{

    public function block()
    {
        return $this->belongsTo('Interpro\QuickStorage\Laravel\Model\Block', 'block_name');
    }

    public function owner()
    {
        return $this->belongsTo('Interpro\QuickStorage\Laravel\Model\Group', 'owner_id');
    }

    public function groups()
    {
        return $this->hasMany('Interpro\QuickStorage\Laravel\Model\Group', 'owner_id');
    }

    public function images() {

        return $this->hasMany('Interpro\QuickStorage\Laravel\Model\Imageitem', 'group_id');

    }

    public function stringfields() {

        return $this->hasMany('Interpro\QuickStorage\Laravel\Model\Stringfield', 'group_id');

    }

    public function textfields() {

        return $this->hasMany('Interpro\QuickStorage\Laravel\Model\Textfield', 'group_id');

    }

    public function numbs() {

        return $this->hasMany('Interpro\QuickStorage\Laravel\Model\Numb', 'group_id');

    }

    public function pdatetimes() {

        return $this->hasMany('Interpro\QuickStorage\Laravel\Model\Pdatetime', 'group_id');

    }

    public function bools() {

        return $this->hasMany('Interpro\QuickStorage\Laravel\Model\Bool', 'group_id');

    }

    public function saveGroupItem($dataobj)
    {

        if(array_key_exists('show', $dataobj))
        {
            $this->show = $dataobj['show'] == "true" ? true : false;
        }

        if(array_key_exists('sorter', $dataobj))
        {
            $this->sorter = $dataobj['sorter'];
        }

        if(array_key_exists('owner', $dataobj))
        {
            $this->owner_id = $dataobj['owner'];
        }

        $qstorage = config('qstorage');

        if(array_key_exists($this->block_name, $qstorage))
        {
            $groupstruct = config('qstorage')[$this->block_name]['groups'][$this->group_name];

            foreach(['stringfields', 'textfields', 'numbs', 'images', 'bools', 'pdatetimes'] as $typename) {

                if(array_key_exists($typename, $dataobj) && array_key_exists($typename, $groupstruct)){

                    $data_fs = $dataobj[$typename];

                    foreach($groupstruct[$typename] as $fieldname)
                    {
                        if(array_key_exists($fieldname, $data_fs)){

                            if($typename == 'stringfields'){
                                $field = Stringfield::firstOrNew(['block_name'=>$this->block_name, 'name'=>$fieldname, 'group_id'=>$dataobj['id']]);
                                $field->value = $data_fs[$fieldname];
                                $this->stringfields()->save($field);

                            }else if($typename == 'textfields'){
                                $field = Textfield::firstOrNew(['block_name'=>$this->block_name, 'name'=>$fieldname, 'group_id'=>$dataobj['id']]);
                                $field->value = $data_fs[$fieldname];
                                $this->textfields()->save($field);

                            }else if($typename == 'numbs'){
                                $field = Numb::firstOrNew(['block_name'=>$this->block_name, 'name'=>$fieldname, 'group_id'=>$dataobj['id']]);
                                $field->value = $data_fs[$fieldname];
                                $this->numbs()->save($field);

                            }else if($typename == 'bools'){
                                $field = Bool::firstOrNew(['block_name'=>$this->block_name, 'name'=>$fieldname, 'group_id'=>$dataobj['id']]);
                                $field->value = $data_fs[$fieldname] == "true" ? true : false;
                                $this->bools()->save($field);

                            }else if($typename == 'pdatetimes'){
                                $field = Pdatetime::firstOrNew(['block_name'=>$this->block_name, 'name'=>$fieldname, 'group_id'=>$dataobj['id']]);
                                $field->value = $data_fs[$fieldname];
                                $this->pdatetimes()->save($field);

                            }else if($typename == 'images'){
                                $field = Imageitem::firstOrNew(['block_name'=>$this->block_name, 'name'=>$fieldname, 'group_id'=>$dataobj['id']]);

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

                                $this->images()->save($field);

                            }
                        }
                    }
                }
            }

            $this->save();

            return 'OK';

        }else{
            return 'Block key '.$this->block_name.' not found.';
        }
    }

    public function deleteGroupItem(& $deleted)
    {
        $id = $this->id;

        $groups = Group::where('owner_id', '=', $id)->get();

        foreach($groups as $item)
        {
            $item->deleteGroupItem($deleted);
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

        $this->delete();

        $deleted[] = $id;

    }

}
