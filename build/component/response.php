<?php

namespace Build\Component;

class response extends head
{

    private $args;
    private $response;

    /**
     * Get a value the arguments
     * @param $name
     * @return mixed | null
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->args))
            return (string) $this->args[$name];

        return null;
    }

    /**
     * Sets a value for the arguments
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->args[$name] = (string) $value;
    }

    /**
     * Show Debug
     * @return array
     */
    public function __debugInfo()
    {
        $this->response = [
            ['Head' => http_response_code() ],
            ['Response' => get_object_vars($this)]
        ];

        return $this->response;
    }

    /**
     * Work args
     * Formats the arguments for the response
     */
    private function afterResponse($args)
    {

        if (empty($args))
            return array();

        return $this->response;

    }

    /**
     * Create response
     * @param $valid ? Checks whether the header is positive or negative
     * @param $args ? Return response arguments
     */
    public function createResponse($valid, $args)
    {

        if ($valid === false)
            $this->setHead(null);

        $this->setHead(200);

        $this->response['args'] = $this->afterResponse($args);

    }




}