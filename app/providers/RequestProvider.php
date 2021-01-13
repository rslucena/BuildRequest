<?php

    declare(strict_types = 1);

    namespace app\providers;

    use app\interfaces\authInterface;
    use app\interfaces\logInterface;

    class RequestProvider
    {

        /**
         * Creates a request to any other HTTP
         * server or to the same * in CURL
         *
         * @param $method
         * @param $end
         * @param array  $props
         * @param bool  $token
         * @param string  $api
         *
         * @return null|array
         */
        public static function request($method, $end, $props = array(), $token = false, $api = APP_API): ?array
        {

            //INITIALIZATION
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $api.$end);

            //SHIPPING AND RETURN METHOD
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($props, JSON_THROW_ON_ERROR));

            //HEADER
            curl_setopt($curl, CURLOPT_HTTPHEADER, self::getHeader($token));

            $response = curl_exec($curl);

            if (curl_error($curl)) {
                $response = "{}";
                logInterface::save(curl_error($curl));
            }

            curl_close($curl);

            return self::buildReturn($response, array($method, $end, $props, $token));

        }


        /**
         * Creates the header for the CURL request
         *
         * @param bool  $token
         *
         * @return array
         */
        private static function getHeader($token = false): array
        {

            $authorization = array();

            $head = array('Content-Type: application/json');

            if ($token === false) {
                return $head;
            }

            if (authInterface::is_login()) {
                $authorization = authInterface::getSession('token');
            }

            if (!empty($authorization[0])) {
                $head[] = 'Authorization: Bearer '.$authorization[0];
            }

            return $head;
        }


        /**
         * Returns an array of results
         *
         * @param string  $resp
         * @param array  $request
         *
         * @return array
         */
        public static function buildReturn($resp, $request): array
        {

            $response = array();

            $res = json_decode($resp, true, 512, JSON_THROW_ON_ERROR);

            $response['origin'] = array();
            $response['Router'] = array();

            if (!empty($res)) {
                $response['origin'] = $res;
                $response['Router'] = $request;
            }

            if (!empty($response['origin']['access_token'])) {
                authInterface::auth($response['origin']['access_token'], false);
            }

            return $response;

        }


        /**
         * Retrieves all values ​​
         * sent via POST and GET
         *
         * @return null|array
         */
        public static function buildInput(): ?array
        {

            return $_REQUEST;

        }


        /**
         * Validates a field based
         * on its rules and value
         *
         * @param $field
         * @param $rules
         *
         * @return bool
         */
        public static function validate($field, $rules): bool
        {

            $valid = true;

            if (strpos($rules, "|") === false) {
                $rules .= "|";
            }

            $rules = explode('|', $rules);

            $rules = array_filter($rules, static function ($value) {
                return !empty($value) || $value === 0;
            });

            if (empty($rules)) {
                return $valid;
            }

            foreach ($rules as $rule) {

                $MinMax = 0;

                if (
                        strpos($rule, "min:") !== false ||
                        strpos($rule, "max:") !== false
                ) {

                    $stale = explode(":", $rule);

                    $rule = $stale[0];

                    if( !empty( $stale[1] ) ){
                        $MinMax = $stale[1];
                    }

                }

                switch ($rule) {

                    case 'text' :
                        $valid = is_string($field);
                        break;

                    case 'number' :
                        $valid = is_numeric($field);
                        break;

                    case 'required' :
                        $valid = !empty($field);
                        break;

                    case 'email' :
                        $email = filter_var($field, FILTER_VALIDATE_EMAIL);
                        $valid = !is_bool($email);
                        break;

                    case 'min' :
                        $valid = strlen($field) >= $MinMax;
                        break;

                    case 'max' :
                        $valid = strlen($field) <= $MinMax;
                        break;

                    case 'equals' :

                        $equal  = array();

                        foreach(array_count_values($field) as $val => $c){
                            if( $c > 1 ){
                                $equal[] = $val;
                            }
                        }

                        return !empty($equal);

                        break;

                    default :
                        return $valid;
                        break;
                }

            }

            return $valid;
        }

        /**
         * Checks whether specific keys are contained in a
         * array array
         *
         * @param $props
         * @param array  $keys
         *
         * @return bool
         */
        public static function key_exists( $props, $keys = array() ) : bool {

            if( !is_array( $keys ) || empty( $props ) ){
                return false;
            }

            $valid = array();

            foreach ($keys as $key) {
                $valid[] = array_key_exists($key, $props);
            }

            return !(in_array(false, $valid, true));

        }


    }


