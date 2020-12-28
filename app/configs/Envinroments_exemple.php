<?php

    declare(strict_types = 1);

    #APP
    define('APP_VERSION', '0.0.1');
    define('APP_NAME', '');
    define('APP_API', '');
    define('APP_DESCRIPTION', '');

    #LOCALE
    define('APP_LOCALE', 'America/Sao_Paulo');
    define('APP_URL', (!empty($_SERVER['HTTPS']) ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'].'/');

    #PHP
    define('CONF_MEMORYLIMIT', '256M');
    define('CONF_TIMESESSION', '21600');

    #DATABASE
    define('DB_SERVE', '');
    define('DB_NAME', '');
    define('DB_USER', '');
    define('DB_PASS', '');
    define('DB_CHARSET', 'utf8mb4');


    #LOGS
    define('CONF_SAVELOGS', true);
    define('CONF_REPORTING', E_ALL);
    define('CONF_DISPLAYVERSION', true);

    #ENCRYPTION
    define('ENCRY_SALT', 32);
    define('ENCRY_AUTHKEY', "");

    #PATH
    define('PATH_ROOT', '');

    define('PATH_APP', PATH_ROOT.DIRECTORY_SEPARATOR.'app');

    #DIR
    define('DIR_BOOT', PATH_APP.DIRECTORY_SEPARATOR.'bootstrap');
    define('DIR_SESSIONS', PATH_APP.DIRECTORY_SEPARATOR.'sessions');
    define('DIR_LOGS', PATH_APP.DIRECTORY_SEPARATOR.'logs');
    define('DIR_CONFIG', PATH_APP.DIRECTORY_SEPARATOR.'configs');
    define('DIR_CONTROLLER', PATH_APP.DIRECTORY_SEPARATOR.'controllers');
    define('DIR_PUBLIC', PATH_APP.DIRECTORY_SEPARATOR.'public');
    define('DIR_PROVIDER', PATH_APP.DIRECTORY_SEPARATOR.'providers');
    define('DIR_VIEW', PATH_APP.DIRECTORY_SEPARATOR.'view');
    define('DIR_VENDOR', PATH_ROOT.DIRECTORY_SEPARATOR.'vendor');