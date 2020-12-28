<?php

    declare(strict_types = 1);

    namespace app\providers;

    use DateTime;
    use Exception;

    class htmlProvider
    {

        /**
         * Link function, create an link / include stylesheet
         *
         * @param $href
         * @param array  $props
         *
         * @return string
         */
        public static function css($href, $props = array())
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
        public static function js($href, $props = array())
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
        public static function img($src, $props = array())
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
        public static function p($value, $props = array())
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
        public static function div(string $value, $props = array())
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
        public static function ul(string $value, $props = array())
        {
            return self::tag('ul', $value, $props);
        }

        /**
         * date time function, create a date time tag
         *
         * @param string  $value
         * @param string  $entry
         * @param string  $leave
         *
         * @return string
         * @throws \Exception
         */
        public static function time(string $value, string $entry = "Y-m-d H:i:s", string $leave = "d/m/Y - H:i")
        {

            try {

                $date = DateTime::createFromFormat($entry, $value)->format($leave);

                $props = array('datetime' => $date);

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
        public static function li(string $value, $props = array())
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
        public static function label(string $value, $props = array())
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
        public static function input(string $value, $props = array())
        {
            if( empty($props['name'])){
                $props['name'] = $value;
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
         *
         * @return string
         */
        public static function checkbox(string $name, string $text ){

            $input = '';

            $input .= self::label($text, array('for' => $name, 'class' => 'input-label'));
            $input .= self::input($name, array('id' => $name, 'type' => 'checkbox', 'class' => 'input'));

            $input .= self::div(" ", array('class' => "after control_indicator"));

            $input = self::div($input, array('class' => "decoration checkbox relative"));

            return $input;

        }

        /**
         * Creates the reserved page
         * the corresponding page
         *
         * @param int $step
         * @param string $total
         * @param int  $active
         *
         * @return string
         */
        public static function pager(int $step, string $total, int $active = 1): string
        {

            if ($total < 0) {
                return "";
            }

            //Current Page
            $currentPage = strtok($_SERVER['REQUEST_URI'], '?');

            //NUMBER OF PAGES
            $pages = intval($total / $step);
            $pages = (($pages * $step) == $total) ? $pages : $pages + 1;

            if ($pages < 1) {
                return '';
            }

            #Config
            $return = "";
            $active = !empty($_GET['p']) ? $_GET['p'] : 1;

            #PREV

            if ($active != 1) {
                $link = self::a("$currentPage?p=".intval($active - 1), "<", array('class' => "block btn btn-padding bt-border borderRadius c-secondary") );
                $return .= self::li( $link , array('class' => 'm-r'));
            }

            #NUMBER
            for ($x = 1; $x <= $pages; $x++) {
                if (($x >= ($active - 2)) && ($x < ($active + 3) or ($x < 6))) {
                    $class = ($active == $x) ? 'bg-primary' : '';
                    if (!empty($class)) {

                        $link = self::a("$currentPage?p=$x", "$x", array('class' => "block btn btn-padding borderRadius normal bt-border bg-primary c-white") );
                        $return .= self::li( $link , array('class' => 'm-r'));

                    } else {

                        $link = self::a("$currentPage?p=$x", "$x", array('class' => "block btn btn-padding borderRadius bt-border c-secondary") );
                        $return .= self::li( $link , array('class' => 'm-r'));

                    }
                }

            }

            #NEXT
            if (($active + 1) <= $pages) {
                $link = self::a("$currentPage?p=".intval($active + 1), ">", array('class' => "block btn btn-padding borderRadius bt-border c-secondary") );
                $return .= self::li( $link , array('class' => 'm-r'));
            }

            $return = self::ul($return, array('class' => 'flex m-t m-b content-left pagination'));

            return $return;

        }

    }



