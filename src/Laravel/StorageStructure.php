<?php

namespace Interpro\QuickStorage\Laravel;

use Interpro\QuickStorage\Concept\StorageStructure as StorageStructureInterface;

class StorageStructure implements StorageStructureInterface
{

    /**
     * @param string $blockName
     *
     * @return bool
     */
    private function getBlockFieldsFlat($blockName)
    {
        $struct = $this->getBlockConfig($blockName);

        $fields_flat = [];

        $types = ['stringfields', 'textfields', 'numbs', 'bools', 'pdatetimes', 'images'];
        foreach($types as $type)
        {
            if(array_key_exists($type, $struct))
            {
                foreach($struct[$type] as $fieldname)
                {
                    $fields_flat[] = $fieldname;
                }
            }
        }

        return $fields_flat;
    }

    /**
     * @param string $blockName
     *
     * @param string $groupName
     *
     * @return bool
     */
    private function getGroupFieldsFlat($blockName, $groupName)
    {
        $struct = $this->getGroupConfig($blockName, $groupName);

        $fields_flat = [];

        $types = ['stringfields', 'textfields', 'numbs', 'bools', 'pdatetimes', 'images'];
        foreach($types as $type)
        {
            if(array_key_exists($type, $struct))
            {
                foreach($struct[$type] as $fieldname)
                {
                    $fields_flat[] = $fieldname;
                }
            }
        }

        return $fields_flat;
    }

    /**
     * @param string $blockName
     *
     * @return int
     */
    public function getMainGroupsDepth($blockName)
    {
        $depth = 0;
        $groupstruct_0 = $this->getGroupsFlatConfig($blockName);

        foreach ($groupstruct_0 as $groupname => $groupstruct)
        {
            if(!array_key_exists('owner', $groupstruct))
            {
                $dataArr[$groupname] = [];

                $currdepth = $this->getGroupsDepth($groupstruct_0, $groupname, 0);

                $depth = $currdepth > $depth ? $currdepth : $depth;
            }
        }

        return $depth;
    }

    private function getGroupsDepth(&$groupstruct_0, $groupname_x, $depth)
    {

        $depth++;
        $maxdepth = $depth;

        foreach ($groupstruct_0 as $groupname => $groupstruct)
        {
            if(array_key_exists('owner', $groupstruct) and $groupstruct['owner'] == $groupname_x)
            {
                $currdepth = $this->getGroupsDepth($groupstruct_0, $groupname, $depth);

                $maxdepth = $currdepth > $maxdepth ? $currdepth : $maxdepth;
            }
        }

        return $maxdepth;
    }

    /**
     * @param string $blockName
     *
     * @return array
     */
    public function getGroupsSub9n($blockName)
    {

        $groups_conf = $this->getGroupsFlatConfig($blockName);
        $groupstruct_invert = [];

        foreach ($groups_conf as $groupname => $_conf)
        {
            if(!array_key_exists($groupname, $groupstruct_invert))
            {
                $groupstruct_invert[$groupname] = [];
            }

            if(array_key_exists('owner', $_conf))
            {
                if(!array_key_exists($_conf['owner'], $groupstruct_invert))
                {
                    $groupstruct_invert[$_conf['owner']] = [];
                }

                $groupstruct_invert[$_conf['owner']][] = $groupname;
            }
        }

        return $groupstruct_invert;
    }

    /**
     * @param string $blockName
     *
     * @return array
     */
    private function getGroups0level($blockName)
    {

        $groups_conf = $this->getGroupsFlatConfig($blockName);
        $groupstruct_0l = [];

        foreach ($groups_conf as $groupname => $_conf)
        {
            if(!array_key_exists('owner', $_conf))
            {
                $groupstruct_0l[$groupname] = [];
            }
        }

        return $groupstruct_0l;
    }

    /**
     * @param string $blockName
     *
     * @return array
     */
    public function getGroupsFlatConfig($blockName)
    {
        return config('ersatzstorage.'.$blockName)['groups'];
    }

    /**
     * @param string $blockName
     *
     * @return array
     */
    public function getBlockConfig($blockName)
    {
        return config('ersatzstorage.'.$blockName);
    }

    /**
     * @param string $blockName
     *
     * @param string $groupName
     *
     * @return array
     */
    public function getGroupConfig($blockName, $groupName)
    {
        return config('ersatzstorage.'.$blockName)['groups'][$groupName];
    }

    /**
     * @param string $blockName
     *
     * @param string $groupName
     *
     * @return bool
     */
    public function groupExist($blockName, $groupName)
    {
        $groups_conf = $this->getGroups0level($blockName);

        return array_key_exists($groupName, $groups_conf);
    }

    /**
     * @param string $blockName
     *
     * @param string $groupName
     *
     * @param string $subGroupName
     *
     * @return bool
     */
    public function subGroupExist($blockName, $groupName, $subGroupName)
    {
        $groups_conf = $this->getGroupsSub9n($blockName);

        if (array_key_exists($groupName, $groups_conf))
        {
            return in_array($subGroupName, $groups_conf[$groupName]);

        }else{

            return false;
        }
    }

    /**
     * @param string $blockName
     *
     * @param string $fieldName
     *
     * @return bool
     */
    public function blockFieldExist($blockName, $fieldName)
    {
        $fields = $this->getBlockFieldsFlat($blockName);

        return in_array($fieldName, $fields);
    }

    /**
     * @param string $blockName
     *
     * @param string $groupName
     *
     * @param string $fieldName
     *
     * @return bool
     */
    public function groupFieldExist($blockName, $groupName, $fieldName)
    {
        $groups_conf = $this->getGroupsFlatConfig($blockName);

        if(!array_key_exists($groupName, $groups_conf))
        {
            return false;
        }else{
            $struct = $this->getGroupFieldsFlat($blockName, $groupName);

            return in_array($fieldName, $struct);
        }
    }



}



































