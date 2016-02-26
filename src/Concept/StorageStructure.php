<?php

namespace Interpro\QuickStorage\Concept;

interface StorageStructure{

    /**
     * @param string $blockName
     *
     * @return array
     */
    public function getBlockFieldsFlat($blockName);

    /**
     * @param string $blockName
     *
     * @param string $groupName
     *
     * @return array
     */
    public function getGroupFieldsFlat($blockName, $groupName);

    /**
     * @param string $blockName
     *
     * @return int
     */
    public function getMainGroupsDepth($blockName);

    /**
     * @param string $type
     *
     * @return bool
     */
    public function getModelName($type);

    /**
     * @param string $blockName
     *
     * @param string $FieldName
     *
     * @return bool
     */
    public function getModelNameByFieldBlock($blockName, $FieldName);

    /**
     * @param string $blockName
     *
     * @param string $groupName
     *
     * @param string $FieldName
     *
     * @return bool
     */
    public function getModelNameByFieldGroup($blockName, $groupName, $FieldName);

    /**
     * @param string $blockName
     *
     * @return bool
     */
    public function getBlockFieldsModels($blockName);

    /**
     * @param string $blockName
     *
     * @param string $groupName
     *
     * @return bool
     */
    public function getGroupFieldsModels($blockName, $groupName);

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
     * @return bool
     */
    public function groupInBlockExist($blockName, $groupName);

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
