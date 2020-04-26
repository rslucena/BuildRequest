<?php

namespace Build\Controller;

use Build\Component\request;

class routes extends request
{

    //ACTIONS
    protected $method;
    protected $controller;

    //GET AGENT
    private $origin;

    /**
     * Routes constructor.
     */
    public function __construct()
    {

        $this->method = $_SERVER["REQUEST_METHOD"];

        $this->controller = $_SERVER["REQUEST_URI"];

        $this->origin = isset($_SERVER["HTTP_ORIGIN"]);

        $this->AccessControlAllowOrigin();

        $this->sendRequest();
    }

    /**
     * Indicates whether the response's
     * resources can be shared with the given source.
     */
    private function AccessControlAllowOrigin()
    {

        // Sets the access release if the source is available
        if ($this->origin) {
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
        }

        // Access-Control headers are received during OPTIONS requests
        if ($this->method == 'OPTIONS') {

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
                header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
                header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

            exit(0);

        }

    }

    /**
     * Envia para a classe de requisições
     */
    public function sendRequest()
    {

        $this->handle( $_REQUEST );

    }

}