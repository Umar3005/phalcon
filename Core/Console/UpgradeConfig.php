<?php declare(strict_types=1);

\define('BP', \dirname(__DIR__) . '/..');

require_once BP . '/vendor/autoload.php';

use Core\Services\Cache\CacheSystem;
use Phalcon\Config\ConfigFactory;
use Phalcon\Storage\SerializerFactory;

$globalEnv = include BP . '/globalEnv.php';
$mode = $globalEnv['mode'];
$config = include BP . '/config/' . $mode . '/env.php';

$cacheConfig = $config['cacheSystem'];
$cacheOptions = $cacheConfig['options'];
$type = $cacheConfig['type'];

$cache = CacheSystem::getCache($cacheOptions, $type);

$serializerFactory = new SerializerFactory();

$factory = new ConfigFactory();
$fileName = BP . '/config/' . $mode . '/env.php';
$configInstance = $factory->newInstance('php', $fileName);

$cache->set('env', $configInstance);