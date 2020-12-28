<?php

    declare(strict_types = 1);

    namespace app\interfaces;

    class logInterface
    {

        static $error;
        static $trace = array();

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
         */
        static function save($message, $params = array(), $clear = false): void
        {

            self::requestTrace();

            self::create();

            if ($clear) {
                self::flush();
            }

            $file = fopen(self::$trace['path'], "a+");

            fwrite(
                    $file,
                    @date('d/m/Y h:i:s')." - ".
                    self::$trace['className']."Controller - ".self::$trace['function']."Function \n".
                    'msg:: '.$message."\n".
                    'params:: '.json_encode($params)."\n\n"
            );

            fclose($file);

        }

        /**
         * Do the necessary tracking
         * to identify which controller and function
         */
        static function requestTrace()
        {

            $trace = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

            if (substr($trace, -1, 1) === '/') {
                $trace = substr($trace, 0, -1);
            }

            if (substr($trace, 0, 1) === '/') {
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
         */
        static function create(): void
        {

            $nameFile = self::$trace['className'].'Controller__'.self::$trace['function'].'Function.txt';

            $nameFile = ucfirst($nameFile);

            if (file_exists(DIR_LOGS.'\\'.$nameFile) == false) {

                $file = fopen(DIR_LOGS.'\\'.$nameFile, "w");
                fwrite($file, '');
                fclose($file);

            }

            self::$trace['path'] = DIR_LOGS.'\\'.$nameFile;

        }

        /**
         * Used to clear
         * any type of Log file
         *
         * @file nameController__nameFunction
         */
        static function flush(): void
        {

            self::requestTrace();

            $nameFile = self::$trace['className'].'Controller__'.self::$trace['function'].'Function.txt';

            $nameFile = ucfirst($nameFile);

            $file = fopen(DIR_LOGS.'\\'.$nameFile, "w");
            fwrite($file, '');
            fclose($file);

        }

    }