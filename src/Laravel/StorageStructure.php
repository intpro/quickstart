<?php

namespace Interpro\QuickStorage\Laravel;

use Interpro\QuickStorage\Concept\StorageStructure as StorageStructureInterface;

class StorageStructure implements StorageStructureInterface
{

    private $types;

    private function getTypes()
    {
        if (!isset($this->types))
        {
            $this->types = ['stringfields' => 'Interpro\QuickStorage\Laravel\Model\Stringfield',
                'textfields' => 'Interpro\QuickStorage\Laravel\Model\Textfield',
                'numbs' => 'Interpro\QuickStorage\Laravel\Model\Numb',
                'bools' => 'Interpro\QuickStorage\Laravel\Model\Bool',
                'pdatetimes' => 'Interpro\QuickStorage\Laravel\Model\Pdatetime',
                'images' => 'Interpro\QuickStorage\Laravel\Model\Imageitem'];
        }

        return $this->types;
    }

    /**
     * @param string $blockName
     *
     * @return array
     */
    public function getBlockImagesFlat($blockName)
    {
        $struct = $this->getBlockConfig($blockName);

        $images_flat = [];

        if(array_key_exists('images', $struct))
        {
            foreach($struct['images'] as $image_name)
            {
                $images_flat[] = $image_name;
            }
        }

        return $images_flat;
    }

    /**
     * @param string $blockName
     *
     * @param string $groupName
     *
     * @return array
     */
    public function getGroupImagesFlat($blockName, $groupName)
    {
        $struct = $this->getGroupConfig($blockName, $groupName);

        $images_flat = [];

        if(array_key_exists('images', $struct))
        {
            foreach($struct['images'] as $image_name)
            {
                $images_flat[] = $image_name;
            }
        }

        return $images_flat;
    }

    /**
     * @param string $blockName
     *
     * @return array
     */
    public function getBlockFieldsFlat($blockName)
    {
        $struct = $this->getBlockConfig($blockName);

        $fields_flat = ['name', 'title', 'show'];

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
     * @return array
     */
    public function getGroupFieldsFlat($blockName, $groupName)
    {
        $struct = $this->getGroupConfig($blockName, $groupName);

        $fields_flat = ['id', 'owner_id', 'block_name', 'group_owner_name', 'group_name', 'title', 'slug', 'sorter', 'show'];

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
     * @param string $type
     *
     * @return bool
     */
    public function getModelName($type)
    {
        return $this->getTypes()[$type];
    }

    public function getModelNameByFieldBlock($blockName, $fieldName)
    {
        return $this->getBlockFieldsModels($blockName)[$fieldName];
    }

    public function getModelNameByFieldGroup($blockName, $groupName, $fieldName)
    {
        return $this->getGroupFieldsModels($blockName, $groupName)[$fieldName];
    }

    /**
     * @param string $blockName
     *
     * @param string $groupName
     *
     * @return bool
     */
    public function getGroupFieldsModels($blockName, $groupName)
    {
        $struct = $this->getGroupConfig($blockName, $groupName);

        $fields_flat = [];

        $types = $this->getTypes();

        foreach($types as $type=>$model)
        {
            if(array_key_exists($type, $struct))
            {
                foreach($struct[$type] as $fieldname)
                {
                    $fields_flat[$fieldname] = $model;
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
    public function getBlockFieldsModels($blockName)
    {
        $struct = $this->getBlockConfig($blockName);

        $fields_flat = [];

        $types = $this->getTypes();

        foreach($types as $type=>$model)
        {
            if(array_key_exists($type, $struct))
            {
                foreach($struct[$type] as $fieldname)
                {
                    $fields_flat[$fieldname] = $model;
                }
            }
        }

        return $fields_flat;
    }

    /**
     * @param string $blockName
     *
     * @return array
     */
    public function getGroupsSub9n($blockName) //getGroupsStruct
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
        return config('qstorage.'.$blockName)['groups'];
    }

    /**
     * @param string $blockName
     *
     * @return bool
     */
    public function blockExist($blockName)
    {
        return !!config('qstorage.'.$blockName);
    }

    /**
     * @param string $blockName
     *
     * @return array
     */
    public function getBlockConfig($blockName)
    {
        return config('qstorage.'.$blockName);
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
        return config('qstorage.'.$blockName)['groups'][$groupName];
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
     * @return bool
     */
    public function groupInBlockExist($blockName, $groupName)
    {
        $groups_conf = $this->getGroupsFlatConfig($blockName);

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

    /**
     * @param string $blockName
     *
     * @param string $fieldName
     *
     * @return bool
     */
    public function blockImageExist($blockName, $fieldName)
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
    public function groupImageExist($blockName, $groupName, $imageName)
    {
        $groups_conf = $this->getGroupsFlatConfig($blockName);

        if(!array_key_exists($groupName, $groups_conf))
        {
            return false;
        }else{
            $struct = $this->getGroupImagesFlat($blockName, $groupName);

            return in_array($imageName, $struct);
        }
    }

}



































