<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Post;

final class BlogController extends BaseController
{
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

        $this->view('blog.show', [
            'title' => (string)$post['title'],
            'post' => $post,
        ]);
    }
}

