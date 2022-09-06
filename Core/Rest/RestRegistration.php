<?php declare(strict_types=1);

namespace Core\Rest;

use Core\Rest\Api\Data\ResponseHelperInterface;
use Core\Rest\Api\Data\UrlHandlerInterface;
use Core\Rest\Api\RestClientInterface;
use Core\Rest\Handler\UrlHandler;
use Core\Rest\Helper\ResponseHelper;
use Core\Services\Cache\CacheService;
use Core\Services\Cache\CacheSystem;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Phalcon\Di\DiInterface;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Events\Manager;

class RestRegistration implements ModuleDefinitionInterface
{
    public function registerAutoloaders(DiInterface $container = null) { }

    function registerServices(DiInterface $container)
    {
        $container->set('dispatcher', function () {
            $dispatcher = new Dispatcher();
            $eventManager = new Manager();
            $dispatcher->setEventsManager($eventManager);

            return $dispatcher;
        });

        $container->set(RestClientInterface::class, function () use ($container) {
            $config = $container->get(CacheSystem::class)->get('env')->get('magentoApi')->toArray();
            return new RestClient($config);
        });

        $container->set(UrlHandlerInterface::class, function () {
            return new UrlHandler();
        });

        $container->set(ResponseHelperInterface::class, function () {
            return new ResponseHelper();
        });

        $container->set('cacheService', function () use ($container) {
            $config = $container->get(CacheSystem::class)->get('env')->get('serviceCache');
            $options = $config['options'];
            $type = $config['type'];

            return CacheService::getCache($options, $type);
        });
    }
}
