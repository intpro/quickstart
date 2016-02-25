<?php

namespace Interpro\QuickStorage\Laravel\Http;

use App\Http\Controllers\Controller;
use Interpro\QuickStorage\Concept\Command\CreateGroupItemCommand;
use Interpro\QuickStorage\Concept\Command\InitAllBlockCommand;
use Interpro\QuickStorage\Concept\Command\InitOneBlockCommand;
use Illuminate\Support\Facades\DB;
use Interpro\QuickStorage\Laravel\Item\GroupItem;

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

            $slug = $group_item->slug_field;

            $complhtml = view('back/blocks/groupitems/'.$group, ['item_'.$group => $group_item])->render();


            $status = 'OK';

            return compact('status', 'complhtml');

        } catch(\Exception $exception) {

            return ['status'=>('Что-то пошло не так. '.$exception->getMessage())];
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
