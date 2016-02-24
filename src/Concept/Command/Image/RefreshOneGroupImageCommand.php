<?php namespace Interpro\QuickStorage\Concept\Command\Image;

class RefreshOneGroupImageCommand extends ImageCommand
{
    public $group_name;
    public $group_id;

    /**
     * @param string $block_name
     * @param string $group_name
     * @param string $image_name
     * @param int $group_id
     *
     * @return void
     */
    public function __construct($block_name, $group_name, $image_name, $group_id)
    {
        parent::__construct($block_name, $image_name);

        $this->group_name = $group_name;
        $this->group_id = $group_id;
    }

}
