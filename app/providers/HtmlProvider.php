<?php

    declare(strict_types = 1);

    namespace app\providers;

    use DateTime;
    use Exception;

    class HtmlProvider
    {

        /**
         * Link function, create an link / include stylesheet
         *
         * @param $href
         * @param array  $props
         *
         * @return string
         */
        public static function css($href, $props = array()): string
        {
            $props['href'] = $href;
            $props['rel'] = 'stylesheet';
            return self::tag('link', null, $props);
        }

        /**
         * Function responsible for creating any HTML tag
         *
         * @param $tag
         * @param $cont
         * @param $attr
         *
         * @return string
         */
        private static function tag($tag, $cont, $attr): string
        {
            $params = '';

            if (is_array($attr)) {
                foreach ($attr as $k => $v) {
                    $params .= $k.'="'.$v.'" ';
                }
            }

            if (empty($cont)) {
                return "<$tag $params />";
            }

            return "<$tag $params >".trim($cont)."</$tag>";

        }

        /**
         * Script function, create an script / include JS
         *
         * @param $href
         * @param array  $props
         *
         * @return string
         */
        public static function js($href, $props = array()): string
        {
            $props['src'] = $href;
            return self::tag('script', " ", $props);
        }

        /**
         * Creates a division between the elements
         * using the HR tag
         *
         * @param string  $color
         * @param int $size
         * @param array  $props
         *
         * @return string
         */
        public static function hr(array $props = array(), string $color = "c-primary", int $size = 12): string
        {

            if (strpos($color, "#") !== false) {
                $props['style'] = "color: $color";
            } else {
                $props['class'] .= " $color";
            }

            $props['class'] .= " w-$size";

            return self::tag("hr", null, $props);
        }

        /**
         * Create titles
         * h1 -> h6
         *
         * @param $content
         * @param int  $size
         * @param array  $props
         *
         * @return string
         */
        public static function titles($content, $size = 1, $props = array()): string
        {

            if ($size <= 0 && $size >= 7) {
                $size = 1;
            }

            return self::tag("h$size", $content, $props);

        }

        /**
         * img function, create an img
         *
         * @param $src
         * @param array  $props
         *
         * @return string
         */
        public static function img($src, $props = array()): string
        {

            $props['src'] = $src;
            return self::tag('img', null, $props);

        }

        /**
         * a function, create an hyperlink
         *
         * @param string $href
         * @param array  $props
         * @param string $content
         *
         * @return string
         */
        public static function a(string $href, string $content = "", $props = array()) : string
        {

            $props['href'] = $href;
            return self::tag('a', $content, $props);

        }

        /**
         * p function, create an paragraph
         *
         * @param $value
         * @param array  $props
         *
         * @return string
         */
        public static function p($value, $props = array()): string
        {
            return self::tag('p', $value, $props);
        }

        /**
         * div function, create an div
         *
         * @param string  $value
         * @param array  $props
         *
         * @return string
         */
        public static function div(string $value, $props = array()): string
        {
            return self::tag('div', $value, $props);
        }

        /**
         * Function to create an
         * unordered listing
         *
         * @param string  $value
         * @param array  $props
         *
         * @return string
         */
        public static function ul(string $value, $props = array()): string
        {
            return self::tag('ul', $value, $props);
        }

        /**
         * date time function, create a time tag
         *
         * @param string  $value
         * @param string  $entry
         * @param string  $leave
         *
         * @return string
         * @throws \Exception
         */
        public static function time(string $value, string $entry = "Y-m-d H:i:s", string $leave = "d/m/Y - H:i"): ?string
        {

            try {

                $date = "";
                $props = array();

                if( !empty( $value ) ){

                    $date = DateTime::createFromFormat($entry, $value)->format($leave);

                    $props = array('datetime' => $date);

                }

                return self::tag('time', $date, $props);

            } catch (Exception $exception) {

                return '';

            }

        }

        /**
         * Function to create an
         * item listing
         *
         * @param string  $value
         * @param array  $props
         *
         * @return string
         */
        public static function li(string $value, $props = array()): string
        {
            return self::tag('li', $value, $props);
        }

        /**
         * Function to create label for input
         *
         * @param string  $value
         * @param array  $props
         *
         * @return string
         */
        public static function label(string $value, $props = array()): string
        {
            if( empty($props['for'])){
                $props['for'] = $value;
            }
            return self::tag('label', $value, $props);
        }

        /**
         * Function to create input
         *
         * @param string  $value
         * @param array  $props
         *
         * @return string
         */
        public static function input(string $value, $props = array()): string
        {
            if( empty($props['name'])){
                $props['name'] = $value;
            }

            if( empty($props['value'])){
                $props['value'] = $value;
            }

            if( empty($props['type'])){
                $props['type'] = 'text';
            }

            return self::tag('input', null, $props);
        }

        /**
         * Function to create checkbox input
         *
         * @param string  $name
         * @param string  $text
         * @param string  $value
         * @param bool  $checkbox
         *
         * @return string
         */
        public static function checkbox(string $name, string $text, string $value = "", bool $checkbox = false): string
        {

            $input = '';

            $input .= self::label($text, array('for' => $name, 'class' => 'input-label'));

            $props = array('id' => $name, 'value' => $value, 'type' => 'checkbox', 'class' => 'input');

            if( $checkbox ){
                $props['checked'] = "checked";
            }

            $input .= self::input($name, $props);

            $input .= self::div(" ", array('class' => "after control"));

            $input = self::div($input, array('class' => "decoration checkbox relative"));

            return $input;

        }

        /**
         * Function to create form input
         *
         * @param string  $id
         * @param string  $inputs
         * @param string  $url
         * @param string  $enctype
         * @param string  $method
         *
         * @return string
         */
        public static function form(string $id, string $inputs, string $url = "", string $enctype = "application/x-www-form-urlencoded", string $method = "POST"){

            $props = array();

            $props['id'] = !empty( $id ) ? $id : substr( sha1((string)mt_rand()) ,10,10);

            $props['action'] = !empty($url) ? $url : $_SERVER['REQUEST_URI'];

            $props['enctype'] = $enctype;

            $props['method'] = $method;

            return self::tag('form', $inputs, $props);

        }

        /**
         * Creates the reserved page
         * the corresponding page
         *
         * @param int $step
         * @param string $total
         *
         * @return string
         */
        public static function pager(int $step, string $total): string
        {

            if ($total < 0) {
                return "";
            }

            //Current Page
            $currentPage = strtok($_SERVER['REQUEST_URI'], '?');

            //NUMBER OF PAGES
            $pages = (int)($total / $step);
            $pages = (($pages * $step) === $total) ? $pages : $pages + 1;

            if ($pages < 1) {
                return '';
            }

            #Config
            $return = "";

            if(!empty($_GET['p'])){
                $active = (int)$_GET['p'];
            }else{
                $active = 1;
            }

            #PREV

            if ($active !== 1) {
                $link = self::a("$currentPage?p=".($active - 1), "<", array('class' => "block btn btn-padding btn-padding-2 borderRadius bt-border c-secondary") );
                $return .= self::li( $link , array('class' => 'm-r'));
            }

            #NUMBER
            for ($x = 1; $x <= $pages; $x++) {
                if (($x >= ($active - 2)) && ($x < ($active + 3) || ($x < 6))) {
                    $class = ($active === $x) ? 'bg-primary' : '';
                    if (!empty($class)) {

                        $link = self::a("$currentPage?p=$x", (string)$x, array('class' => "block btn btn-padding btn-padding-2 borderRadius normal bt-border bg-primary c-white") );
                        $return .= self::li( $link , array('class' => 'm-r'));

                    } else {

                        $link = self::a("$currentPage?p=$x", (string)$x, array('class' => "block btn btn-padding btn-padding-2 borderRadius bt-border c-secondary") );
                        $return .= self::li( $link , array('class' => 'm-r'));

                    }
                }

            }

            #NEXT
            if (($active + 1) <= $pages) {
                $link = self::a("$currentPage?p=".($active + 1), ">", array('class' => "block btn btn-padding btn-padding-2 borderRadius bt-border c-secondary") );
                $return .= self::li( $link , array('class' => 'm-r'));
            }

            $return = self::ul($return, array('class' => 'flex m-t m-b content-left pagination'));

            return $return;

        }

        /**
         * Function to create option select
         *
         * @param array $content
         * @param string $placeholder
         *
         * @return string
         */
        public static function option( $content = array(), $placeholder = "" ){

            if( empty($content)){
                return '';
            }

            $code = "";
            $options = "";
            $no_selected = true;

            foreach ($content as $item) {

                $props['value'] = $item['name'];
                unset($props['selected']);

                if( !empty($item['selected']) && $item['selected'] === true){
                    $no_selected = false;
                    $props['selected'] = "selected";
                }

                $options .= self::tag('option', $item['text'], $props);

            }

            $props = array();
            if( $no_selected ) {
                $props = array(
                        'disabled' => 'disabled',
                        'selected' => 'selected'
                );
            }
            $code = self::tag('option', "Choose from options", $props);

            $code .= $options;

            return $code;

        }
    }





