<?php

namespace Interpro\QuickStorage\Laravel\Param;
use Interpro\QuickStorage\Concept\Exception\WrongParamException;
use Interpro\QuickStorage\Concept\Param\GroupParam as GroupParamInterface;

class GroupParam implements GroupParamInterface
{
    private $params;
    private $permitted_params;

    public function __construct()
    {
        //В перспективе добавить возможность насыпать из сессии
        $this->params = [];

        //разрешенные параметры, если запрос к параметру не из этого списка, выбр. исключение
        $this->permitted_params = ['take', 'skip'];
    }

    public function reset($group_name)
    {
        $this->params[$group_name] = ['take'=>0, 'skip'=>0];
    }

    private function createIfNotExist($group_name)
    {
        if(!array_key_exists($group_name, $this->params))
        {
            $this->params[$group_name] = ['take'=>0, 'skip'=>0];
        }
    }

    private function checkParam($param_name)
    {
        if(!in_array($param_name, $this->permitted_params))
        {
            throw new WrongParamException('Обращение к неразрешенному параметру - '.$param_name);
        }
    }

    /**
     * @param string $group_name
     * @param string $param_name
     * @param $value
     *
     * @return void
     */
    public function set($group_name, $param_name, $value)
    {
        $this->createIfNotExist($group_name);

        $this->checkParam($param_name);

        $this->params[$group_name][$param_name] = $value;
    }

    /**
     * @param string $group_name
     * @param string $param_name
     *
     * @return mixed
     */
    public function get($group_name, $param_name)
    {
        $this->createIfNotExist($group_name);

        $this->checkParam($param_name);

        return $this->params[$group_name][$param_name];
    }

    /**
     * @param string $group_name
     * @param $group_q
     *
     * @return void
     */
    public function apply($group_name, $group_q)
    {
        $portion = $this->get($group_name, 'take');
        $start = $this->get($group_name, 'skip');

        if($portion != 0){
            $group_q->take($portion);
        }
        if($start != 0){
            $group_q->skip($start);
        }
    }

}