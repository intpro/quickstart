<?php

namespace Interpro\QuickStorage\Concept;

interface PaginalQueryAgent
{
    /**
     * @param string $block_name
     * @param string $name
     * @param array $specs
     * @param int $page
     * @param int $limit
     * @param int $linkppage
     */
    public function getLinks($block_name, $name, $specs, $page, $limit, $linkppage);

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
    public function getGroupPage($block_name, $name, $sorts, $specs, $page, $limit);

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
    public function getGroupFlatPage($block_name, $name, $sorts, $specs, $page, $limit);
}
