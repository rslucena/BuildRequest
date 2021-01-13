<?php

    declare(strict_types = 1);

    namespace app\interfaces;

    class LogInterface
    {

        protected static $error;
        protected static $trace = array();

        /**
         *
         * Log a new Log
         * Validating the existence of the file
         * Cleaning if requested
         *
         * @param $message
         * @param array  $params
         * @param bool  $clear
         *
         * @return void
         */
        public static function save($message, $params = array(), $clear = false): void
        {

            self::requestTrace();

            self::create();

            if ($clear) {
                self::flush();
            }

            $file = fopen(self::$trace['path'], 'ab+');

            fwrite(
                    $file,
                    @date('d/m/Y h:i:s')." - ".
                    self::$trace['className']."Controller - ".self::$trace['function']."Function \n".
                    'msg:: '.$message."\n".
                    'params:: '.json_encode($params, JSON_THROW_ON_ERROR)."\n\n"
            );

            fclose($file);

        }

        /**
         * Do the necessary tracking
         * to identify which controller and function
         *
         * @return void
         *
         */
        public static function requestTrace(): void
        {

            $trace = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

            if (substr($trace, -1, 1) === '/') {
                $trace = substr($trace, 0, -1);
            }

            if (isset($trace[0]) && $trace[0] === '/') {
                $trace = substr($trace, 1);
            }

            $trace = explode('/', $trace);
            array_multisort($trace, SORT_DESC);

            self::$trace['className'] = !empty($trace[0]) ? $trace[0] : "home";
            self::$trace['function'] = !empty($trace[1]) ? $trace[1] : "index";

            if (empty($trace[1])) {
                self::$trace['function'] = "index";
                self::$trace['className'] = $trace[0];
            }

            if (empty($trace[0])) {
                self::$trace['function'] = 'index';
                self::$trace['className'] = 'home';
            }

        }

        /**
         * Used to create
         * any type of Log file
         *
         * @file nameController__nameFunction
         *
         * @return void
         */
        public static function create(): void
        {

            $nameFile = self::$trace['className'].'Controller__'.self::$trace['function'].'Function.txt';

            $nameFile = ucfirst($nameFile);

            if (file_exists(DIR_LOGS.DIRECTORY_SEPARATOR.$nameFile) === false) {

                $file = fopen(DIR_LOGS.DIRECTORY_SEPARATOR.$nameFile, 'wb');
                fwrite($file, '');
                fclose($file);

            }

            self::$trace['path'] = DIR_LOGS.DIRECTORY_SEPARATOR.$nameFile;

        }

        /**
         * Used to clear
         * any type of Log file
         *
         * @file nameController__nameFunction
         */
        public static function flush(): void
        {

            self::requestTrace();

            $nameFile = self::$trace['className'].'Controller__'.self::$trace['function'].'Function.txt';

            $nameFile = ucfirst($nameFile);

            $file = fopen(DIR_LOGS.DIRECTORY_SEPARATOR.$nameFile, 'wb');
            fwrite($file, '');
            fclose($file);

        }

    }