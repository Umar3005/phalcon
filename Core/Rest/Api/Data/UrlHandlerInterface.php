<?php declare(strict_types=1);

namespace Core\Rest\Api\Data;

interface UrlHandlerInterface
{
    const REGEX_PATTERN = '(\{[а-яА-Яa-zA-Z_\s]+\})';

    public function getUrl($urlPaths, array $urlData = [], string $role = null): string;
}
