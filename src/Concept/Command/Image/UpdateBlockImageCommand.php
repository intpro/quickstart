<?php namespace Interpro\QuickStorage\Concept\Command\Image;

class UpdateBlockImageCommand extends ImageCommand
{
    public $req_orig_file;

    /**
     * @param string $block_name
     * @param string $image_name
     * @param string $req_orig_file
     *
     * @return void
     */
    public function __construct($block_name, $image_name, $req_orig_file)
    {
        parent::__construct($block_name, $image_name);

        $this->req_orig_file = $req_orig_file;
    }

}
