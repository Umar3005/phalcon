<?php declare(strict_types=1);

namespace Core\ElasticSearch\Helper;

use Core\ElasticSearch\Api\Data\MapperInterface;
use Core\ElasticSearch\Api\Data\RequestValidationInterface;
use Core\Rest\Helper\ResponseHelper;
use Core\Request\RestApiException;
use Phalcon\Http\Response\StatusCode as Code;

class RequestValidation implements RequestValidationInterface
{
    protected ResponseHelper $responseHelper;

    public function __construct(ResponseHelper $responseHelper)
    {
        $this->responseHelper = $responseHelper;
    }

    /** @throws RestApiException */
    public function validateMethod(string $method)
    {
        if (in_array($method, self::AVAILABLE_METHODS)) {
            return;
        }
        $error = 'ERROR: ' . $method . ' request method is not supported.';
        $this->responseHelper->getException($error, Code::INTERNAL_SERVER_ERROR);
    }

    /** @throws RestApiException */
    public function validateClusters(array $indices = [])
    {
        if ($indices) {
            return;
        }
        $this->responseHelper->getException(self::CLUSTER_ERROR, Code::INTERNAL_SERVER_ERROR);
    }

    /** @throws RestApiException */
    public function validateSearchField(string $url = '')
    {
        if (!strpos($url, MapperInterface::SEARCH_FIELD)) {
            return;
        }
        $this->responseHelper->getException(self::SEARCH_FILED_ERROR, Code::INTERNAL_SERVER_ERROR);
    }
}
