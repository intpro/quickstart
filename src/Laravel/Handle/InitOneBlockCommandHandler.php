<?php namespace Interpro\QuickStorage\Laravel\Handle;


use Illuminate\Support\Facades\Log;
use Interpro\QuickStorage\Concept\Command\Command;
use Interpro\QuickStorage\Laravel\Model\Block;
use Interpro\QuickStorage\Laravel\Model\Bool;
use Interpro\QuickStorage\Laravel\Model\Imageitem;
use Interpro\QuickStorage\Laravel\Model\Numb;
use Interpro\QuickStorage\Laravel\Model\Pdatetime;
use Interpro\QuickStorage\Laravel\Model\Stringfield;
use Interpro\QuickStorage\Laravel\Model\Textfield;

class InitOneBlockCommandHandler {

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
     * @param  Command  $command
     * @return void
     */
    public function handle(Command $command)
    {
        $this->initBlocks($command->block_name);
    }

    //Создание структуры блоков лэндинга из конфига
    public function initBlocks($block_name='')
    {
        if($block_name==''){
            //Создаем поля по структуре
            $qstorage = config('qstorage');
        }else{
            $qstorage = [$block_name=>config('qstorage')[$block_name]];
        }

        foreach($qstorage as $blockname => $blockstruct)
        {
            $newBlock = Block::find($blockname);

            if(!$newBlock)
            {

                $newBlock = new Block();
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

//                //Создание элементов групп с фиксированным количеством (только для 1-го уровня),
//                //для элементов вложенных групп та же процедура будет проделана при создании элемента - владельца.
//                foreach($blockstruct['groups'] as $groupstruct)
//                {
//                    if(array_key_exists('fixed', $groupstruct) && !array_key_exists('owner', $groupstruct))
//                    {
//                        $fixed = $groupstruct['fixed'];
//
//                        for ($count=0; $count<$fixed; $count++)
//                        {
//                            //Создание элемента группы
//
//
//
//                        }
//                    }
//                }

            }
        }
    }

}