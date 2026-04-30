<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Setting;

final class EmailJs
{
    private static function enabled(): bool
    {
        return (string)(Setting::get('emailjs_enabled', '') ?? '') === '1';
    }

    /**
     * @param array<string, mixed> $templateParams
     */
    public static function send(string $templateId, array $templateParams): bool
    {
        if (!self::enabled()) {
            return false;
        }

        $serviceId = trim((string)(Setting::get('emailjs_service_id', '') ?? ''));
        $publicKey = trim((string)(Setting::get('emailjs_public_key', '') ?? ''));
        $privateKey = trim((string)(Setting::get('emailjs_private_key', '') ?? ''));
        if ($serviceId === '' || $publicKey === '' || $templateId === '') {
            return false;
        }

        $payload = [
            'service_id' => $serviceId,
            'template_id' => $templateId,
            'user_id' => $publicKey, // EmailJS calls this user_id, it's the Public Key
            'template_params' => $templateParams,
        ];
        if ($privateKey !== '') {
            // Required when EmailJS "strict mode" is enabled.
            $payload['accessToken'] = $privateKey;
        }

        $json = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($json === false) {
            return false;
        }

        $res = self::postJson('https://api.emailjs.com/api/v1.0/email/send', $json);
        return $res['ok'];
    }

    /**
     * @param array<string, mixed> $params
     */
    public static function notifyQuote(array $params): bool
    {
        $tpl = trim((string)(Setting::get('emailjs_template_quote', '') ?? ''));
        return self::send($tpl, $params);
    }

    /**
     * @param array<string, mixed> $params
     */
    public static function notifyContact(array $params): bool
    {
        $tpl = trim((string)(Setting::get('emailjs_template_contact', '') ?? ''));
        return self::send($tpl, $params);
    }

    /**
     * @return array{ok:bool,status:int,body:string}
     */
    private static function postJson(string $url, string $json): array
    {
        $headers = [
            'Content-Type: application/json',
            'Accept: application/json',
        ];

        // Prefer curl if available (common on XAMPP).
        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            if ($ch === false) {
                return ['ok' => false, 'status' => 0, 'body' => 'curl_init_failed'];
            }
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 12);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 6);
            $body = (string)curl_exec($ch);
            $status = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            return ['ok' => ($status >= 200 && $status < 300), 'status' => $status, 'body' => $body];
        }

        $ctx = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => implode("\r\n", $headers),
                'content' => $json,
                'timeout' => 12,
            ],
        ]);
        $body = @file_get_contents($url, false, $ctx);
        $status = 0;
        if (isset($http_response_header) && is_array($http_response_header)) {
            foreach ($http_response_header as $h) {
                if (preg_match('#^HTTP/\S+\s+(\d{3})#', (string)$h, $m)) {
                    $status = (int)$m[1];
                    break;
                }
            }
        }
        return ['ok' => ($status >= 200 && $status < 300), 'status' => $status, 'body' => (string)($body ?? '')];
    }
}

