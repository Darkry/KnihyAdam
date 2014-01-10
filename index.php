<?php

// Uncomment this line if you must temporarily take down your site for maintenance.
// require '.maintenance.php';

define('WWW_DIR', dirname(__FILE__));

// Let bootstrap create Dependency Injection container.
$container = require __DIR__ . '/app/bootstrap.php';



// Run application.
$container->application->run();
