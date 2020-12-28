<?php

    declare(strict_types = 1);

    require_once "Envinroments.php";

    ini_set('memory_limit', strval(CONF_MEMORYLIMIT));

    date_default_timezone_set(strval(APP_LOCALE));

    error_reporting(strval(CONF_REPORTING));

    ini_set('display_errors', strval(CONF_SAVELOGS));

    ini_set('display_startup_errors', strval(CONF_SAVELOGS));

    ini_set('session.save_path', strval(DIR_SESSIONS));

    ini_set('display_startup_errors', strval(CONF_SAVELOGS));

    ini_set('session.gc_maxlifetime', strval(CONF_TIMESESSION));