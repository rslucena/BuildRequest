<?php

require __DIR__ . '/build/kernel.php';

use Build\Application\kernel;

/**
 * Create The Application
 * The first thing we will do is create an application
 * instance to serve as the basis for all components
 */

$app = new kernel();

$app->Routes();

die();
