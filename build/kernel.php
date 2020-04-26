<?php

namespace Build\Application;

use Build\Controller\routes;

class kernel
{
    //ACTIONS
    protected $Routes;
    protected $Request;

    //KERNEL
    private $spaces;
    private $basedir;

    /**
     * Record application startup
     * Provides a convenient and automatically generated class
     * loader for our application.
     */
    public function __construct()
    {
        // Directory where the libraries are located
        $this->basedir = __DIR__;

        // Defines which components are to be loaded
        $this->spaces = array(
            'component' => array('head', 'response', 'request'),
            'converter' => array('json'),
            'routes'    => array('routes')
        );

        // Create core
        $this->builderKernel();

    }

    /**
     * Record automatic component loading
     */
    private function builderKernel()
    {
        // Imports all necessary files for the application engine
        foreach ($this->spaces as $key => $space)
            foreach ($space as $file) {
                require_once(__DIR__ . '/' . $key . '/' . $file . '.php');
            }

    }

    /**
     * Redirects the request by analyzing
     * its controllers and routes
     */
    public function Routes(){

        $this->Routes = new routes();

    }


}