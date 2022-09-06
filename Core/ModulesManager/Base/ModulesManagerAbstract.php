<?php declare(strict_types=1);

namespace Core\ModulesManager\Base;

use Core\ModulesManager\Api\Data\ModulesManagerInterface;
use Core\ModulesManager\Model\ModuleDirectoryInterface;
use SimpleXMLElement;

abstract class ModulesManagerAbstract implements ModulesManagerInterface
{
    protected string $pathToFile;

    abstract function getModules(): array;

    public function getXmlData(): array
    {
        return array_map('self::getModuleConfiguration', $this->getModules());
    }

    /**
     * @param string $module
     * @return false|SimpleXMLElement
     */
    protected function getModuleConfiguration(string $module)
    {
        $pathToXml = ModuleDirectoryInterface::PROJECT_DIR . $module . $this->pathToFile;

        if (!file_exists($pathToXml)) {
            return false;
        }
        return simplexml_load_file($pathToXml);
    }
}
