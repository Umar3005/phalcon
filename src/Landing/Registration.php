<?php declare(strict_types=1);

namespace Landing;

use Core\ElasticSearch\ElasticRegistration;
use Phalcon\Di\DiInterface;

class Registration extends ElasticRegistration
{
    public function registerServices(DiInterface $container)
    {
        parent::registerServices($container);
    }
}
