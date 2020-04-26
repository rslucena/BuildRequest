<?php

namespace Build\Component;

class request extends response {

    private $Request;

    /**
     * Request constructor.
     * @param $input
     */
    public function handle($input = null){

        $this->Request = $input;

    }

    public function call( $command, array $parameters = array() ){


    }

}