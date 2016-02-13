<?php

namespace Interpro\QuickStorage\Laravel\Http;

use Interpro\QuickStorage\Laravel\Model\Block;
use Interpro\QuickStorage\Laravel\Model\Group;
use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;

class AdminUpdateController extends Controller
{
    public function updateBlock()
    {

        if(Request::has('entity'))
        {
            $dataobj = Request::all();

            if($dataobj['entity'] == 'block')
            {
                try {

                    $block = Block::firstOrCreate(['name'=>$dataobj['block']]);

                    $result = $block->saveBlock($dataobj);

                    return ['status'=>$result];

                } catch(\Exception $exception) {
                    return ['status'=>('Что-то пошло не так. '.$exception->getMessage())];
                }
            } else {
                return ['status'=>'Имя сохраняемой сущности не равно block ('.$dataobj['entity'].').'];
            }

        } else {

            return ['status'=>'Не хватает параметров для сохранения.'];

        }

    }

    public function updateGroupItem()
    {
        if(Request::has('entity') && Request::has('block') && Request::has('id'))
        {
            $dataobj = Request::all();

            if($dataobj['entity'] == 'groupitem')
            {
                try {
                    $groupitem = Group::findOrFail($dataobj['id']);

                    $result = $groupitem->saveGroupItem($dataobj);

                    return ['status'=>$result];

                } catch(\Exception $exception) {
                    return ['status'=>('Что-то пошло не так. '.$exception->getMessage())];
                }
            } else {
                return ['status'=>'Имя сохраняемой сущности не равно group ('.$dataobj['entity'].').'];
            }

        } else {

            return ['status'=>'Не хватает параметров для сохранения.'];

        }
    }
}
