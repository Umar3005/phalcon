<?php declare(strict_types=1);

namespace Core\ModulesManager\Model;

interface ModuleDirectoryInterface
{
    const PROJECT_DIR        = BP . '/src/';
    const PATH_TO_API_XML    = '/etc/webapi.xml';
    const PATH_TO_MODULE_XML = '/etc/module.xml';
}
