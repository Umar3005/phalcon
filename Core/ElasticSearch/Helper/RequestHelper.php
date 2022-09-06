<?php declare(strict_types=1);

namespace Core\ElasticSearch\Helper;

use Core\ElasticSearch\Api\Data\RequestBuilderInterface;
use Core\ElasticSearch\Api\Data\RequestHelperInterface;
use Phalcon\Http\Request;
use Phalcon\Http\Request\Method;
use Phalcon\Http\Response;

class RequestHelper implements RequestHelperInterface
{
    protected array $config;
    protected Response $response;
    protected RequestValidation $validation;
    protected RequestBuilderInterface $requestBuilder;

    public function __construct(
        array $config,
        Response $response,
        RequestValidation $validation,
        RequestBuilderInterface $requestBuilder
    ) {
        $this->config         = $config;
        $this->response       = $response;
        $this->validation     = $validation;
        $this->requestBuilder = $requestBuilder;
    }

    /**
     * @param string $url
     * @param string $searchable ['index', 'indexTypes']
     * @return array
     */
    public function parseUrlData(string $url, string $searchable): array
    {
        $segments = [];

        $urlSegments = explode(self::SLASH_SEPARATOR, $url);
        $parameters = $this->config[self::ELASTIC_SEARCH][$searchable];
        foreach ($urlSegments as $segment) {
            if (in_array($segment, $parameters)) {
                $segments[] = $segment;
            }
        }
        return $segments;
    }

    public function getRequestBody(Request $request, array $map = [], array $additionalParams = []): array
    {
        $requestData = $this->getDataByRequest($request);

        foreach ($map as $field => $valueField) {
            if (isset($requestData[$valueField])) {
                $requestData[$field] = $requestData[$valueField];
            }
        }

        foreach ($additionalParams as $field => $value) {
            $requestData[$field] = $value;
        }

        $url = $request->getURI(true);
        $clusters = $map[self::CLUSTER_FIELD] ?? $this->parseUrlData($url, self::INDICES_FIELD);
        $entities = $map[self::ENTITIES_FIELD] ?? $this->parseUrlData($url, self::INDEX_TYPES_FIELD);
        $requestData[self::INDEX_FIELD] = $this->requestBuilder->getIndices($clusters, $entities);

        return $requestData;
    }

    public function getDataByRequest(Request $request)
    {
        $requestData = $request->getJsonRawBody(true) ?? [];
        if ($request->getMethod() === Method::GET) {
            $requestData = $requestData->query->request;
            if ($requestData) {
                return (array) json_decode($requestData);
            }
            return [];
        }
        $requestData = array_merge($requestData, $request->get());

        if (isset($requestData[self::AGGS_FIELD])) {
            $requestData[self::BODY_FIELD][self::AGGS_FIELD] = $requestData[self::AGGS_FIELD];
        }

        if (isset($requestData[self::QUERY_FIELD])) {
            $requestData[self::BODY_FIELD][self::QUERY_FIELD] = $requestData[self::QUERY_FIELD];
        }

        return $requestData;
    }

    public function getResponse(array $result, array $headers = []): Response
    {
        $this->response->setJsonContent($result);

        foreach ($headers as $headerKey => $headerValue) {
            $this->response->setHeader($headerKey, $headerValue);
        }
        return $this->response;
    }
}
