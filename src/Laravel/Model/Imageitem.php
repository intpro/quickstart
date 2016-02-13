<?php

namespace Interpro\QuickStorage\Laravel\Model;

use Illuminate\Database\Eloquent\Model;

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
                    \Prehistorical\ImageFileLogic\ImageFileLogic::removeForPrefix($imageitem->prefix);
                    \Prehistorical\ImageFileLogic\ImageFileLogic::removeForPrefix('mod_'.$imageitem->prefix);
                }

            }
        );

        parent::boot();
    }
}
