<?php

    declare(strict_types = 1);

    use app\bootstrap\Builder;

    require "Builder.php";

    #LOAD RENDER
    $framework = new Builder();

    #CREATE AND PRINT PAGE
    $framework->loadPage();

    die();