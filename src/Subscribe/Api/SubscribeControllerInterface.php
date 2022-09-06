<?php declare(strict_types=1);

namespace Subscribe\Api;

use Exception;
use Phalcon\Http\Response;

interface SubscribeControllerInterface
{
    /** @throws Exception */
    public function subscribeAction(): Response;
}
