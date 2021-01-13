<?php

    declare(strict_types = 1);

    namespace app\providers;

    class FileProvider
    {

        private static $display_version = CONF_DISPLAYVERSION;

        /**
         * Save the version of the
         * file to the include url
         *
         * @param $mode
         *
         * @return void
         */
        public static function setVersionMode($mode): void
        {

            self::$display_version = $mode;

        }


        /**
         * Creates the list of
         * page dependent files (js only)
         *
         * @param array( folder, filename )  $js
         * @param array( folder, filename )  $css
         *
         * @return string
         */
        public static function enqueue($js = array(), $css = array()): string
        {

            //RETURN
            $out = "";

            if (!empty($css)) {
                $out .= self::loop($css, 'css');
            }

            if (!empty($js)) {
                $out .= self::loop($js, 'js');
            }

            return $out;

        }

        /**
         * Creates a recursive architecture
         * by loading all necessary files
         *
         * @param $files
         * @param string  $type
         *
         * @return string
         */
        private static function loop($files, $type = 'js'): string
        {

            $render = '';

            foreach ($files as $key => $values) {

                if (is_array($values)) {

                    foreach ($values as $value) {

                        $url = DIR_PUBLIC."/{$type}/{$key}/{$value}.{$type}";

                        if (file_exists($url)) {

                            $modify = self::recoverFileVersion($url);

                            $render .= HtmlProvider::$type("/{$type}/{$key}/{$value}.min.{$type}?v={$modify}");

                        }

                    }

                }

            }

            return $render;

        }

        /**
         * Retrieves the file's
         * last modified date
         *
         * @param $path
         *
         * @return string
         */
        public static function recoverFileVersion($path): string
        {

            if (file_exists($path) === false) {
                return (string)0;
            }

            $modifyDate = date("F d Y H:i:s", filemtime($path));

            $modifyDate = strtotime($modifyDate);

            return (string)$modifyDate;

        }

    }