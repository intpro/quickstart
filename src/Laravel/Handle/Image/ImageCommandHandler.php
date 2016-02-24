<?php namespace Interpro\QuickStorage\Laravel\Handle\Image;

use Interpro\ImageFileLogic\Concept\ActionChainFactory;
use Interpro\QuickStorage\Concept\ImageRepository;
use Interpro\QuickStorage\Concept\QSource;

abstract class ImageCommandHandler
{
    protected $actionChainFactory;
    protected $imageRepository;
    protected $qSource;

    /**
     *
     * Interpro\ImageFileLogic\Concept\ActionChainFactory $actionChainFactory
     * Interpro\ImageFileLogic\Concept\ImageRepository $imageRepository
     *
     * @return void
     */
    public function __construct(ActionChainFactory $actionChainFactory, ImageRepository $imageRepository, QSource $qSource)
    {
        $this->actionChainFactory = $actionChainFactory;
        $this->imageRepository    = $imageRepository;
        $this->qSource            = $qSource;
    }

}
