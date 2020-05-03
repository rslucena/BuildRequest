<?php

namespace Build\Component;

use Build\DataBase\Component;

class request extends response {

    use Component\authentication;

    private $Request;

    /**
     * Request constructor.
     * @param $input
     */
    public function handle($input = null){

        $this->Request = $input;

        // check the compulsory fields
        if (!array_key_exists('token',  $this->Request ) )
            $this->createResponse(false, null);

        // check the compulsory fields
        if (!array_key_exists('router',  $this->Request ) )
            $this->createResponse(false, null);

    }

    public function call( $command, array $parameters = array() ){



    }

}