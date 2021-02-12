<?php

    declare(strict_types = 1);

    namespace app\interfaces;

    class AuthInterface
    {

        /**
         * Checks if a user is logged
         * in to the system
         *
         * @param string  $token
         * @param bool  $regenerate_id
         *
         * @return bool
         */
        public static function auth($token, $regenerate_id = true): bool
        {

            $invert = explode('.', $token);
            foreach ($invert as $key => $item) {
                $invert[$key] = base64_decode($item);
            }

            $user = array();
            if (!empty(json_decode($invert[1], true, 512, JSON_THROW_ON_ERROR))) {
                $user = json_decode($invert[1], true, 512, JSON_THROW_ON_ERROR);
            }

            unset($invert);

            foreach ($user['data'] as $key => $value) {
                self::updateSession('user', $key, $value);
            }

            self::updateSession('config', 'token', $token);

            if ($regenerate_id) {

                session_regenerate_id(true);

            }

            if (empty(self::getSession())) {
                return false;
            }

            return true;

        }

        /**
         * Retrieves a value or all in
         * the open session
         *
         * @param string $parent
         * @param string $key
         *
         * @return mixed
         */
        public static function getSession($parent = '__allsession', $key = "")
        {

            if( !empty( $key ) ){
                return $_SESSION[$parent][$key] ?? null;
            }

            if( $parent === '__allsession' ){
                return $_SESSION;
            }

            return $_SESSION[$parent] ?? null;

        }

        /**
         * Saves or updates a value for a user session
         *
         * @param $key
         * @param $val
         * @param $parent
         *
         * @return null|string
         */
        public static function updateSession($parent, $key, $val): ?string
        {

            $_SESSION[$parent][$key] = $val;

            if (!empty($_SESSION[$parent][$key])) {
                return (string)$_SESSION[$parent][$key];
            }

            return null;

        }

        /**
         * Checks if a user is logged
         * in to the system
         *
         * @return null|bool
         */
        public static function is_access(): ?bool
        {

            if (!self::is_login()) {
                return false;
            }

            $user = self::getSession('user');

            $ass = $user['assinatura'] ?? 0;

            return isset($ass) && $ass[0] !== 0;

        }

        /**
         * Checks if a user is logged
         * in to the system
         *
         * @return bool
         */
        public static function is_login(): bool
        {

            if (empty(self::getSession('user'))) {
                return false;
            }

            if ( empty( self::getSession( 'config', 'token' ) ) ) {
                return false;
            }

            return true;

        }

        /**
         * Remove Session and force login

         * @return void
         */
        public static function logout(): void
        {

            session_unset();
            session_destroy();
            unset($_SESSION);

            session_start();

            self::updateSession('config', 'maxlife', time() + (int)CONF_TIMESESSION );

        }

        /**
         * Checks the validity of the assignment and if it expires
         * removes and recreates a new
         */
        public static function build() : void
        {

            session_start();

            $maxlife = self::getSession('config', 'maxlife');

            if ( empty( $maxlife ) ) {
                self::logout();
            }

            if ( $maxlife < time() ) {
                self::logout();
            }

        }

    }