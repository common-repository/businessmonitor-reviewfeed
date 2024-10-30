<?php
namespace FluffyMedia\BusinessMonitor;

// TODO: on plugin activation and de-activation etc.

class Loader
{
    protected $pluginDir;

    public $reviewsController;

    public function __construct($file)
    {
        $this->pluginDir = plugin_dir_path($file);
        $this->reviewsController = new ReviewsController($this->pluginDir);
    }

}