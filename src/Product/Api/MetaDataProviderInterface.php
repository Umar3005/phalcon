<?php declare(strict_types=1);

namespace Product\Api;

use Core\ElasticSearch\Api\Data\MapperInterface;
use Exception;

interface MetaDataProviderInterface extends MapperInterface
{
    const SOURCE_FIELD = '_source';
    const OPTIONS_FIELD = 'options';

    const METADATA_FIELDS = [
        'id',
        'slug',
        'options',
        'is_visible',
        'attribute_id',
        'is_comparable',
        'entity_type_id',
        'attribute_code',
        'is_user_defined',
        'is_visible_on_front',
        'default_frontend_label',
    ];

    /** @throws Exception */
    public function getMetaData(array $attributesList): array;
}
