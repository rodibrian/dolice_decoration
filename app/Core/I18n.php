<?php
declare(strict_types=1);

namespace App\Core;

final class I18n
{
    /** @var array<string, array<string, string>> */
    private static array $flat = [];

    /**
     * @param array<string, mixed> $params
     */
    public static function t(string $key, array $params = []): string
    {
        $loc = Locale::current();
        if (!isset(self::$flat[$loc])) {
            self::$flat[$loc] = self::loadFlat($loc);
        }
        $msg = self::$flat[$loc][$key] ?? self::$flat[Locale::DEFAULT][$key] ?? $key;
        foreach ($params as $k => $v) {
            $msg = str_replace(':' . $k, (string)$v, $msg);
        }

        return $msg;
    }

    /**
     * @return array<string, string>
     */
    private static function loadFlat(string $locale): array
    {
        $path = dirname(__DIR__, 1) . '/i18n/' . $locale . '.php';
        if (!is_file($path)) {
            $path = dirname(__DIR__, 1) . '/i18n/' . Locale::DEFAULT . '.php';
        }
        /** @var array<string, mixed> $tree */
        $tree = is_file($path) ? (require $path) : [];

        return self::flatten($tree);
    }

    /**
     * @param array<string, mixed> $tree
     * @return array<string, string>
     */
    private static function flatten(array $tree, string $prefix = ''): array
    {
        $out = [];
        foreach ($tree as $k => $v) {
            $key = $prefix === '' ? (string)$k : $prefix . '.' . $k;
            if (is_array($v)) {
                $out = array_merge($out, self::flatten($v, $key));
            } else {
                $out[$key] = (string)$v;
            }
        }

        return $out;
    }
}
