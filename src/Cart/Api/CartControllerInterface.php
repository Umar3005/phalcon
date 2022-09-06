<?php declare(strict_types=1);

namespace Cart\Api;

use Exception;
use Phalcon\Http\Response;

interface CartControllerInterface
{
    const ITEM_ID_FIELD   = 'item_id';
    const CART_ITEM_FIELD = 'cartItem';

    /** @throws Exception */
    public function createAction(): Response;

    /** @throws Exception */
    public function pullAction(): Response;

    /** @throws Exception */
    public function updateAction(): Response;

    /** @throws Exception */
    public function deleteAction(): Response;

    /** @throws Exception */
    public function getPaymentMethodsAction(): Response;
}
