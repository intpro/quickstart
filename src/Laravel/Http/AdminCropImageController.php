<?php

namespace Interpro\QuickStorage\Laravel\Http;

use Interpro\QuickStorage\Concept\Command\Crop\UpdateOneGroupCropCommand;
use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;

class AdminCropImageController extends Controller
{
    public function cropGroupImage()
    {
        if(Request::has('entity') && Request::has('block') && Request::has('group_id'))
        {
            $dataobj = Request::all();

            if($dataobj['entity'] == 'groupitem')
            {
                try {

                    if (array_key_exists('crops', $dataobj)){
                        $this->dispatch(new UpdateOneGroupCropCommand($dataobj['block'], $dataobj['group'], $dataobj['group_id'], $dataobj['crops']));
                    }else{
                        return ['status' => ('Не переданы координаты кропов!')];
                    }

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
