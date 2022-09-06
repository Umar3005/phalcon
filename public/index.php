<?php declare(strict_types=1);

\define('BP', \dirname(__DIR__));

require_once BP . '/Core/Application.php';

use Core\Application;
try {
    $app = new Application();
    $app->main();
} catch (\Exception $e) {
    echo $e->getMessage();
}
