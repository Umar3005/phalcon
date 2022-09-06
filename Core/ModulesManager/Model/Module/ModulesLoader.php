<?php declare(strict_types=1);

namespace Core\ModulesManager\Model\Module;

use Core\ModulesManager\Model\ModuleDirectoryInterface;
use Core\ModulesManager\Base\ModulesManagerAbstract;
use Exception;
use Phalcon\Http\Response\StatusCode as Code;

class ModulesLoader extends ModulesManagerAbstract
{
    public function __construct()
    {
        $this->pathToFile = ModuleDirectoryInterface::PATH_TO_MODULE_XML;
    }

    public function getModules(): array
    {
        $declaredModules = array_values(array_diff(scandir(ModuleDirectoryInterface::PROJECT_DIR), ['.', '..']));

        $modules = include BP . self::SEPARATOR . self::MODULES_FILE;

        $result = [];
        foreach ($declaredModules as $module) {
            if (!$modules[$module]) {
                continue;
            }
            $result[] = $module;
        }
        return $result;
    }

    public function prepareModulesData(): array
    {
        $modulesXmlData = $this->getXmlData();
        $preparedData = [];
        foreach ($modulesXmlData as $moduleXmlData) {
            if (!$moduleXmlData) {
                continue;
            }

            $moduleName = reset($moduleXmlData->module[self::NAME_FIELD]);

            $preparedData[$moduleName][self::NAME_FIELD] = $moduleName;

            if (!$moduleXmlData->sequence->module) {
                $preparedData[$moduleName][self::SEQUENCE_FIELD] = [];
                continue;
            }

            foreach ($moduleXmlData->sequence->module as $sequence) {
                $preparedData[$moduleName][self::SEQUENCE_FIELD][] = (string) $sequence[self::NAME_FIELD];
            }
        }

        return $preparedData;
    }

    /** @throws Exception */
    public function prepareModule(array $moduleNames): array
    {
        $result = [];

        $moduleValues = include BP . self::SEPARATOR . self::MODULES_FILE;

        foreach ($moduleNames as $moduleName) {
            if (!$moduleValues[$moduleName]) {
                throw new Exception(
                    'Module: ' . $moduleName . ' is not declared', Code::INTERNAL_SERVER_ERROR
                );
            }

            $result[$moduleName] = [
                self::PATH_FIELD =>
                    ModuleDirectoryInterface::PROJECT_DIR . $moduleName . self::SEPARATOR . self::REGISTRATION_FILE,
                self::CLASS_NAME_FIELD
                    => self::CLASS_SEPARATOR . $moduleName . self::CLASS_SEPARATOR . self::REGISTRATION_FIELD
            ];
        }

        return $result;
    }
}
