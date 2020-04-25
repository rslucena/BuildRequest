<?php

namespace Build\Component;

class head
{
    private $response_code;

    /**
     * Head constructor.
     */
    public function __construct()
    {
        $this->response_code = 200;
    }

    /**
     * Define a state to the header
     * @param $status_code
     */
    public function setHead( $status_code = null ){

        if(empty($status_code))
            http_response_code(500);

        http_response_code($this->response_code);

    }

}