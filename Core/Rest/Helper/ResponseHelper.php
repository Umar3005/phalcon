<?php declare(strict_types=1);

namespace Core\Rest\Helper;

use Core\Request\RestApiException;
use Core\Rest\Api\Data\ResponseHelperInterface;
use Phalcon\Http\Response\StatusCode;
use Phalcon\Http\Response;
use Phalcon\Http\Response\StatusCode as Code;

class ResponseHelper implements ResponseHelperInterface
{
    /** @throws RestApiException */
    public function getResponse(array $responseData, array $meta = null): Response
    {
        $response = new Response();
        $code = $responseData[self::HEADER][self::STATUS_CODE];
        $response->setStatusCode($code, StatusCode::message($code));
        $response->setContent($this->prepareResponse($responseData, $code, $meta));

        return $response;
    }

    /** @inheritDoc */
    public function prepareResponse($response, int $code, array $meta = null): string
    {
        $responseBody = $response[self::BODY_FIELD];
        if (isset($responseBody[self::MESSAGE_FIELD])) {
            $responseBody = $this->getMessage($responseBody);

            if (isset($response[self::BODY_FIELD][self::CODE_FIELD])) {
                $this->getException($responseBody, Code::BAD_REQUEST);
            }
            $meta = null;
        }

        if ($code !== Code::OK) {
            $this->getException($responseBody, $code);
        }

        $result = [self::CODE_FIELD => $code, self::RESULT_FIELD => $responseBody];

        if ($meta) {
            $result = array_merge($result, [self::META_FIELD => $meta]);
        }

        return json_encode($result);
    }

    public function getMessage(array $result): string
    {
        $message    = $result[self::MESSAGE_FIELD] ?? [];
        $parameters = $result[self::PARAMETERS_FIELD] ?? [];

        if (!$parameters) {
            return $message;
        }
        return $this->prepareMessage($parameters, $message);
    }

    /** @inheritDoc */
    public function getException(string $error, int $code): RestApiException
    {
        $result = json_encode([self::CODE_FIELD => $code, self::RESULT_FIELD => $error]);

        throw new RestApiException($result, $code);
    }

    private function prepareMessage(array $parameters, string &$message): string
    {
        foreach ($parameters as $key => $value) {
            if (is_int($key)) {
                $key++;
            }
            if (is_object($value)) {
                continue;
            }
            $message = str_replace('%' . $key, $value, $message);
        }
        return $message;
    }
}
