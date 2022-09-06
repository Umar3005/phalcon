<?php declare(strict_types=1);

namespace Core\Services\Cache;

use Phalcon\Cache\Adapter\AdapterInterface;

class CacheService extends CacheAbstract
{
    private static AdapterInterface $serviceCache;

    static function getCache(array $options, string $type): AdapterInterface
    {
        if (!isset(self::$serviceCache)) {
            self::$serviceCache = self::prepareCache($options, $type);
        }

        return self::$serviceCache;
    }
}
