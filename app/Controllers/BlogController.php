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
            $img = trim((string)($post['featured_image'] ?? ''));
            $imgUrl = '';
            if ($img !== '') {
                $imgUrl = (preg_match('#^https?://#i', $img) === 1) ? $img : ($base . '/' . ltrim($img, '/'));
            }

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
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            return;
        }

        $this->view('blog.show', [
            'title' => (string)$post['title'],
            'post' => $post,
        ]);
    }
}

