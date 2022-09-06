<?php declare(strict_types=1);

namespace Core\Rest;

use Core\Rest\Handler\UrlHandler;
use Core\Rest\Helper\ResponseHelper;
use Phalcon\Mvc\Controller as BaseController;

class Controller extends BaseController
{
    protected UrlHandler $urlHandler;
    protected ResponseHelper $responseHelper;

    public function initialize()
    {
        $this->urlHandler     = $this->di->get(UrlHandler::class);
        $this->responseHelper = $this->di->get(ResponseHelper::class);
    }
}
