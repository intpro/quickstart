<?php

namespace Interpro\QuickStorage\Laravel\Model;

use Illuminate\Database\Eloquent\Model;


class Cropitem extends Model
{
    protected $table = 'crops';
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

    public function image()
    {
        return $this->belongsTo('Interpro\QuickStorage\Laravel\Model\Imageitem', 'image_id');
    }

}
