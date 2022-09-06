<?php declare(strict_types=1);

namespace Core\Rest\Api\Data;

use Core\Request\RestApiException;

interface ResponseHelperInterface
{
    const HEADER           = 'header';
    const BODY_FIELD       = 'body';
    const CODE_FIELD       = 'code';
    const META_FIELD       = 'meta';
    const STATUS_CODE      = 'statusCode';
    const RESULT_FIELD     = 'result';
    const MESSAGE_FIELD    = 'message';
    const PARAMETERS_FIELD = 'parameters';

    /** @throws RestApiException */
    public function prepareResponse($response, int $code, array $meta = null): string;

    public function getMessage(array $result): string;

    /** @throws RestApiException */
    public function getException(string $error, int $code): RestApiException;
}
