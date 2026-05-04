<?php
declare(strict_types=1);

if (!function_exists('t')) {
    /**
     * @param array<string, string|int|float> $params
     */
    function t(string $key, array $params = []): string
    {
        return \App\Core\I18n::t($key, $params);
    }
}
