<?php declare(strict_types=1);

namespace CartCoupon\Api;

use Exception;
use Phalcon\Http\Response;

interface CartCouponControllerInterface
{
    /** @throws Exception */
    public function applyAction(): Response;

    /** @throws Exception */
    public function deleteAction(): Response;
}
