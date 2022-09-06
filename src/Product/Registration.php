<?php declare(strict_types=1);

namespace Product;

use Core\ElasticSearch\Api\ProviderInterface;
use Core\ElasticSearch\ElasticRegistration;
use Phalcon\Di\DiInterface;
use Product\Api\MetaDataProviderInterface;
use Product\Helper\AttributeDataBuilder;
use Product\Helper\ProductTax;
use Product\Model\MetaDataProvider;

class Registration extends ElasticRegistration
{
    public function registerServices(DiInterface $container)
    {
        parent::registerServices($container);

        $container->set(ProductTax::class, function () {
            return new ProductTax();
        });

        $container->set(AttributeDataBuilder::class, function () {
            return new AttributeDataBuilder();
        });

        $container->set(MetaDataProviderInterface::class, function () use ($container) {
            $dataBuilder       = $container->get(AttributeDataBuilder::class);
            $attributeProvider = $container->get(ProviderInterface::class);

            return new MetaDataProvider($dataBuilder, $attributeProvider);
        });
    }
}
