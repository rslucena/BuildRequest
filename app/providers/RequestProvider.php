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
         *
         * @return null|array
         */
        public static function request($method, $end, $props = array(), $token = false): ?array
        {

            //INITIALIZATION
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, APP_API.$end);

            //SHIPPING AND RETURN METHOD
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($props));

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

            $head = array(
                    'Content-Type: application/json'
            );

            if (authInterface::is_login()) {
                $token = authInterface::getSession('token');
            }

            if ($token) {
                $head[] = 'Authorization: Bearer '.$token[0];
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

            $res = json_decode($resp, true);

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

            $resp = $_REQUEST;

            return $resp;

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
                $rules = $rules."|";
            }

            $rules = explode('|', $rules);

            $rules = array_filter($rules, function ($value) {
                return !empty($value) || $value === 0;
            });

            if (empty($rules)) {
                return $valid;
            }

            foreach ($rules as $rule) {

                $MinMax = null;

                if (
                        strpos($rule, "min:") !== false ||
                        strpos($rule, "max:") !== false
                ) {
                    $stale = explode(":", $rule);
                    $rule = $stale[0];
                    $MinMax = $stale[1];
                }

                switch ($rule) {

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

                    default :
                        return $valid;
                        break;
                }

            }

            return $valid;
        }
    }




