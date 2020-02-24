<?php

use app\{App, Worker};

error_reporting(E_ALL);

$startTime = date('H:i:s');
$startMicroTime = microtime(true);

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

echo 'Done' . PHP_EOL;
echo 'Started at ' . $startTime . ', finished at ' . date('H:i:s') . PHP_EOL;
echo 'Execution time is: ' . round((microtime(true) - $startMicroTime)) . ' sec' . PHP_EOL;
echo 'Peak memory is: ' . round(memory_get_peak_usage() / (1024 * 1024), 2) . 'MB' . PHP_EOL;
