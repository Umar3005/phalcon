<?php declare(strict_types=1);

namespace Core\Services\Cache;

use Phalcon\Cache\Adapter\AdapterInterface;

class CacheSystem extends CacheAbstract
{
    private static AdapterInterface $systemCache;

    static function getCache(array $options, string $type): AdapterInterface
    {
        if (!isset(self::$systemCache)) {
            self::$systemCache = self::prepareCache($options, $type);
        }

        return self::$systemCache;
    }
}
