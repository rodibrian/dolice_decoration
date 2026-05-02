<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Post;

final class BlogController extends BaseController
{
    /**
     * @return string
     */
    private function appUrlBase(): string
    {
        $base = (string)(env('APP_URL', '') ?: '');
        return rtrim($base, '/');
    }

    public function index(): void
    {
        $this->view('blog.index', [
            'title' => 'Blog',
            'posts' => Post::published(),
        ]);
    }

    public function show(): void
    {
        $slug = trim((string)($_GET['slug'] ?? ''));
        $post = $slug !== '' ? Post::findBySlug($slug) : null;
        if ($post === null || (string)($post['status'] ?? 'draft') !== 'published') {
            http_response_code(404);
            echo '404';
            return;
        }

        $isModal = isset($_GET['modal']) && (string)$_GET['modal'] === '1';
        if ($isModal) {
            $base = $this->appUrlBase();
            $images = $this->collectPostModalImages($base, $post);
            $imgUrl = $images[0] ?? '';

            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'post' => [
                    'title' => (string)($post['title'] ?? ''),
                    'slug' => (string)($post['slug'] ?? ''),
                    'excerpt' => (string)($post['excerpt'] ?? ''),
                    'content' => (string)($post['content'] ?? ''),
                    'author' => (string)($post['author'] ?? ''),
                    'published_at' => (string)($post['published_at'] ?? ''),
                    'image' => $imgUrl,
                ],
                'images' => $images,
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            return;
        }

        $this->view('blog.show', [
            'title' => (string)$post['title'],
            'post' => $post,
        ]);
    }

    /**
     * @param array<string, mixed> $post
     * @return list<string>
     */
    private function collectPostModalImages(string $base, array $post): array
    {
        $out = [];
        $seen = [];

        $push = function (string $url) use (&$out, &$seen): void {
            if ($url === '') {
                return;
            }
            $key = strtolower($url);
            if (isset($seen[$key])) {
                return;
            }
            $seen[$key] = true;
            $out[] = $url;
        };

        $feat = trim((string)($post['featured_image'] ?? ''));
        if ($feat !== '') {
            $url = (preg_match('#^https?://#i', $feat) === 1)
                ? $feat
                : ($base . '/' . ltrim($feat, '/'));
            $push($url);
        }

        $html = (string)($post['content'] ?? '');
        if ($html !== '' && preg_match_all('#<img[^>]+\\bsrc\\s*=\\s*("|\')([^"\'>]+)#i', $html, $matches, PREG_SET_ORDER) > 0) {
            foreach ($matches as $row) {
                $raw = (string)($row[2] ?? '');
                $norm = $this->normalizePostModalImageUrl($base, $raw);
                if ($norm !== '') {
                    $push($norm);
                }
            }
        }

        return $out;
    }

    private function normalizePostModalImageUrl(string $base, string $src): string
    {
        $src = trim(html_entity_decode($src, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        if ($src === '') {
            return '';
        }
        if (preg_match('#^(javascript|data|vbscript):#i', $src) === 1) {
            return '';
        }
        if (preg_match('#^https?://#i', $src) === 1) {
            return $src;
        }

        return $base . '/' . ltrim($src, '/');
    }
}

