<?php

use App\Kernel;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return static function (array $context) {

    $appEnv = $context['APP_ENV'] ?? null;
    if (!is_string($appEnv) || "" === ($appEnv = trim($appEnv))) {
        throw new \RuntimeException("APP_ENV environment variable is not set or is empty.");
    }

    return new Kernel($appEnv, (bool) $context['APP_DEBUG']);
};
