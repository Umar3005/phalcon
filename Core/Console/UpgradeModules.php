<?php declare(strict_types=1);

\define('BP', \dirname(__DIR__) . '/..');

require_once BP . '/vendor/autoload.php';

use Core\ModulesManager\Model\Module\ModulesLoader;
use Core\ModulesManager\Model\Module\ModulesSorter;

const ENABLED = 1;

$modulesManager = new ModulesLoader();
$modulesData = $modulesManager->prepareModulesData();

$modulesSorter = new ModulesSorter();
$includedModules = $modulesSorter->sortBySequence($modulesData);

$result = [];

foreach ($includedModules as $includedModule) {
    $result[$includedModule['name']] = ENABLED;
}

$excludedModules = array_diff(include BP . '/modules.php', $result);

$result = array_merge($result, $excludedModules);

file_put_contents(
    BP . '/modules.php',
    "<?php\nreturn " . var_export($result, true) . ';'
);

return $result;