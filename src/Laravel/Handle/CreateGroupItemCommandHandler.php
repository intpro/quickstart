<?php namespace Interpro\QuickStorage\Laravel\Handle;

use Illuminate\Support\Facades\Log;
use Interpro\QuickStorage\Concept\Command\CreateGroupItemCommand;
use Interpro\QuickStorage\Concept\StorageStructure;
use Interpro\QuickStorage\Laravel\Model\Block;
use Interpro\QuickStorage\Laravel\Model\Bool;
use Interpro\QuickStorage\Laravel\Model\Imageitem;
use Interpro\QuickStorage\Laravel\Model\Numb;
use Interpro\QuickStorage\Laravel\Model\Pdatetime;
use Interpro\QuickStorage\Laravel\Model\Stringfield;
use Interpro\QuickStorage\Laravel\Model\Textfield;

class CreateGroupItemCommandHandler {

    private $storageStructure;


    /**
     * Create the command handler.
     *
     * @return void
     */
    public function __construct(StorageStructure $storageStructure)
    {
        $this->storageStructure = $storageStructure;
    }

    /**
     * Handle the command.
     *
     * @param  CreateGroupItemCommand  $command
     * @return array
     */
    public function handle(CreateGroupItemCommand $command)
    {
        return $this->createGroupItem($command->block_name, $command->group_name, $command->owner_id);
    }

    public function createGroupItem($block_name, $group_name, $owner_id)
    {
        $block = Block::find($block_name);

        if($block)
        {
            $groupstruct_invert = $this->storageStructure->getGroupsSub9n($block_name);

            $dataArr = ['sorter' => 99,'show' => true];

            $groupstruct = config('qstorage')[$block_name]['groups'][$group_name];

            $newGroupItem = new \Interpro\QuickStorage\Laravel\Model\Group();
            $newGroupItem->block_name = $block_name;
            $newGroupItem->group_name = $group_name;
            $newGroupItem->owner_id   = $owner_id;
            $newGroupItem->save();

            $dataArr['id']         = $newGroupItem->id;
            $dataArr['owner_id']   = $newGroupItem->owner_id;
            $dataArr['block_name'] = $block_name;
            $dataArr['group_name'] = $group_name;

            //По умолчанию предлагаем заполнять слаг этим:
            $dataArr['slug'] = 'gi_'.$newGroupItem->id;

            if(array_key_exists('stringfields', $groupstruct))
            {
                foreach($groupstruct['stringfields'] as $fieldname)
                {
                    $stringfield = Stringfield::create(['block_name'=>$block_name, 'group_name'=>$group_name, 'name'=>$fieldname, 'group_id'=>$newGroupItem->id]);
                    $newGroupItem->stringfields()->save($stringfield);
                    $dataArr[$fieldname]='';
                }
            }

            if(array_key_exists('textfields', $groupstruct))
            {
                foreach($groupstruct['textfields'] as $fieldname)
                {
                    $textfield = Textfield::firstOrNew(['block_name'=>$block_name, 'group_name'=>$group_name, 'name'=>$fieldname, 'group_id'=>$newGroupItem->id]);
                    $newGroupItem->textfields()->save($textfield);
                    $dataArr[$fieldname]='';
                }
            }

            if(array_key_exists('numbs', $groupstruct))
            {
                foreach($groupstruct['numbs'] as $fieldname)
                {
                    $numb = Numb::firstOrNew(['block_name'=>$block_name, 'group_name'=>$group_name, 'name'=>$fieldname, 'group_id'=>$newGroupItem->id]);
                    $newGroupItem->numbs()->save($numb);
                    $dataArr[$fieldname]='';
                }
            }

            if(array_key_exists('bools', $groupstruct))
            {
                foreach($groupstruct['bools'] as $fieldname)
                {
                    $boolitem = Bool::firstOrNew(['block_name'=>$block_name, 'group_name'=>$group_name, 'name'=>$fieldname, 'group_id'=>$newGroupItem->id]);
                    $newGroupItem->bools()->save($boolitem);
                    $dataArr[$fieldname]='';
                }
            }

            if(array_key_exists('pdatetimes', $groupstruct))
            {
                foreach($groupstruct['pdatetimes'] as $fieldname)
                {
                    $dtitem = Pdatetime::firstOrNew(['block_name'=>$block_name, 'group_name'=>$group_name, 'name'=>$fieldname, 'group_id'=>$newGroupItem->id]);
                    $dtitem->value = new \DateTime();
                    $newGroupItem->pdatetimes()->save($dtitem);
                    $dataArr[$fieldname]= $dtitem->value->format('d.m.Y H:i:s');
                }
            }

            if(array_key_exists('images', $groupstruct))
            {
                foreach($groupstruct['images'] as $fieldname)
                {
                    $image = Imageitem::firstOrNew(['block_name'=>$block_name, 'group_name'=>$group_name, 'name'=>$fieldname, 'group_id'=>$newGroupItem->id]);
                    $newGroupItem->images()->save($image);
                    $dataArr[$fieldname]='';
                }
            }
            $newGroupItem->save();

            $dataArr['groups'] = array_fill_keys($groupstruct_invert[$group_name], []);

            return $dataArr;

        }else{

            throw new \Exception('Не нашел блок по имени '.$block_name);
        }
    }


}