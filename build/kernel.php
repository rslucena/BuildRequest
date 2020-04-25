<?php

namespace Build\Application;

class kernel
{

    protected $spaces;
    protected $basedir;

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
            'converter' => array('json')
        );

        // Init
        $this->builderKernel();
    }

    /**
     * Record automatic component loading
     */
    public function builderKernel()
    {
        foreach ($this->spaces as $key => $space)
            foreach ($space as $file) {
                require_once(__DIR__ . '/' . $key . '/' . $file . '.php');
            }

    }


}