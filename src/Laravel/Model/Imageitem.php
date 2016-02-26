<?php

namespace Interpro\QuickStorage\Laravel\Model;

use Illuminate\Database\Eloquent\Model;
use Interpro\ImageFileLogic\Laravel\Action\DeleteImageAction;
use Interpro\QuickStorage\Laravel\Item\ImageItem as InterproImageItem;


class Imageitem extends Model
{
    protected $table = 'images';
    public $timestamps = false;
    protected static $unguarded = true;

    public function block()
    {
        return $this->belongsTo('Interpro\QuickStorage\Laravel\Model\Block', 'block_name');
    }

    public function group()
    {
        return $this->belongsTo('Interpro\QuickStorage\Laravel\Model\Group', 'group_id');
    }

    protected static function boot(){

        self::deleted(
            function(Imageitem $imageitem)
            {
                if(trim($imageitem->prefix) !== '')
                {
                    //\Interpro\ImageFileLogic\ImageFileLogic::removeForPrefix($imageitem->prefix);
                    //\Interpro\ImageFileLogic\ImageFileLogic::removeForPrefix('mod_'.$imageitem->prefix);

                    $imageItem = new InterproImageItem($imageitem->prefix, ['a'=>1]);
                    $imageItem_mod = new InterproImageItem('mod_'.$imageitem->prefix, []);

                    $delAction = new DeleteImageAction();

                    $delAction->applyFor($imageItem);
                    $delAction->applyFor($imageItem_mod);
                }

            }
        );

        parent::boot();
    }
}
