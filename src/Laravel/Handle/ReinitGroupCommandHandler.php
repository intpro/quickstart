<?php namespace Interpro\QuickStorage\Laravel\Handle;

use Interpro\ImageFileLogic\Concept\ImageLogicAgent;
use Interpro\QuickStorage\Concept\Command\ReinitGroupCommand;
use Interpro\QuickStorage\Laravel\Model\Block;
use Interpro\QuickStorage\Laravel\Model\Bool;
use Interpro\QuickStorage\Laravel\Model\Group;
use Interpro\QuickStorage\Laravel\Model\Imageitem;
use Interpro\QuickStorage\Laravel\Model\Numb;
use Interpro\QuickStorage\Laravel\Model\Pdatetime;
use Interpro\QuickStorage\Laravel\Model\Stringfield;
use Interpro\QuickStorage\Laravel\Model\Textfield;

class ReinitGroupCommandHandler {

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
     * @param  ReinitGroupCommand  $command
     * @return void
     */
    public function handle(ReinitGroupCommand $command)
    {
        $this->reinitGroup($command->group_name);
    }

    public function reinitGroup($group_name)
    {
        $items = Group::where('group_name', '=', $group_name)->get();

        $qstorage = config('qstorage');

        foreach($items as $item)
        {
            $block_name = $item->block_name;
            $group_id = $item->id;

            if(array_key_exists($block_name, $qstorage))
            {
                $groupstruct = $qstorage[$block_name]['groups'][$group_name];

                foreach(['stringfields', 'textfields', 'numbs', 'images', 'bools', 'pdatetimes'] as $typename) {

                    if(array_key_exists($typename, $groupstruct)){

                        foreach($groupstruct[$typename] as $fieldname)
                        {
                            if($typename == 'stringfields'){
                                $field = Stringfield::firstOrCreate(['block_name'=>$block_name, 'name'=>$fieldname, 'group_id'=>$group_id, 'group_name'=>$group_name]);

                            }else if($typename == 'textfields'){
                                $field = Textfield::firstOrCreate(['block_name'=>$block_name, 'name'=>$fieldname, 'group_id'=>$group_id, 'group_name'=>$group_name]);

                            }else if($typename == 'numbs'){
                                $field = Numb::firstOrCreate(['block_name'=>$block_name, 'name'=>$fieldname, 'group_id'=>$group_id, 'group_name'=>$group_name]);

                            }else if($typename == 'bools'){
                                $field = Bool::firstOrCreate(['block_name'=>$block_name, 'name'=>$fieldname, 'group_id'=>$group_id, 'group_name'=>$group_name]);

                            }else if($typename == 'pdatetimes'){
                                $field = Pdatetime::firstOrCreate(['block_name'=>$block_name, 'name'=>$fieldname, 'group_id'=>$group_id, 'group_name'=>$group_name]);

                            }else if($typename == 'images'){

                                $image_name = $group_name.'_'.$fieldname;

                                $image = Imageitem::where('block_name', $block_name)->where('group_name', $group_name)->where('group_id', $group_id)->where('name', $fieldname)->first();

                                if(!$image)
                                {
                                    $image = new Imageitem;
                                    $image->name = $fieldname;
                                    $image->block_name = $block_name;
                                    $image->group_name = $group_name;
                                    $image->group_id = $group_id;

                                    $image->preview_link   = $this->imageLogicAgent->getPlaceholder($image_name, 'preview');
                                    $image->primary_link   = $this->imageLogicAgent->getPlaceholder($image_name, 'primary');
                                    $image->original_link  = $this->imageLogicAgent->getPlaceholder($image_name, 'primary');
                                    $image->secondary_link = $this->imageLogicAgent->getPlaceholder($image_name, 'secondary');
                                    $image->icon_link      = $this->imageLogicAgent->getPlaceholder($image_name, 'icon');

                                    $image->save();
                                }

                            }
                        }
                    }
                }

            }else{
                throw new \Exception('Не нашел в настройке блок группы '.$block_name);
            }

        }
    }
}
