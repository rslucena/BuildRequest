<?php

    declare(strict_types = 1);

    namespace app\Bootstrap;

    use app\interfaces\modelInterface;

    /**
     * Class Builder
     *
     * @package app\bootstrap
     */
    class Builder extends modelInterface
    {

        #Router
        private static $action;
        private static $controler;

        #APP Version
        public $version = APP_VERSION;

        #KEYS
        public $keys = array();

        /**
         * CREATE PAGE
         *
         * @return void
         */
        public function loadPage(): void
        {

            $this->getRouter();

            #init keys
            $this->keys['action'] = self::$action;
            $this->keys['controler'] = self::$controler;

            //LOAD PAGE CONFIGURATION FILE
            $page = $this->requestView("", self::$action);

            //CREATE CLASS
            $className = 'app\controllers\\'.$this->keys['controler'].'Controller';

            //CALL FUNCTION
            if( class_exists( $className ) ) {

                $newClass = new $className();

                if (method_exists($newClass, self::$action)) {
                    $action = self::$action;
                    $this->keys = $newClass->$action();
                }

            }

            $this->keys['head']['canonical'] = APP_URL;
            $this->keys['head']['menu-'.self::$controler] = 'activated';

            if( empty($page) ){
                $this->redirect("/error404");
            }

            $html = $this->applyKeys($page, $this->keys);

            //PRINT THE PAGE
            echo $html;
        }

        /**
         * CREATE ROUTER
         */
        private function getRouter(): void
        {

            #CONTROLLER
            $url_parameter[1] = str_replace(array('-', "@"), '_', $this->getParameter(1));
            $controller_name = explode('?', $url_parameter[1]);

            self::$controler = ($controller_name[0] === '') ? 'home' : $controller_name[0];

            #ACTION
            $url_parameter[2] = str_replace(array('-', "@"), '_', $this->getParameter(2));
            self::$action = ($url_parameter[2] === '') ? 'index' : $url_parameter[2];

            if( strpos(self::$controler, "error") !== false){
                self::$controler = 'home';
                self::$action = 'error404';
            }

        }

        /**
         * getParameter get the url parameters
         *
         * @param int  $position
         *
         * @return string
         */
        public function getParameter(int $position): string
        {
            $URL = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

            #get the url and explode to get positions
            $url = explode('/', $URL);

            $return = (count($url) > $position) ? $url[$position] : '';

            $return = (@$return[0] === '?') ? '' : $return;

            return $return;
        }

        /**
         * includeHTML function, import html
         * file to the current page
         *
         * @param string  $folder
         * @param string  $end
         * @param array  $props
         *
         * @return string
         */
        public function requestView(string $folder = "", string $end = "", $props = array()): string
        {
            //GET FOLDER
            $folder = !empty($folder) ? $folder : self::$controler;

            //FILE NAME
            $end = !empty($end) ? $end : 'index';

            //ORGANIZE THE WAY
            $Path = DIR_VIEW."/$folder/";
            $File = $Path."$end.html";

            //REQUEST FILE VIEW
            $template = "";

            if (file_exists($File)) {
                $template = "<!-- Start: $folder/$end -->";
                $template .= file_get_contents($File);
                $template .= "<!-- End: $folder/$end -->";
            }

            if (!empty($props)) {

                $template = $this->applyKeys($template, $props);

            }

            return $template;

        }

        /**
         * applyKeys function
         *
         * @param $html
         * @param array  $keys
         *
         * @return string
         */
        public function applyKeys(string $html, $keys = array()): string
        {

            if (empty($keys)) {
                return $html;
            }

            for ($x = 1; $x < 6; $x++) {

                foreach ($keys as $key => $value) {

                    if (!is_array($value)) {
                        $html = str_replace('{'.$key.'}', (string)$value, $html);
                    }

                    if (is_array($value)) {

                        foreach ($value as $index => $val) {

                            if (is_array($val)) {

                                foreach ($val as $i => $v) {

                                    if (is_array($v)) {

                                        foreach ($v[0] as $kk => $vv) {
                                            $html = str_replace('{'.$index.'->'.$kk.'}', (string)$vv, $html);
                                        }

                                    } else {

                                        $html = str_replace('{'.$val.'->'.$key.'->'.$i.'}', (string)$v, $html);

                                    }

                                }

                            } else {

                                $html = str_replace('{'.$key.'->'.$index.'}', (string)$val, $html);

                            }

                        }
                    }

                }

            }

            return $html;
        }

        /**
         * redirect function
         *
         * @param string $link
         *
         * @return void
         */
        public function redirect(string $link): void
        {
            header("location: ".$link);
            die();
        }

        /**
         * Creates a slug (url), dynamically and validates
         *
         * @param string  $string
         *
         * @return string
         */
        public function slug(string $string): string
        {

            $pattern = array("'é'", "'è'", "'ë'", "'ê'", "'É'", "'È'", "'Ë'", "'Ê'", "'Ã'", "'ã'", "'á'", "'à'", "'ä'", "'â'", "'å'", "'Á'", "'À'", "'Ä'", "'Â'", "'Å'", "'ó'", "'ò'", "'ö'", "'ô'", "'Ó'", "'Ò'", "'Ö'", "'Ô'", "'í'", "'ì'", "'ï'", "'î'", "'Í'", "'Ì'", "'Ï'", "'Î'", "'ú'", "'ù'", "'ü'", "'û'", "'Ú'", "'Ù'", "'Ü'", "'Û'", "'ý'", "'ÿ'", "'Ý'", "'ø'", "'Ø'", "'œ'", "'Œ'", "'Æ'", "'ç'", "'Ç'");
            $replace = array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A', 'A', 'o', 'o', 'o', 'o', 'O', 'O', 'O', 'O', 'i', 'i', 'i', 'I', 'I', 'I', 'I', 'I', 'u', 'u', 'u', 'u', 'U', 'U', 'U', 'U', 'y', 'y', 'Y', 'o', 'O', 'a', 'A', 'A', 'c', 'C');

            $slug = preg_replace($pattern, $replace, $string);

            $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $slug);

            $slug = strtolower($slug);

            return $slug;

        }


    }