<?php declare(strict_types=1);

namespace Core\ModulesManager\Model\Module;

use Core\ModulesManager\Model\ModuleDirectoryInterface;

class NamespacesLoader
{
    public function getNamespaces(array $modules): array
    {
        $namespaces = [];
        foreach ($modules as $module) {
            $values = array_diff(scandir(ModuleDirectoryInterface::PROJECT_DIR . $module), ['.', '..']);

            foreach ($values as $value) {
                $namespaces[$module . '\\' . $value] = '../src/' . $module . '/' . $value;
            }
        }

        return $namespaces;
    }
}
