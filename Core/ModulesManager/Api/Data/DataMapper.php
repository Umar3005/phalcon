<?php declare(strict_types=1);

namespace Core\ModulesManager\Api\Data;

interface DataMapper
{
    const SEPARATOR         = '/';
    const MODULES_FILE      = 'modules.php';
    const CLASS_SEPARATOR   = '\\';
    const REGISTRATION_FILE = 'Registration.php';

    const URL_FIELD          = 'url';
    const PATH_FIELD         = 'path';
    const NAME_FIELD         = 'name';
    const CLASS_FIELD        = 'class';
    const ACTION_FIELD       = 'action';
    const MODULE_FIELD       = 'module';
    const METHOD_FIELD       = 'method';
    const METHODS_FIELD      = 'methods';
    const SEQUENCE_FIELD     = 'sequence';
    const CONTROLLER_FIELD   = 'controller';
    const CLASS_NAME_FIELD   = 'className';
    const REGISTRATION_FIELD = 'Registration';
    const SEQUENCE_SET_FIELD = 'sequence_set';
}
