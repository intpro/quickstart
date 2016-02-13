<?php

namespace Interpro\QuickStorage\Laravel\Http;

use App\Http\Controllers\Controller;
use Interpro\QuickStorage\Laravel\Model\Group;

class AdminDeleteController extends Controller
{
    public function deleteGroupItem($id){
        try {

            $gritem = Group::findOrFail($id);
            $deleted = [];
            $gritem->deleteGroupItem($deleted);

            return ['status'=>'OK', 'deleted'=>$deleted];

        } catch(\Exception $exception) {
            return ['status'=>('Что-то пошло не так. '.$exception->getMessage())];
        }
    }
}

