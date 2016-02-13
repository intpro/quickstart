<?php

namespace Interpro\QuickStorage\Concept;

interface StorageStructure{

    /**
     * @param string $blockName
     *
     * @return int
     */
    public function getMainGroupsDepth($blockName);

    /**
     * @param string $blockName
     *
     * @return array
     */
    public function getGroupsSub9n($blockName);

    /**
     * @param string $blockName
     *
     * @return array
     */
    public function getGroupsFlatConfig($blockName);

    /**
     * @param string $blockName
     *
     * @param string $groupName
     *
     * @return bool
     */
    public function groupExist($blockName, $groupName);

    /**
     * @param string $blockName
     *
     * @param string $groupName
     *
     * @param string $SubGroupName
     *
     * @return bool
     */
    public function subGroupExist($Blockname, $groupName, $SubGroupName);

    /**
     * @param string $blockName
     *
     * @param string $fieldName
     *
     * @return bool
     */
    public function blockFieldExist($blockName, $fieldName);

    /**
     * @param string $blockName
     *
     * @param string $groupName
     *
     * @param string $fieldName
     *
     * @return bool
     */
    public function groupFieldExist($blockName, $groupName, $fieldName);

}
