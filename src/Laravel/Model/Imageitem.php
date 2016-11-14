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

    public function crops()
    {
        return $this->hasMany('Interpro\QuickStorage\Laravel\Model\Cropitem', 'image_id');
    }


    protected static function boot(){

        self::deleted(
            function(Imageitem $imageitem)
            {
                //Удаляем подчиненные кропы
                $crops = Cropitem::where('image_id', '=', $imageitem->id)->get();
                foreach($crops as $field){
                    $field->delete();
                }

                //Удаление файлов картинок (вместе с кропами)
                if(trim($imageitem->prefix) !== '')
                {
                    //\Interpro\ImageFileLogic\ImageFileLogic::removeForPrefix($imageitem->prefix);
                    //\Interpro\ImageFileLogic\ImageFileLogic::removeForPrefix('mod_'.$imageitem->prefix);

                    //2 строки костыля, в базе хранение с id, в item'ax переделано без id,
                    //здесь единственное место где команда создается из itema
                    $suffix_pos = strripos($imageitem->prefix, '_');
                    $config_name = substr($imageitem->prefix, 0, $suffix_pos);
                    //--------------------------------------------------------

                    $imageItem = new InterproImageItem($config_name, $imageitem->group_id, ['a'=>1]);
                    $imageItem_mod = new InterproImageItem('mod_'.$imageitem->prefix, $imageitem->group_id, []);

                    $delAction = new DeleteImageAction();

                    $delAction->applyFor($imageItem);
                    $delAction->applyFor($imageItem_mod);
                }

            }
        );

        parent::boot();
    }
}
