<?php declare(strict_types=1);

namespace Core\ModulesManager\Api\Data;

interface ModulesManagerInterface extends DataMapper
{
    public function getXmlData(): array;
}
