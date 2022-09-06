<?php declare(strict_types=1);

\define('BP', \dirname(__DIR__) . '/..');

require_once BP . '/vendor/autoload.php';

use Core\ModulesManager\Model\WebApi\WebApiLoader;
use Core\Services\Cache\CacheSystem;
use Phalcon\Mvc\Router;

const PATTERN = '(\/\?(.*)*)';

$webApiLoader = new WebApiLoader();
$routesData = $webApiLoader->prepareRoutesData();

$router = new Router();
foreach ($routesData as $name => $route) {
    $router->add($route['url'], [
        'module' => $route['module'],
        'controller' => $route['controller'],
        'action' => $route['action'],
    ], $route['methods'] ?? null)->setName($name);
}

$globalEnv = include BP . '/globalEnv.php';
$config = include BP . '/config/' . $globalEnv['mode'] . '/env.php';

$cacheConfig = $config['cacheSystem'];
$cacheOptions = $cacheConfig['options'];
$type = $cacheConfig['type'];

$cache = CacheSystem::getCache($cacheOptions, $type);

$cache->set('router', $router);

$moduleRoutes = [];

foreach ($routesData as $routeData) {
    $variables = [];
    $url = preg_replace(PATTERN, '', $routeData['url']);

    $moduleRoutes[$url] = $routeData['module'];
}

$cache->set('moduleRoutes', $moduleRoutes);
