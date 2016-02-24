<?php

namespace Interpro\QuickStorage\Laravel\Model;

use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    protected $primaryKey = 'name';
    public $timestamps = false;
    protected static $unguarded = true;

    public function groups() {

        return $this->hasMany('Interpro\QuickStorage\Laravel\Model\Group', 'block_name');

    }

    public function images() {

        return $this->hasMany('Interpro\QuickStorage\Laravel\Model\Imageitem', 'block_name');

    }

    public function stringfields() {

        return $this->hasMany('Interpro\QuickStorage\Laravel\Model\Stringfield', 'block_name');

    }

    public function textfields() {

        return $this->hasMany('Interpro\QuickStorage\Laravel\Model\Textfield', 'block_name');

    }

    public function numbs() {

        return $this->hasMany('Interpro\QuickStorage\Laravel\Model\Numb', 'block_name');

    }

    public function bools() {

        return $this->hasMany('Interpro\QuickStorage\Laravel\Model\Bool', 'block_name');

    }

    public function pdatetimes() {

        return $this->hasMany('Interpro\QuickStorage\Laravel\Model\Pdatetime', 'block_name');

    }

    public function saveBlock($dataobj)
    {
        $qstorage = config('qstorage');

        if(array_key_exists($this->name, $qstorage))
        {

            $blockstruct = $qstorage[$this->name];

            if(array_key_exists('title', $dataobj))
            {
                $this->title = $dataobj['title'];
            }

            foreach(['stringfields', 'textfields', 'numbs', 'images', 'bools', 'pdatetimes'] as $typename) {

                if(array_key_exists($typename, $dataobj) && array_key_exists($typename, $blockstruct)){

                    $data_fs = $dataobj[$typename];

                    foreach($blockstruct[$typename] as $fieldname)
                    {
                        if(array_key_exists($fieldname, $data_fs)){

                            if($typename == 'stringfields'){
                                $field = Stringfield::firstOrNew(['block_name'=>$this->name, 'name'=>$fieldname, 'group_id'=>0]);
                                $field->value = $data_fs[$fieldname];
                                $field->save();

                            }else if($typename == 'textfields'){
                                $field = Textfield::firstOrNew(['block_name'=>$this->name, 'name'=>$fieldname, 'group_id'=>0]);
                                $field->value = $data_fs[$fieldname];
                                $field->save();

                            }else if($typename == 'numbs'){
                                $field = Numb::firstOrNew(['block_name'=>$this->name, 'name'=>$fieldname, 'group_id'=>0]);
                                $field->value = $data_fs[$fieldname];
                                $field->save();

                            }else if($typename == 'bools'){
                                $field = Bool::firstOrNew(['block_name'=>$this->name, 'name'=>$fieldname, 'group_id'=>0]);
                                $field->value = $data_fs[$fieldname] == "true" ? true : false;
                                $field->save();

                            }else if($typename == 'pdatetimes'){
                                $field = Pdatetime::firstOrNew(['block_name'=>$this->name, 'name'=>$fieldname, 'group_id'=>0]);
                                $field->value = $data_fs[$fieldname];
                                $field->save();

                            }else if($typename == 'images'){
                                $field = Imageitem::firstOrNew(['block_name'=>$this->name, 'name'=>$fieldname, 'group_id'=>0]);

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

                                $field->save();

                            }
                        }
                    }
                }
            }

            $this->save();

            return 'OK';

        }else{
            return 'Key '.$this->name.' not found.';
        }

    }

    public function createGroupItem($group_name, $owner_id)
    {

        $groupstruct_invert = $this->getGroupsStruct();

        $dataArr = ['sorter'=>99,'show'=>true, 'stringfields'=>[], 'textfields'=>[], 'numbs'=>[], 'bools'=>[], 'pdatetimes'=>[], 'images'=>[]];

        $blockname = $this->name;

        $groupstruct = config('qstorage')[$this->name]['groups'][$group_name];

        $newGroupItem = new \Interpro\QuickStorage\Laravel\Model\Group();
        $newGroupItem->block_name = $blockname;
        $newGroupItem->group_name = $group_name;
        $newGroupItem->owner_id   = $owner_id;
        $newGroupItem->save();

        $dataArr['id'] = $newGroupItem->id;

        if(array_key_exists('stringfields', $groupstruct))
        {
            foreach($groupstruct['stringfields'] as $fieldname)
            {
                $stringfield = Stringfield::create(['block_name'=>$blockname, 'group_name'=>$group_name, 'name'=>$fieldname, 'group_id'=>$newGroupItem->id]);
                $newGroupItem->stringfields()->save($stringfield);
                $dataArr['stringfields'][$fieldname]='';
            }
        }

        if(array_key_exists('textfields', $groupstruct))
        {
            foreach($groupstruct['textfields'] as $fieldname)
            {
                $textfield = Textfield::firstOrNew(['block_name'=>$blockname, 'group_name'=>$group_name, 'name'=>$fieldname, 'group_id'=>$newGroupItem->id]);
                $newGroupItem->textfields()->save($textfield);
                $dataArr['textfields'][$fieldname]='';
            }
        }

        if(array_key_exists('numbs', $groupstruct))
        {
            foreach($groupstruct['numbs'] as $fieldname)
            {
                $numb = Numb::firstOrNew(['block_name'=>$blockname, 'group_name'=>$group_name, 'name'=>$fieldname, 'group_id'=>$newGroupItem->id]);
                $newGroupItem->numbs()->save($numb);
                $dataArr['numbs'][$fieldname]='';
            }
        }

        if(array_key_exists('bools', $groupstruct))
        {
            foreach($groupstruct['bools'] as $fieldname)
            {
                $boolitem = Bool::firstOrNew(['block_name'=>$blockname, 'group_name'=>$group_name, 'name'=>$fieldname, 'group_id'=>$newGroupItem->id]);
                $newGroupItem->bools()->save($boolitem);
                $dataArr['bools'][$fieldname]='';
            }
        }

        if(array_key_exists('pdatetimes', $groupstruct))
        {
            foreach($groupstruct['pdatetimes'] as $fieldname)
            {
                $dtitem = Pdatetime::firstOrNew(['block_name'=>$blockname, 'group_name'=>$group_name, 'name'=>$fieldname, 'group_id'=>$newGroupItem->id]);
                $dtitem->value = new \DateTime();
                $newGroupItem->pdatetimes()->save($dtitem);
                $dataArr['pdatetimes'][$fieldname]= $dtitem->value->format('d.m.Y H:i:s');

            }
        }

        if(array_key_exists('images', $groupstruct))
        {
            foreach($groupstruct['images'] as $fieldname)
            {
                $image = Imageitem::firstOrNew(['block_name'=>$blockname, 'group_name'=>$group_name, 'name'=>$fieldname, 'group_id'=>$newGroupItem->id]);
                $newGroupItem->images()->save($image);
                $dataArr['images'][$fieldname]=[
                    'alt'=>'',
                    'original_link'=>'',
                    'primary_link'=>'',
                    'secondary_link'=>'',
                    'icon_link'=>'',
                    'preview_link'=>'',
                    'prefix'=>''
                ];
            }
        }
        $newGroupItem->save();

        $dataArr['groups'] = array_fill_keys($groupstruct_invert[$group_name], []);

        return $dataArr;

    }

    private function getGroupsDepth(&$groupstruct_0, $groupname_x, $depth)
    {

        $depth++;
        $maxdepth = $depth;

        foreach ($groupstruct_0 as $groupname => $groupstruct)
        {
            if(array_key_exists('owner', $groupstruct) and $groupstruct['owner'] == $groupname_x)
            {
                $currdepth = $this->getGroupsDepth($groupstruct_0, $groupname, $depth);

                $maxdepth = $currdepth > $maxdepth ? $currdepth : $maxdepth;
            }
        }

        return $maxdepth;

    }

    private function getMainGroupsDepth()
    {

        $depth = 0;

        $groupstruct_0 = config('qstorage.'.$this->name)['groups'];

        foreach ($groupstruct_0 as $groupname => $groupstruct)
        {
            if(!array_key_exists('owner', $groupstruct))
            {
                $dataArr[$groupname] = [];

                $currdepth = $this->getGroupsDepth($groupstruct_0, $groupname, 0);

                $depth = $currdepth > $depth ? $currdepth : $depth;
            }
        }

        return $depth;
    }

    private static function addGroupsFunc($addshow, $depth, $currdepth = 1)
    {
        $currdepth++;

        if ($currdepth > $depth) {
            return function($query){};
        } else {
            $func = static::addGroupsFunc($addshow, $depth, $currdepth);
            return function($query) use ($func, $addshow)
            {
                $query->with([
                    'groups' => $func
                ]);

                if($addshow)
                {
                    $query->where('show', '=', true);
                }
            };
        }
    }

    private static function fillGroups($depth, & $groupstruct_invert, $groups, & $dataArr, $currdepth = 0)
    {
        $currdepth++;

        if($currdepth > $depth){
            return;
        }

        foreach($groups as $item)
        {

            $dataArrItem = ['updated_at'=>$item->updated_at->timestamp, 'id'=>$item->id, 'sorter'=>$item->sorter, 'show'=>$item->show, 'stringfields'=>[], 'textfields'=>[], 'images'=>[], 'bools'=>[], 'pdatetimes'=>[], 'numbs'=>[]];

            $group_name = $item->group_name;

//            if(!array_key_exists($group_name, $dataArr))
//            {
//                $dataArr[$group_name] = [];
//            }

            $fields = & $dataArrItem['stringfields'];
            foreach($item->stringfields as $stringfield)
            {
                $fields[$stringfield->name] = $stringfield->value;
            }

            $fields = & $dataArrItem['textfields'];
            foreach($item->textfields as $textfield)
            {
                $fields[$textfield->name] = $textfield->value;
            }

            $fields = & $dataArrItem['images'];
            foreach($item->images as $image)
            {
                $fields[$image->name] = [
                    'alt'=>$image->alt,
                    'original_link'=>$image->original_link,
                    'primary_link'=>$image->primary_link,
                    'secondary_link'=>$image->secondary_link,
                    'icon_link'=>$image->icon_link,
                    'preview_link'=>$image->preview_link,
                    'prefix'=>$image->prefix
                ];
            }

            $fields = & $dataArrItem['bools'];
            foreach($item->bools as $boolitem)
            {
                $fields[$boolitem->name] = $boolitem->value;
            }

            $fields = & $dataArrItem['pdatetimes'];
            foreach($item->pdatetimes as $dtitem)
            {
                $fields[$dtitem->name] = $dtitem->value;
            }

            $fields = & $dataArrItem['numbs'];
            foreach($item->numbs as $numb)
            {
                $fields[$numb->name] = $numb->value;
            }

            $dataArrItem['groups'] = array_fill_keys($groupstruct_invert[$group_name], []);

            $item_groups = $item->groups;

            static::fillGroups($depth, $groupstruct_invert, $item_groups, $dataArrItem['groups'], $currdepth);

            $dataArr[$group_name]['id'.$item->id] = $dataArrItem;
        }
    }

    public  function getGroupsStruct()
    {
        $groups_conf = config('qstorage.'.$this->name)['groups'];
        $groupstruct_invert = [];

        foreach ($groups_conf as $groupname => $_conf)
        {
            if(!array_key_exists($groupname, $groupstruct_invert))
            {
                $groupstruct_invert[$groupname] = [];
            }

            if(array_key_exists('owner', $_conf))
            {
                if(!array_key_exists($_conf['owner'], $groupstruct_invert))
                {
                    $groupstruct_invert[$_conf['owner']] = [];
                }

                $groupstruct_invert[$_conf['owner']][] = $groupname;
            }
        }

        return $groupstruct_invert;
    }

    private function getGroupItemsArray($addshow = false)
    {

        $groupstruct_0 = config('qstorage.'.$this->name)['groups'];
        $groupstruct_invert = $this->getGroupsStruct();

        $dataArr = [];

        foreach ($groupstruct_0 as $groupname => $groupstruct)
        {
            if(!array_key_exists('owner', $groupstruct))
            {
                $dataArr[$groupname] = [];
            }
        }

        $depth = $this->getMainGroupsDepth();


        //Построение, выполнение запроса
        $query_fields = ['stringfields', 'textfields', 'numbs', 'bools', 'pdatetimes', 'images'];

        if($depth>1)
        {
            $groupsFunc = static::addGroupsFunc($addshow, $depth);
            $query_fields['groups'] = $groupsFunc;
        } else {
            $query_fields[] = 'groups';
        }

        $query = \Interpro\QuickStorage\Laravel\Model\Group::where('block_name','=',$this->name)->with($query_fields)
            ->where('owner_id', '=', 0);

        if($addshow)
        {
            $query->where('show', '=', true);
        }

        $groups = $query->get();

        //Извлечение данных из результатов запроса
        static::fillGroups($depth, $groupstruct_invert, $groups, $dataArr);

        return $dataArr;
    }


    public static function  getBlocksDisplayArray($addshow=false, $block_name='')
    {

        if($block_name!=''){
            $blocks = Block::where('name', '=', $block_name)->with([
                'stringfields'=>function($query){
                    $query->where('group_id','=',0);
                },
                'textfields'=>function($query){
                        $query->where('group_id','=',0);
                    },
                'numbs'=>function($query){
                        $query->where('group_id','=',0);
                    },
                'bools'=>function($query){
                        $query->where('group_id','=',0);
                    },
                'pdatetimes'=>function($query){
                        $query->where('group_id','=',0);
                    },
                'images'=>function($query){
                        $query->where('group_id','=',0);
                    }
            ])->get();
        }else{
            $blocks = Block::with([
                'stringfields'=>function($query){
                        $query->where('group_id','=',0);
                    },
                'textfields'=>function($query){
                        $query->where('group_id','=',0);
                    },
                'numbs'=>function($query){
                        $query->where('group_id','=',0);
                    },
                'bools'=>function($query){
                        $query->where('group_id','=',0);
                    },
                'pdatetimes'=>function($query){
                        $query->where('group_id','=',0);
                    },
                'images'=>function($query){
                        $query->where('group_id','=',0);
                    }
            ])->get();
        }


        $dataArr = [];

        foreach($blocks as $block)
        {
            $dataArr[$block->name] = ['title'=>$block->title, 'stringfields'=>[], 'textfields'=>[], 'images'=>[], 'bools'=>[], 'pdatetimes'=>[], 'numbs'=>[]];

            $block_strfs = & $dataArr[$block->name]['stringfields'];
            foreach($block->stringfields as $stringfield)
            {
                $block_strfs[$stringfield->name] = $stringfield->value;
            }

            $block_textfs = & $dataArr[$block->name]['textfields'];
            foreach($block->textfields as $textfield)
            {
                $block_textfs[$textfield->name] = $textfield->value;
            }

            $block_images = & $dataArr[$block->name]['images'];
            foreach($block->images as $image)
            {
                $block_images[$image->name] = [
                    'alt'=>$image->alt,
                    'original_link'=>$image->original_link,
                    'primary_link'=>$image->primary_link,
                    'secondary_link'=>$image->secondary_link,
                    'icon_link'=>$image->icon_link,
                    'preview_link'=>$image->preview_link,
                    'prefix'=>$image->prefix
                ];
            }

            $block_bools = & $dataArr[$block->name]['bools'];
            foreach($block->bools as $boolitem)
            {
                $block_bools[$boolitem->name] = $boolitem->value;
            }

            $block_pdatetimes = & $dataArr[$block->name]['pdatetimes'];
            foreach($block->pdatetimes as $dtitem)
            {
                $block_pdatetimes[$dtitem->name] = $dtitem->value;
            }

            $block_numbs = & $dataArr[$block->name]['numbs'];
            foreach($block->numbs as $numb)
            {
                $block_numbs[$numb->name] = $numb->value;
            }

            $dataArr[$block->name]['groups'] = $block->getGroupItemsArray($addshow);

        }

        return $dataArr;
    }

    //Создание структуры блоков лэндинга из конфига
    public static function initBlocks($block_name='')
    {
        if($block_name==''){
            //Создаем поля по структуре
            $qstorage = config('qstorage');
        }else{
            $qstorage = [$block_name=>config('qstorage')[$block_name]];
        }

        foreach($qstorage as $blockname => $blockstruct)
        {
            $newBlock = static::find($blockname);

            if(!$newBlock)
            {

                $newBlock = new static;
                $newBlock->name = $blockname;

                if(array_key_exists('title', $blockstruct))
                {
                    $newBlock->title = $blockstruct['title'];
                }

                if(array_key_exists('stringfields', $blockstruct))
                {
                    foreach($blockstruct['stringfields'] as $fieldname)
                    {
                        $stringfield = Stringfield::firstOrCreate(['block_name'=>$blockname, 'name'=>$fieldname]);
                    }
                }

                if(array_key_exists('textfields', $blockstruct))
                {
                    foreach($blockstruct['textfields'] as $fieldname)
                    {
                        $textfield = Textfield::firstOrCreate(['block_name'=>$blockname, 'name'=>$fieldname]);
                    }
                }

                if(array_key_exists('numbs', $blockstruct))
                {
                    foreach($blockstruct['numbs'] as $fieldname)
                    {
                        $numb = Numb::firstOrCreate(['block_name'=>$blockname, 'name'=>$fieldname]);
                    }
                }

                if(array_key_exists('bools', $blockstruct))
                {
                    foreach($blockstruct['bools'] as $fieldname)
                    {
                        $boolitem = Bool::firstOrCreate(['block_name'=>$blockname, 'name'=>$fieldname]);
                    }
                }

                if(array_key_exists('pdatetimes', $blockstruct))
                {
                    foreach($blockstruct['pdatetimes'] as $fieldname)
                    {
                        $dtitem = Pdatetime::firstOrCreate(['block_name'=>$blockname, 'name'=>$fieldname]);
                    }
                }

                if(array_key_exists('images', $blockstruct))
                {
                    foreach($blockstruct['images'] as $fieldname)
                    {
                        $image = Imageitem::firstOrCreate(['block_name'=>$blockname, 'name'=>$fieldname]);
                    }
                }

                $newBlock->save();

                //Создание элементов групп с фиксированным количеством (только для 1-го уровня),
                //для элементов вложенных групп та же процедура будет проделана при создании элемента - владельца.
                foreach($blockstruct['groups'] as $groupstruct)
                {
                    if(array_key_exists('fixed', $groupstruct) && !array_key_exists('owner', $groupstruct))
                    {
                        $fixed = $groupstruct['fixed'];

                        for ($count=0; $count<$fixed; $count++)
                        {
                            //Создание элемента группы



                        }
                    }
                }

            }
        }

        return 'OK';
    }
}
