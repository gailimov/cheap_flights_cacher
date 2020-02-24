<?php

use app\{App, Worker};

error_reporting(E_ALL);

require_once 'vendor/autoload.php';

$container = (new App(require_once 'config.php'))->initDependencies();

switch ($argv[1]) {
    case 'prepare':
        $container->get(Worker::class)->prepare();
        break;

    case 'check':
        $container->get(Worker::class)->check();
        break;

    default:
        echo "Undefined command\n";
}
