<?php

namespace Interpro\QuickStorage\Laravel\Http;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Interpro\ImageFileLogic\Concept\Exception\ImageFileSystemException;
use Interpro\ImageFileLogic\Concept\Report;
use Interpro\QuickStorage\Concept\Command\CreateGroupItemCommand;
use Interpro\QuickStorage\Concept\Command\Image\UpdateOneGroupImageCommand;
use Interpro\QuickStorage\Concept\Command\InitAllBlockCommand;
use Interpro\QuickStorage\Concept\Command\InitOneBlockCommand;
use Illuminate\Support\Facades\DB;
use Interpro\QuickStorage\Concept\Command\UpdateGroupItemCommand;
use Interpro\QuickStorage\Laravel\Item\GroupItem;
use Illuminate\Http\Request;

class AdminCreateController extends Controller
{

    public function __construct()
    {

    }

    public function createGroupItem($block, $group, $owner_id)
    {
        try {

            $dataArr = $this->dispatch(new CreateGroupItemCommand($block, $group, $owner_id));

            $group_item = new GroupItem($dataArr);

            $complhtml = view('back/blocks/groupitems/'.$block.'/'.$group, ['item_'.$group => $group_item])->render();


            $status = 'OK';

            return compact('status', 'complhtml');

        } catch(\Exception $exception) {

            return ['status'=>('Что-то пошло не так. '.$exception->getMessage())];
        }
    }


    public function createGroupImageItem(Request $request, Report $report){

        $validator = Validator::make(
            $request->all(),
            [
                'block_name' => 'required',
                'group_name' => 'required',
                'image_name' => 'required'
            ]
        );

        if($validator->fails()){
            return ['success'=>false, 'message'=>$validator->errors()->setFormat(':message<br>')->all()];
        }

        try{

            $block_name = $request->input('block_name');
            $group_name = $request->input('group_name');
            $image_name = $request->input('image_name');

            $dataArr = $this->dispatch(new CreateGroupItemCommand($block_name, $group_name, 0));

            $this->dispatch(
                new UpdateOneGroupImageCommand(
                    $block_name,
                    $group_name,
                    $image_name,
                    $dataArr['id'],
                    $request->file('imagefile')));

            $resp_name = $group_name.'_'.$image_name.'_'.$dataArr['id'];

            $sizes = $report->getImageReport($resp_name);

            $dataArr['images'] = [$image_name => ['prefix'=>$resp_name,'original_link' => $sizes['original']]];

            $this->dispatch(new UpdateGroupItemCommand($dataArr['id'], $dataArr));

            $group_item = new GroupItem($dataArr);

            $complhtml = view('back/blocks/groupitems/'.$block_name.'/'.$group_name, ['item_'.$group_name => $group_item])->render();

            $success = true;

            $file = '/images/'.$sizes['original'];

            return compact('success', 'file', 'complhtml');

        }catch (\Exception $exception){

            return ['success'=>false, 'message'=>('Что-то пошло не так. '.$exception->getMessage())];
        }
    }


    public function createInit()
    {
        try {

            //Очистим всё, если там что-то есть
            DB::table('blocks')->delete();
            DB::table('groups')->delete();
            DB::table('stringfields')->delete();
            DB::table('textfields')->delete();
            DB::table('bools')->delete();
            DB::table('pdatetimes')->delete();
            DB::table('numbs')->delete();
            DB::table('images')->delete();
            //Эту хрень выше потом обернуть во что-нибудь красивое

            $this->dispatch(
                new InitAllBlockCommand()
            );

        } catch(\Exception $exception) {

            return ['status'=>('Что-то пошло не так. '.$exception->getMessage())];
        }

        return ['status'=>'OK'];
    }

    public function createInitBlock($block_name)
    {
        try {

            $this->dispatch(
                new InitOneBlockCommand($block_name)
            );

        } catch(\Exception $exception) {

            return ['status'=>('Что-то пошло не так. '.$exception->getMessage())];
        }

        return ['status'=>'OK'];
    }
}
