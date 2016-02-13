<?php

namespace Interpro\QuickStorage\Laravel;

use Interpro\QuickStorage\Concept\Exception\BlockNotFoundException;
use Interpro\QuickStorage\Concept\Repository as RepositoryInterface;
use Interpro\QuickStorage\Concept\StorageStructure;
use Interpro\QuickStorage\Laravel\Item\BlockItem;
use Interpro\QuickStorage\Laravel\Model\Block;
use Interpro\QuickStorage\Laravel\Model\Group;

class EloquentRepository implements RepositoryInterface
{
    private $storageStruct;


    public function __construct(StorageStructure $storageStruct)
    {
        $this->storageStruct = $storageStruct;
    }

    //Выборка групп 0 уровня (самого верхнего)
    public function getBlock($block_name, $addshow = false)
    {
        //1- ПОЛЯ БЛОКА
        $blocks = Block::where('name', '=', $block_name)->with([
            'stringfields'=>function($query){
                    $query->where('group_id','=',0);
                },
            'textfields'=>function($query){
                    $query->where('group_id','=',0);
                },
            'numbs'=>function($query){
                    $query->where('group_id','=',0);
                },
            'bools'=>function($query){
                    $query->where('group_id','=',0);
                },
            'pdatetimes'=>function($query){
                    $query->where('group_id','=',0);
                },
            'images'=>function($query){
                    $query->where('group_id','=',0);
                }
        ])->get();

        if(!$blocks->isEmpty())
        {
            $block = $blocks->first();

            $depth = $this->storageStruct->getMainGroupsDepth($block_name);

            //Построение, выполнение запроса
            $query_fields = ['stringfields', 'textfields', 'numbs', 'bools', 'pdatetimes', 'images'];

            if($depth>1)
            {
                $groupsFunc = static::addGroupsQueryFunc($addshow, $depth);
                $query_fields['groups'] = $groupsFunc;
            } else {
                $query_fields[] = 'groups';
            }

            //2- ВСЕ ЭЛЕМЕНТЫ ВСЕХ ПОДЧИНЕННЫХ ГРУПП
            $query = Group::where('block_name','=',$block_name)->with($query_fields)
                ->where('owner_id', '=', 0);

            if($addshow)
            {
                $query->where('show', '=', true);
            }

            $groups = $query->get();

            return new BlockItem($this->storageStruct, $block, $groups, $depth);
        }else{

            throw new BlockNotFoundException('Блок '.$block_name.' не найден.');
        }
    }

    private static function addGroupsQueryFunc($addshow, $depth, $currdepth = 1)
    {
        $currdepth++;

        if ($currdepth > $depth) {
            return function($query){};
        } else {
            $func = static::addGroupsQueryFunc($addshow, $depth, $currdepth);
            return function($query) use ($func, $addshow)
            {
                $query->with([
                    'groups' => $func
                ]);

                if($addshow)
                {
                    $query->where('show', '=', true);
                }
            };
        }
    }

}
