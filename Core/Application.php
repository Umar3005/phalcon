<?php declare(strict_types=1);

namespace Core;

require_once BP . '/vendor/autoload.php';

use Core\ModulesManager\Model\Module\ModulesLoader;
use Exception;
use Phalcon\Cache\Adapter\AdapterInterface;
use Phalcon\Di;
use Phalcon\Http\Response;
use Phalcon\Http\Response\StatusCode;
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Application as BaseApplication;
use Core\Services\Cache\CacheSystem;
use Phalcon\Loader;
use Core\ModulesManager\Model\Module\NamespacesLoader;

class Application extends BaseApplication
{
    public function main()
    {
        $response = new Response();
        try {
            $di = new FactoryDefault();
            $this->useImplicitView(false);
            $globalEnv = include BP . '/globalEnv.php';
            $mode = $globalEnv['mode'];

            $config = include BP . '/config/' . $mode . '/env.php';

            $cacheConfig = $config['cacheSystem'];
            $cacheOptions = $cacheConfig['options'];
            $type = $cacheConfig['type'];

            $cache = CacheSystem::getCache($cacheOptions, $type);

            $di->set(CacheSystem::class, function () use ($cache) {
                return $cache;
            });

            $this->registerServices($cache, $di);

            /** @var Response $response */
            $response = $this->handle($_SERVER['REQUEST_URI']);
        } catch (Exception $e) {
            $response->setStatusCode($e->getCode(), StatusCode::message($e->getCode()));
            $response->setContent($e->getMessage());
        }
        $response->setContentType('application/json');
        $response->send();
    }

    /** @throws Exception */
    protected function registerServices(AdapterInterface $cacheSystem, Di $di)
    {
        $moduleLoader = new ModulesLoader();
        $modulesData = $moduleLoader->prepareModulesData();

        if (!$cacheSystem->has('moduleRoutes')) {
            include BP . '/Core/Console/UpgradeRoutes.php';
        }
        $moduleName = $cacheSystem->get('moduleRoutes')[$_GET['_url']];

        $sequence = $modulesData[$moduleName]['sequence'];
        if (!empty($sequence)) {
            $modules = $sequence;
        }

        $modules[] = $moduleName;

        $this->registerModules($moduleLoader->prepareModule($modules));

        $namespaceLoader = new NamespacesLoader();

        if (!$cacheSystem->has('env')) {
            include BP . '/Core/Console/UpgradeConfig.php';
        }

        $globalNamespaces = $cacheSystem->get('env', [])->get('services')->toArray();
        $moduleNamespaces = $namespaceLoader->getNamespaces($modules);
        $namespaces = array_merge($globalNamespaces, $moduleNamespaces);

        $loader = new Loader();
        $loader->registerNamespaces($namespaces);
        $loader->register();

        $di->set('router', $cacheSystem->get('router', []));

        $this->setDI($di);
    }
}
