<?php declare(strict_types=1);

namespace Core\Rest\Handler;

use Core\Rest\Api\Data\ConsumerInterface;
use Core\Rest\Api\Data\UrlHandlerInterface;
use Core\Rest\Provider\UserRoleProvider;

class UrlHandler implements UrlHandlerInterface
{
    protected UserRoleProvider $userRoleProvider;

    public function __construct()
    {
        $this->userRoleProvider = new UserRoleProvider();
    }

    public function getUrl($urlPaths, array $urlData = [], string $role = null): string
    {
        if (is_string($urlPaths)) {
            return $this->prepareUrl($urlData, $urlPaths);
        }

        $role = $role ?? $this->userRoleProvider->getUserRole($urlData[ConsumerInterface::TOKEN_FIELD]);
        return $this->prepareUrl($urlData, $urlPaths[$role]);
    }

    protected function prepareUrl(array $data, string $url): string
    {
        $variables = [];
        preg_match_all(self::REGEX_PATTERN, $url, $variables);
        $variables = reset($variables);
        $variables = str_replace(['{', '}'], '', $variables);

        foreach ($variables as $variable) {
            if ($data[$variable]) {
                $url = str_replace('{' . $variable . '}', rawurlencode((string) $data[$variable]), $url);
            }
        }

        return $url;
    }
}
