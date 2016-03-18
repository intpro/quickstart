<?php

namespace Interpro\QuickStorage\Concept;

interface QueryAgent{

    /**
     * Получить элемент блока с полями
     *
     * @param string $name
     * @param array $sorts
     * @param array $specs
     * @param array $params
     * @return \Interpro\QuickStorage\Concept\Item\BlockItem
     */
    public function getBlock($name, $sorts, $specs, $params=[]);

    /**
     * Получить коллекцию элементов группы по имени
     *
     * @param string $block_name
     * @param string $name
     * @param array $sorts
     * @param array $specs
     * @param array $params
     * @return \Interpro\QuickStorage\Concept\Collection\GroupCollection
     */
    public function getGroup($block_name, $name, $sorts, $specs, $params=[]);

    /**
     * Получить коллекцию элементов группы по имени
     *
     * @param string $block_name
     * @param string $name
     * @param array $sorts
     * @param array $specs
     * @param array $params
     * @return \Interpro\QuickStorage\Concept\Collection\GroupCollection
     */
    public function getGroupFlat($block_name, $name, $sorts, $specs, $params=[]);

    /**
     * Получить коллекцию элементов группы по имени
     *
     * @param string $block_name
     * @param string $group_name
     * @param int $group_id
     * @return \Interpro\QuickStorage\Concept\Item\GroupItem
     */
    public function getGroupItem($block_name, $group_name, $group_id);

    /**
     * Получить коллекцию элементов группы по имени
     *
     * @param string $block_name
     * @param string $group_name
     * @param string $slug
     * @return \Interpro\QuickStorage\Concept\Item\GroupItem
     */
    public function getGroupItemBySlug($block_name, $group_name, $slug);

}
