<?php

namespace Interpro\QuickStorage\Laravel\Http;

use App\Http\Controllers\Controller;
use Interpro\QuickStorage\Concept\Command\DeleteGroupItemCommand;

class AdminDeleteController extends Controller
{
    public function deleteGroupItem($id){
        try {

            $deleted = $this->dispatch(new DeleteGroupItemCommand($id));

            return ['status' => 'OK', 'deleted' => $deleted];

        } catch(\Exception $exception) {
            return ['status' => ('Что-то пошло не так. '.$exception->getMessage())];
        }
    }
}

