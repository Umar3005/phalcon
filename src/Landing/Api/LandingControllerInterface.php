<?php declare(strict_types=1);

namespace Landing\Api;

use Exception;
use Phalcon\Http\Response;

interface LandingControllerInterface
{
    const HIT_SOURCE_MAP = ['landing' => 'url_path'];

    /** @throws Exception */
    public function getLandingAction(): Response;
}
