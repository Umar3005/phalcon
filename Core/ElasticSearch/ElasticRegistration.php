<?php declare(strict_types=1);

namespace Core\ElasticSearch;

use Core\ElasticSearch\Api\Data\HandlerInterface;
use Core\ElasticSearch\Api\Data\RequestBuilderInterface;
use Core\ElasticSearch\Api\Data\RequestHelperInterface;
use Core\ElasticSearch\Api\EntityBuilderInterface;
use Core\ElasticSearch\Api\ProviderInterface;
use Core\ElasticSearch\Helper\EntityBuilder;
use Core\ElasticSearch\Helper\RequestHelper;
use Core\ElasticSearch\Handler\ElasticSearchClientHandler;
use Core\ElasticSearch\Helper\RequestBuilder;
use Core\ElasticSearch\Helper\RequestValidation;
use Core\Rest\Helper\ResponseHelper;
use Core\Services\Cache\CacheService;
use Core\Services\Cache\CacheSystem;
use Phalcon\Di\DiInterface;
use Phalcon\Events\Manager;
use Phalcon\Http\Response;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\ModuleDefinitionInterface;

class ElasticRegistration implements ModuleDefinitionInterface
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

        $container->set(HandlerInterface::class, function () use ($container) {
            $config = $container->get(CacheSystem::class)->get('env')->get('elasticsearch')->toArray();
            return new ElasticSearchClientHandler($config);
        });

        $container->set(RequestValidation::class, function () use ($container) {
            $responseHelper = $container->get(ResponseHelper::class);
            return new RequestValidation($responseHelper);
        });

        $container->set(RequestBuilderInterface::class, function () {
            return new RequestBuilder();
        });

        $container->set(EntityBuilderInterface::class, function () {
            return new EntityBuilder();
        });

        $container->set(ProviderInterface::class, function () use ($container) {
            $elasticSearchHandler = $container->get(HandlerInterface::class);

            return new Provider($elasticSearchHandler);
        });

        $container->set(RequestHelperInterface::class, function () use ($container) {
            $config         = $container->get(CacheSystem::class)->get('env')->toArray();
            $response       = new Response();
            $validation     = $container->get(RequestValidation::class);
            $requestBuilder = $container->get(RequestBuilderInterface::class);

            return new RequestHelper($config, $response, $validation, $requestBuilder);
        });

        $container->set(CacheService::class, function () use ($container) {
            $config = $container->get(CacheSystem::class)->get('env')->get('cacheService')->toArray();
            $options = $config['options'];
            $type = $config['type'];

            return CacheService::getCache($options, $type);
        });
    }
}
