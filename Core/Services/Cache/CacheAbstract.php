<?php declare(strict_types=1);

namespace Core\Services\Cache;

use Phalcon\Cache\Adapter\AdapterInterface;
use Phalcon\Cache\AdapterFactory;
use Phalcon\Storage\SerializerFactory;

abstract class CacheAbstract
{
    abstract static function getCache(array $options, string $type): AdapterInterface;

    public static function prepareCache(array $options, string $type): AdapterInterface
    {
        $serializerFactory = new SerializerFactory();
        $adapterFactory    = new AdapterFactory(
            $serializerFactory,
            $options
        );

        return $adapterFactory->newInstance($type, $options);
    }
}
