<?php

namespace Interpro\QuickStorage\Laravel\Http;

use Interpro\QuickStorage\Concept\Command\Crop\UpdateBlockCropCommand;
use Interpro\QuickStorage\Concept\Command\Crop\UpdateOneGroupCropCommand;
use Interpro\QuickStorage\Concept\Command\UpdateBlockCommand;
use Interpro\QuickStorage\Concept\Command\UpdateGroupItemCommand;
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

                    $this->dispatch(new UpdateBlockCommand($dataobj['block'], $dataobj));

                    $crops = array_key_exists('crops', $dataobj) ? $dataobj['crops'] : [];
                    $this->dispatch(new UpdateBlockCropCommand($dataobj['block'], $crops));

                    return ['status' => 'OK'];

                } catch(\Exception $exception) {
                    return ['status' => ('Что-то пошло не так. '.$exception->getMessage())];
                }
            } else {
                return ['status' => 'Имя сохраняемой сущности не равно block ('.$dataobj['entity'].').'];
            }

        } else {

            return ['status' => 'Не хватает параметров для сохранения.'];
        }
    }

    public function updateGroupItem()
    {
        if(Request::has('entity') && Request::has('block') && Request::has('group_id'))
        {
            $dataobj = Request::all();

            if($dataobj['entity'] == 'groupitem')
            {
                try {

                    $this->dispatch(new UpdateGroupItemCommand($dataobj['group_id'], $dataobj));

                    $crops = array_key_exists('crops', $dataobj) ? $dataobj['crops'] : [];
                    $this->dispatch(new UpdateOneGroupCropCommand($dataobj['block'], $dataobj['group'], $dataobj['group_id'], $crops));

                    return ['status' => 'OK'];

                } catch(\Exception $exception) {
                    return ['status' => ('Что-то пошло не так. '.$exception->getMessage())];
                }
            } else {
                return ['status' => 'Имя сохраняемой сущности не равно group ('.$dataobj['entity'].').'];
            }

        } else {

            return ['status' => 'Не хватает параметров для сохранения.'];
        }
    }
}


