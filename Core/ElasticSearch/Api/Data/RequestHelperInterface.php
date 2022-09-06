<?php declare(strict_types=1);

namespace Core\ElasticSearch\Api\Data;

use Phalcon\Http\Request;
use Phalcon\Http\Response;

/** @TODO нужно избавиться от Request */
interface RequestHelperInterface extends MapperInterface
{
    public function parseUrlData(string $url, string $searchable): array;

    public function getRequestBody(Request $request, array $map = [], array $additionalParams = []): array;

    public function getDataByRequest(Request $request);

    public function getResponse(array $result, array $headers = []): Response;
}
