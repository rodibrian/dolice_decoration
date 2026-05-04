<?php
declare(strict_types=1);

namespace App\Core;

final class Locale
{
    public const DEFAULT = 'fr';

    /** @return list<string> */
    public static function allowed(): array
    {
        return ['fr', 'en', 'mg'];
    }

    public static function normalize(?string $code): string
    {
        $code = strtolower(trim((string)$code));
        if ($code === '') {
            return self::DEFAULT;
        }

        return in_array($code, self::allowed(), true) ? $code : self::DEFAULT;
    }

    /**
     * Lit ?lang=, met à jour la session, définit la langue courante.
     */
    public static function bootstrap(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            return;
        }
        if (isset($_GET['lang'])) {
            $_SESSION['locale'] = self::normalize((string)$_GET['lang']);
        }
        if (!isset($_SESSION['locale']) || !in_array((string)$_SESSION['locale'], self::allowed(), true)) {
            $_SESSION['locale'] = self::DEFAULT;
        }
    }

    public static function current(): string
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            return self::DEFAULT;
        }

        return self::normalize($_SESSION['locale'] ?? self::DEFAULT);
    }

    /**
     * URL courante avec paramètre lang (conserve les autres query params).
     */
    public static function switchHref(string $to): string
    {
        $to = self::normalize($to);
        $uri = (string)($_SERVER['REQUEST_URI'] ?? '/');
        $path = (string)(parse_url($uri, PHP_URL_PATH) ?: '/');
        $q = (string)(parse_url($uri, PHP_URL_QUERY) ?? '');
        $params = [];
        if ($q !== '') {
            parse_str($q, $params);
        }
        $params['lang'] = $to;
        $qs = http_build_query($params);

        return $path . ($qs !== '' ? '?' . $qs : '');
    }
}
