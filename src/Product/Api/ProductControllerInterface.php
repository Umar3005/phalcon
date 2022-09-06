<?php declare(strict_types=1);

namespace Product\Api;

use Exception;
use Phalcon\Http\Response;

interface ProductControllerInterface
{
    const HIT_SOURCE_MAP = [
        'product'  => 'id',
        'category' => 'category_ids'
    ];

    const AGGREGATIONS_FIELD       = 'aggregations';
    const ATTRIBUTE_METADATA_FIELD = 'attribute_metadata';

    /** @throws Exception */
    public function getProductAction(): Response;
}
