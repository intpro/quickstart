<?php

namespace Interpro\QuickStorage\Laravel\Http;

use App\Http\Controllers\Controller;
use Interpro\QuickStorage\Laravel\Model\Block;
use Illuminate\Support\Facades\DB;

class CreateController extends Controller
{
    public function createGroupItem($block, $group, $owner_id)
    {
        try {

            $block = Block::findOrFail($block);

            $item = $block->createGroupItem($group, $owner_id);

            $status = 'OK';

            $complhtml = view('back/blocks/groupitems/'.$block->name.'_'.$group, ['item_'.$group => $item])->render();

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

            Block::initBlocks();
        } catch(\Exception $exception) {

            return ['status'=>('Что-то пошло не так. '.$exception->getMessage())];
        }

        return ['status'=>'OK'];
    }

    public function createInitBlock($block_name)
    {
        try {
            Block::initBlocks($block_name);
        } catch(\Exception $exception) {

            return ['status'=>('Что-то пошло не так. '.$exception->getMessage())];
        }

        return ['status'=>'OK'];
    }
}
