<?php declare(strict_types=1);

namespace Landing\Controllers;

use Landing\Api\LandingControllerInterface;
use Phalcon\Http\Response;
use Core\ElasticSearch\Controller;

class LandingController extends Controller implements LandingControllerInterface
{
    /** @inheritDoc */
    public function getLandingAction(): Response
    {
        $cacheKey = $this->getCacheKey();
        $isCacheUsing = $this->config['server']['useOutputCache'];
        $isCacheExist = $this->isCacheExist($cacheKey, $isCacheUsing);
        $query = $this->getQuery([self::TRACK_HITS_FIELD => true]);
        $result = $isCacheExist ? $this->serviceCache->get($cacheKey) : $this->provider->search($query);

        $hits = $result[self::HITS_FIELD][self::HITS_FIELD];
        $tags = $this->getTags($hits, $this->getTagPrefixes(self::HIT_SOURCE_MAP));

        if (!$isCacheExist) {
            $this->setCache($result, $tags);
        }

        $tag = implode(' ', $tags);

        return $this->requestHelper->getResponse($result, [Controller::HEADER_CACHE_FIELD => $tag]);
    }
}
