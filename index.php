<?php

/**
 *   Copyright 2020 Rodrigo Lucena
 *
 *   Licensed under the Apache License, Version 2.0 (the "License");
 *   you may not use this file except in compliance with the License.
 *   You may obtain a copy of the License at
 *
 *       http://www.apache.org/licenses/LICENSE-2.0
 *
 *   Unless required by applicable law or agreed to in writing, software
 *   distributed under the License is distributed on an "AS IS" BASIS,
 *   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *   See the License for the specific language governing permissions and
 *   limitations under the License.
 */

//--------
require __DIR__ . '/build/kernel.php';

use Build\Application\kernel;

/**
 * Create The Application
 * The first thing we will do is create an application
 * instance to serve as the basis for all components
 */
$app = new kernel();

die();
