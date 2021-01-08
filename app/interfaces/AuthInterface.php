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
                self::updateSession($key, $value);
            }

            self::updateSession('token', $token);

            if ($regenerate_id) {
                session_regenerate_id(true);
            }

            if (empty(self::getSession())) {
                return false;
            }

            return true;

        }

        /**
         * Saves or updates a value for a user session
         *
         * @param $key
         * @param $val
         *
         * @return null|string
         */
        public static function updateSession($key, $val): ?string
        {

            $_SESSION[$key] = $val;

            if (!empty($_SESSION[$key])) {
                return (string)$_SESSION[$key];
            }

            return null;

        }

        /**
         * Retrieves a value or all in
         * the open session
         *
         * @param string  $filter
         *
         * @return null|array
         */
        public static function getSession($filter = ""): ?array
        {

            if (!empty($filter) && is_string($filter)) {

                if (empty($_SESSION[$filter])) {
                    return array();
                }

                return array($_SESSION[$filter]);
            }

            return $_SESSION;

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

            $ass = self::getSession('signature');

            return $ass[0] !== 0;

        }

        /**
         * Checks if a user is logged
         * in to the system
         *
         * @return bool
         */
        public static function is_login(): bool
        {

            if (empty(self::getSession())) {
                return false;
            }

            if (empty(self::getSession('name'))) {
                return false;
            }

            return true;

        }

        /**
         * Remove Session and force login
         *
         */
        public static function logout(): void
        {
            session_destroy();
            unset($_SESSION);
        }

    }