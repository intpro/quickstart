<?php namespace Interpro\QuickStorage\Laravel\Handle;


use Illuminate\Support\Facades\Log;
use Interpro\ImageFileLogic\Concept\ImageLogicAgent;
use Interpro\QuickStorage\Concept\Command\Command;
use Interpro\QuickStorage\Concept\Exception\WrongBlockNameException;
use Interpro\QuickStorage\Laravel\Model\Block;
use Interpro\QuickStorage\Laravel\Model\Bool;
use Interpro\QuickStorage\Laravel\Model\Imageitem;
use Interpro\QuickStorage\Laravel\Model\Numb;
use Interpro\QuickStorage\Laravel\Model\Pdatetime;
use Interpro\QuickStorage\Laravel\Model\Stringfield;
use Interpro\QuickStorage\Laravel\Model\Textfield;

class ReinitOneBlockCommandHandler {

    private $imageLogicAgent;

    /**
     * Create the command handler.
     *
     * @return void
     */
    public function __construct(ImageLogicAgent $imageLogicAgent)
    {
        $this->imageLogicAgent = $imageLogicAgent;
    }

    /**
     * Handle the command.
     *
     * @param  Command  $command
     * @return void
     */
    public function handle(Command $command)
    {
        $this->reinitBlock($command->block_name);
    }

    //Создание структуры блоков лэндинга из конфига
    public function reinitBlock($block_name='')
    {
        if(!array_key_exists($block_name, config('qstorage')))
        {
            throw new WrongBlockNameException('В настройке нет блока с именем '.$block_name);
        }


        $blockstruct = config('qstorage')[$block_name];

        $newBlock = Block::find($block_name);

        if(!$newBlock)
        {
            $newBlock = new Block();
        }

        $newBlock->name = $block_name;

        if(array_key_exists('title', $blockstruct))
        {
            $newBlock->title = $blockstruct['title'];
        }

        if(array_key_exists('stringfields', $blockstruct))
        {
            foreach($blockstruct['stringfields'] as $fieldname)
            {
                $stringfield = Stringfield::firstOrCreate(['block_name'=>$block_name, 'name'=>$fieldname]);
            }
        }

        if(array_key_exists('textfields', $blockstruct))
        {
            foreach($blockstruct['textfields'] as $fieldname)
            {
                $textfield = Textfield::firstOrCreate(['block_name'=>$block_name, 'name'=>$fieldname]);
            }
        }

        if(array_key_exists('numbs', $blockstruct))
        {
            foreach($blockstruct['numbs'] as $fieldname)
            {
                $numb = Numb::firstOrCreate(['block_name'=>$block_name, 'name'=>$fieldname]);
            }
        }

        if(array_key_exists('bools', $blockstruct))
        {
            foreach($blockstruct['bools'] as $fieldname)
            {
                $boolitem = Bool::firstOrCreate(['block_name'=>$block_name, 'name'=>$fieldname]);
            }
        }

        if(array_key_exists('pdatetimes', $blockstruct))
        {
            foreach($blockstruct['pdatetimes'] as $fieldname)
            {
                $dtitem = Pdatetime::firstOrCreate(['block_name'=>$block_name, 'name'=>$fieldname]);
            }
        }

        if(array_key_exists('images', $blockstruct))
        {
            foreach($blockstruct['images'] as $fieldname)
            {
                $image_name = $block_name.'_'.$fieldname;

                $image = Imageitem::where('block_name', $block_name)->where('group_id', 0)->where('name', $fieldname)->first();

                if(!$image)
                {
                    $image = new Imageitem;
                    $image->name = $fieldname;
                    $image->block_name = $block_name;
                    $image->group_id = 0;

                    $image->preview_link   = $this->imageLogicAgent->getPlaceholder($image_name, 'preview');
                    $image->primary_link   = $this->imageLogicAgent->getPlaceholder($image_name, 'primary');
                    $image->original_link  = $this->imageLogicAgent->getPlaceholder($image_name, 'primary');
                    $image->secondary_link = $this->imageLogicAgent->getPlaceholder($image_name, 'secondary');
                    $image->icon_link      = $this->imageLogicAgent->getPlaceholder($image_name, 'icon');

                    $image->save();
                }
            }
        }

        $newBlock->save();

    }

}