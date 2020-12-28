<?php

    declare(strict_types = 1);

    #DEFINES
    require_once 'configs/Settings.php';

    require_once sprintf("%s/autoload.php", DIR_VENDOR);

    #START SESSION
    session_start();

    #INIT APP
    require_once sprintf("%s/Run.php", DIR_BOOT);