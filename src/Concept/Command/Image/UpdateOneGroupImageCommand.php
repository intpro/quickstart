<?php namespace Interpro\QuickStorage\Concept\Command\Image;

class UpdateOneGroupImageCommand extends ImageCommand
{
    public $group_name;
    public $group_id;
    public $req_orig_file;

    /**
     * @param string $block_name
     * @param string $group_name
     * @param string $image_name
     * @param int $group_id
     * @param string $req_orig_file
     *
     * @return void
     */
    public function __construct($block_name, $group_name, $image_name, $group_id, $req_orig_file)
    {
        parent::__construct($block_name, $image_name);

        $this->group_name = $group_name;
        $this->group_id = $group_id;
        $this->req_orig_file = $req_orig_file;
    }

}
