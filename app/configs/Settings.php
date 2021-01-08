<?php

    declare(strict_types = 1);

    require_once "Envinroments.php";

    ini_set('memory_limit', CONF_MEMORYLIMIT);

    date_default_timezone_set(APP_LOCALE);

    error_reporting(CONF_REPORTING);

    ini_set('display_errors', (string)CONF_SAVELOGS);

    ini_set('display_startup_errors', (string)CONF_SAVELOGS);

    ini_set('session.save_path', DIR_SESSIONS);

    ini_set('display_startup_errors', (string)CONF_SAVELOGS);

    ini_set('session.gc_maxlifetime', CONF_TIMESESSION);