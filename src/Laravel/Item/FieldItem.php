<?php

namespace Interpro\QuickStorage\Laravel\Item;

use Interpro\QuickStorage\Concept\Item\FieldItem as FieldItemInterface;
use Interpro\QuickStorage\Laravel\Model\Block as LaravelModel;

class FieldItem implements FieldItemInterface
{
    private $model;

    //
    public function __construct(LaravelModel $model)
    {
        $this->model = $model;
    }

}

