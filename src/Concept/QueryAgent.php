<?php

namespace Interpro\QuickStorage\Concept;

interface QueryAgent{

    /**
     * Получить элемент блока с полями
     *
     * @param string $name
     * @param array $sorts
     * @param array $specs
     * @return \Interpro\QuickStorage\Concept\Item\BlockItem
     */
    public function getBlock($name, $sorts, $specs);

    /**
     * Получить коллекцию элементов группы по имени
     *
     * @param string $block_name
     * @param string $name
     * @param array $sorts
     * @param array $specs
     * @return \Interpro\QuickStorage\Concept\Collection\GroupCollection
     */
    public function getGroup($block_name, $name, $sorts, $specs);

    /**
     * Получить коллекцию элементов группы по имени
     *
     * @param string $block_name
     * @param string $name
     * @param int $owner_id
     * @return \Interpro\QuickStorage\Concept\Item\GroupItem
     */
    public function getGroupItem($block_name, $name, $owner_id);

}
