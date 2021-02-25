<?php

    declare(strict_types = 1);

    use app\interfaces\AuthInterface;

    #DEFINES
    require_once 'configs/Settings.php';

    require_once sprintf("%s/autoload.php", DIR_VENDOR);

    #START SESSION
    AuthInterface::build();

    #INIT APP
    require_once sprintf("%s/Run.php", DIR_BOOT);