<?php declare(strict_types=1);

namespace Subscribe\Helper;

use Core\Rest\Helper\ResponseHelper;
use Core\Request\RestApiException;
use Phalcon\Http\Response\StatusCode as Code;

class RequestValidation
{
    protected ResponseHelper $responseHelper;

    public function __construct(ResponseHelper $responseHelper)
    {
        $this->responseHelper = $responseHelper;
    }

    /** @throws RestApiException */
    public function validateEmail(array $request)
    {
        if (!$request['email']) {
            $this->responseHelper->getException('sku parameter is required',Code::INTERNAL_SERVER_ERROR);
        }
    }
}
