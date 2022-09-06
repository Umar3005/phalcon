<?php declare(strict_types=1);

namespace Core\ModulesManager\Model\WebApi;

use Core\ModulesManager\Model\ModuleDirectoryInterface;
use Core\ModulesManager\Base\ModulesManagerAbstract;
use SimpleXMLElement;

class WebApiLoader extends ModulesManagerAbstract
{
    public function getModules(): array
    {
        return array_values(array_diff(scandir(ModuleDirectoryInterface::PROJECT_DIR), ['.', '..']));
    }

    public function __construct()
    {
        $this->pathToFile = ModuleDirectoryInterface::PATH_TO_API_XML;
    }

    public function prepareRoutesData(): array
    {
        $routesXmlData = $this->getXmlData();
        $preparedData = [];
        foreach ($routesXmlData as $routeXmlData) {
            if (!$routeXmlData) {
                continue;
            }

            foreach ($routeXmlData as $xmlData) {
                $routeName = (string) $xmlData[self::NAME_FIELD];
                $preparedData[$routeName] = $this->getFileData($xmlData);
            }
        }
        return $preparedData;
    }

    public function getFileData(SimpleXMLElement $moduleXmlData): array
    {
        return [
            self::URL_FIELD        => (string) $moduleXmlData[self::URL_FIELD],
            self::MODULE_FIELD     => $this->getModuleByNamespace((string)$moduleXmlData->service[self::CLASS_FIELD]),
            self::ACTION_FIELD     => (string) $moduleXmlData->service[self::METHOD_FIELD],
            self::METHODS_FIELD    => (string) $moduleXmlData[self::METHOD_FIELD],
            self::CONTROLLER_FIELD => (string) $moduleXmlData->service[self::CLASS_FIELD],
        ];
    }

    public function getModuleByNamespace(string $namespace)
    {
        $data = explode(self::CLASS_SEPARATOR, $namespace);
        return array_shift($data);
    }
}
