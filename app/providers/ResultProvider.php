<?php

    declare(strict_types = 1);

    namespace app\providers;

    class ResultProvider
    {

        /**
         * Creates a response interface for external or internal functions
         * standardization the data output
         *
         * @param int  $status
         * @param array  $props
         *
         * @return void|array
         */
        public static function result($status = 401, $props = array()): ?array
        {

            $return = [];

            switch ($status) {
                case 0:
                    $return['status'] = 'error';
                    break;
                case 1:
                    $return['status'] = 'info';
                    break;
                case 2:
                    $return['status'] = 'success';
                    break;
                default:
                    self::forceEnd($status);
                    break;
            }

            $props = !empty($props) ? $props : array();

            if (!empty($props['status'])) {
                $return['status'] = $props['status'];
                unset($props['status']);
            }

            $return = array_merge($return, $props);

            $return = self::buildJson($return);

            header('Content-Type: application/json');

            echo $return;

            die();
        }

        private static function forceEnd($header): void
        {
            http_response_code($header);
            die();
        }

        /**
         * Closes the return and request cycle
         * cutting the final execution of ajax
         *
         * @param array  $resp
         *
         * @return string
         */
        private static function buildJson($resp): string
        {

            return json_encode($resp, JSON_THROW_ON_ERROR);

        }

    }




