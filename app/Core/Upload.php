<?php
declare(strict_types=1);

namespace App\Core;

final class Upload
{
    /**
     * @param array<string, mixed> $file One entry of $_FILES
     * @return string|null Public path (relative to APP_URL) like /uploads/xxx.jpg
     */
    public static function storeImage(array $file): ?string
    {
        $tmp = (string)($file['tmp_name'] ?? '');
        $err = (int)($file['error'] ?? UPLOAD_ERR_NO_FILE);
        $name = (string)($file['name'] ?? '');

        if ($err === UPLOAD_ERR_NO_FILE || $tmp === '' || !is_uploaded_file($tmp)) {
            return null;
        }
        if ($err !== UPLOAD_ERR_OK) {
            return null;
        }

        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        if ($ext === '' || !in_array($ext, $allowed, true)) {
            return null;
        }

        $destDir = PUBLIC_PATH . '/uploads';
        if (!is_dir($destDir)) {
            @mkdir($destDir, 0775, true);
        }

        $safeName = bin2hex(random_bytes(8)) . '.' . $ext;
        $dest = $destDir . '/' . $safeName;
        if (!move_uploaded_file($tmp, $dest)) {
            return null;
        }

        return '/uploads/' . $safeName;
    }

    /**
     * @param array<string, mixed> $file One entry of $_FILES
     * @return array{path:string,type:'image'|'video'}|null
     */
    public static function storeHeroMedia(array $file): ?array
    {
        $tmp = (string)($file['tmp_name'] ?? '');
        $err = (int)($file['error'] ?? UPLOAD_ERR_NO_FILE);
        $name = (string)($file['name'] ?? '');

        if ($err === UPLOAD_ERR_NO_FILE || $tmp === '' || !is_uploaded_file($tmp)) {
            return null;
        }
        if ($err !== UPLOAD_ERR_OK) {
            return null;
        }

        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        $images = ['jpg', 'jpeg', 'png', 'webp'];
        $videos = ['mp4', 'webm'];

        $type = null;
        if (in_array($ext, $images, true)) {
            $type = 'image';
        } elseif (in_array($ext, $videos, true)) {
            $type = 'video';
        }
        if ($type === null) {
            return null;
        }

        $destDir = PUBLIC_PATH . '/uploads';
        if (!is_dir($destDir)) {
            @mkdir($destDir, 0775, true);
        }

        $safeName = bin2hex(random_bytes(8)) . '.' . $ext;
        $dest = $destDir . '/' . $safeName;
        if (!move_uploaded_file($tmp, $dest)) {
            return null;
        }

        return ['path' => '/uploads/' . $safeName, 'type' => $type];
    }
}

