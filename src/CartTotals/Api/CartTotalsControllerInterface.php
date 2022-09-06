<?php declare(strict_types=1);

namespace CartTotals\Api;

use Exception;
use Phalcon\Http\Response;

interface CartTotalsControllerInterface
{
    /** @throws Exception */
    public function createAction(): Response;
}
