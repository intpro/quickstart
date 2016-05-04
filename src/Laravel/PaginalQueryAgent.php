<?php

namespace Interpro\QuickStorage\Laravel;

use Interpro\QuickStorage\Concept\PaginalQueryAgent as PaginalQueryAgentInterface;
use Interpro\QuickStorage\Concept\QueryAgent as QueryAgentInterface;

class PaginalQueryAgent implements PaginalQueryAgentInterface{

    private $queryAgent;

    //Поиск страницы
    private function searchPage(array $pagesList, $needPage)
    {
        foreach( $pagesList AS $chunk => $pages  ){
            if( in_array($needPage, $pages) ){
                return $chunk;
            }
        }
        return 0;
    }

    /**
     * @param  \Interpro\QuickStorage\Concept\QueryAgent $queryAgent
     * @param  \Interpro\QuickStorage\Concept\Repository $repository
     * @return void
     */
    public function __construct(QueryAgentInterface $queryAgent){
        $this->queryAgent = $queryAgent;
    }

    /**
     * @param string $block_name
     * @param string $name
     * @param array $specs
     * @param int $page
     * @param int $limit
     * @param int $linkppage
     * @return array
     */
    public function getLinks($block_name, $name, $specs, $page, $limit, $linkppage)
    {
        $pagesArr = [];

        //Общее количество записей
        $count = $this->queryAgent->getGroupCount($block_name, $name, $specs);

        // кол-во страниц
        $pages = ceil( $count / $limit );

        // Заполняем массив: ключ - это номер страницы, значение - это смещение для БД.
        // Нумерация здесь нужна с единицы, а смещение с шагом = кол-ву материалов на странице.
        for( $i = 0; $i < $pages; $i++) {
            $pagesArr[$i+1] = $i * $limit;
        }

        // Теперь что бы на странице отображать нужное кол-во ссылок
        // дробим массив на чанки:
        $allPages = array_chunk($pagesArr, $linkppage, true);

        return $this->searchPage($allPages, $page);
    }

    /**
     * Получить коллекцию элементов группы по имени
     *
     * @param string $block_name
     * @param string $name
     * @param array $sorts
     * @param array $specs
     * @param int $page
     * @param int $limit
     * @return \Interpro\QuickStorage\Concept\Collection\GroupCollection
     */
    public function getGroupPage($block_name, $name, $sorts, $specs, $page, $limit)
    {
        $params = ['take'=>$limit, 'skip'=>(($page-1) * $limit)];

        return $this->queryAgent->getGroup($block_name, $name, $sorts, $specs, $params);
    }

    /**
     * Получить коллекцию элементов группы по имени
     *
     * @param string $block_name
     * @param string $name
     * @param array $sorts
     * @param array $specs
     * @param int $page
     * @param int $limit
     * @return \Interpro\QuickStorage\Concept\Collection\GroupCollection
     */
    public function getGroupFlatPage($block_name, $name, $sorts, $specs, $page, $limit)
    {
        $params = ['take'=>$limit, 'skip'=>(($page-1) * $limit)];

        return $this->queryAgent->getGroupFlat($block_name, $name, $sorts, $specs, $params);
    }

}
