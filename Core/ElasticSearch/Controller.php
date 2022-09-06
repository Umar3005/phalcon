<?php declare(strict_types=1);

namespace Core\ElasticSearch;

use Core\ElasticSearch\Api\Data\ControllerInterface;
use Core\ElasticSearch\Api\Data\RequestHelperInterface;
use Core\ElasticSearch\Api\EntityBuilderInterface;
use Core\ElasticSearch\Api\EntityInterface;
use Core\ElasticSearch\Api\ProviderInterface;
use Core\ElasticSearch\Helper\EntityBuilder;
use Core\Services\Cache\CacheService;
use Core\Services\Cache\CacheSystem;
use Phalcon\Cache\Adapter\AdapterInterface;
use Phalcon\Mvc\Controller as BaseController;

abstract class Controller extends BaseController implements ControllerInterface
{
    protected AdapterInterface $serviceCache;
    protected ProviderInterface $provider;
    protected EntityBuilder $entityBuilder;
    protected RequestHelperInterface $requestHelper;
    protected array $config;

    public function initialize()
    {
        $this->config        = $this->di->get(CacheSystem::class)->get('env')->toArray();
        $this->provider      = $this->container->get(ProviderInterface::class);
        $this->serviceCache  = $this->di->get(CacheService::class);
        $this->requestHelper = $this->di->get(RequestHelperInterface::class);
        $this->entityBuilder = $this->di->get(EntityBuilderInterface::class);
    }

    public function getQuery(array $params = [])
    {
        $body = $this->requestHelper->getRequestBody($this->request, EntityBuilderInterface::PARAMS_MAP, $params);

        /** @var $entity EntityInterface */
        $entity = $this->entityBuilder->build($body);

        return $entity->getData();
    }

    public function isCacheExist(string $hash, bool $isCacheUsing): bool
    {
        return $isCacheUsing && $this->serviceCache->has($hash);
    }

    public function setCache(array $data, array $tags)
    {
        $key = $this->getCacheKey();
        foreach ($tags as $tag) {
            $this->serviceCache->set(self::TAGS_PREFIX . $tag, $key);
        }

        $this->serviceCache->set($key, $data);
    }

    public function getTagPrefix(string $entityType): string
    {
        $parsedEntityType = explode(self::UNDERSCORE_SEPARATOR, $entityType);

        $entityTag = '';
        foreach ($parsedEntityType as $type) {
            $entityTag = strtoupper(substr($type, 0, 1));
        }

        return $entityTag;
    }

    protected function getTags(
        array $hits,
        array $tagPrefixes,
        string $additionalValue = ''
    ): array {
        $result = [];

        foreach ($hits as $hit) {
            $hitSource = $hit[self::SOURCE_FIELD];
            $this->getHitTagByFields($result, $hitSource, $tagPrefixes);
        }

        if ($additionalValue && !in_array($additionalValue, $result)) {
            $result[] = $additionalValue;
        }

        return $result;
    }

    protected function getHitTagByFields(array &$result, array $hitSource, array $tagPrefixes): array
    {
        foreach ($tagPrefixes as $key => $value) {
            $sourceValue = $hitSource[$value];
            $value = is_array($sourceValue) ? implode(self::COMA_SEPARATOR, $sourceValue) : $sourceValue;
            if ($value && !in_array($key . $value, $result)) {
                $result[] = $key . $value;
            }

        }
        return $result;
    }

    protected function getTagPrefixes(array $fields): array
    {
        $prefixes = [];
        foreach ($fields as $key => $value) {
            $prefixes[$this->getTagPrefix($key)] = $value;
        }

        return $prefixes;
    }

    protected function getCacheKey(): string
    {
        $cacheData = array_merge($this->request->getQuery(), $this->request->getJsonRawBody(true));
        $requestHash = hash(self::HASH_ALGORITHM, json_encode($cacheData) . $this->request->getURI(true));
        return self::CACHE_KEY_PREFIX . $requestHash;
    }
}
