<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Core\Auth;
use App\Core\Upload;
use App\Models\Post;

final class PostsController extends BaseController
{
    public function index(): void
    {
        $this->requireAdmin(['posts.view']);

        $this->view('admin.posts.index', [
            'title' => 'Articles',
            'posts' => Post::all(),
            'flash' => $_SESSION['flash_success'] ?? null,
            'error' => $_SESSION['flash_error'] ?? null,
        ], 'layouts/admin');

        unset($_SESSION['flash_success'], $_SESSION['flash_error']);
    }

    public function create(): void
    {
        $this->requireAdmin(['posts.create']);

        $this->view('admin.posts.form', [
            'title' => 'Nouvel article',
            'post' => null,
            'error' => $_SESSION['flash_error'] ?? null,
        ], 'layouts/admin');

        unset($_SESSION['flash_error']);
    }

    public function store(): void
    {
        $this->requireAdmin(['posts.create']);

        $title = trim((string)($_POST['title'] ?? ''));
        $slug = trim((string)($_POST['slug'] ?? ''));
        $excerpt = trim((string)($_POST['excerpt'] ?? '')) ?: null;
        $content = trim((string)($_POST['content'] ?? '')) ?: null;
        $author = trim((string)($_POST['author'] ?? '')) ?: null;
        $keywords = trim((string)($_POST['keywords'] ?? '')) ?: null;

        $status = (string)($_POST['status'] ?? 'draft');
        if (!in_array($status, ['draft', 'published'], true)) {
            $status = 'draft';
        }

        $publishedAt = trim((string)($_POST['published_at'] ?? '')) ?: null;
        if ($status === 'published' && ($publishedAt === null || $publishedAt === '')) {
            $publishedAt = date('Y-m-d H:i:s');
        }

        if ($title === '' || $slug === '') {
            $_SESSION['flash_error'] = "Titre et slug requis.";
            $this->redirect('/admin/posts/create');
        }

        $imagePath = null;
        if (isset($_FILES['featured_image']) && is_array($_FILES['featured_image'])) {
            $imagePath = Upload::storeImage($_FILES['featured_image']);
        }

        Post::create([
            'title' => $title,
            'slug' => $slug,
            'excerpt' => $excerpt,
            'content' => $content,
            'featured_image' => $imagePath,
            'author' => $author,
            'keywords' => $keywords,
            'status' => $status,
            'published_at' => $publishedAt,
        ]);

        $_SESSION['flash_success'] = "Article créé.";
        $this->redirect('/admin/posts');
    }

    public function edit(): void
    {
        $this->requireAdmin(['posts.update']);

        $id = (int)($_GET['id'] ?? 0);
        $post = $id > 0 ? Post::find($id) : null;
        if ($post === null) {
            $_SESSION['flash_error'] = "Article introuvable.";
            $this->redirect('/admin/posts');
        }

        $this->view('admin.posts.form', [
            'title' => 'Modifier article',
            'post' => $post,
            'error' => $_SESSION['flash_error'] ?? null,
        ], 'layouts/admin');

        unset($_SESSION['flash_error']);
    }

    public function update(): void
    {
        $this->requireAdmin(['posts.update']);

        $id = (int)($_POST['id'] ?? 0);
        $post = $id > 0 ? Post::find($id) : null;
        if ($post === null) {
            $_SESSION['flash_error'] = "Article introuvable.";
            $this->redirect('/admin/posts');
        }

        $title = trim((string)($_POST['title'] ?? ''));
        $slug = trim((string)($_POST['slug'] ?? ''));
        $excerpt = trim((string)($_POST['excerpt'] ?? '')) ?: null;
        $content = trim((string)($_POST['content'] ?? '')) ?: null;
        $author = trim((string)($_POST['author'] ?? '')) ?: null;
        $keywords = trim((string)($_POST['keywords'] ?? '')) ?: null;

        $status = (string)($_POST['status'] ?? 'draft');
        if (!in_array($status, ['draft', 'published'], true)) {
            $status = 'draft';
        }

        $publishedAt = trim((string)($_POST['published_at'] ?? '')) ?: null;
        if ($status === 'published' && ($publishedAt === null || $publishedAt === '')) {
            $publishedAt = (string)($post['published_at'] ?? '') ?: date('Y-m-d H:i:s');
        }
        if ($status === 'draft') {
            $publishedAt = null;
        }

        if ($title === '' || $slug === '') {
            $_SESSION['flash_error'] = "Titre et slug requis.";
            $this->redirect('/admin/posts/edit?id=' . $id);
        }

        $imagePath = (string)($post['featured_image'] ?? '');
        if (isset($_FILES['featured_image']) && is_array($_FILES['featured_image'])) {
            $newPath = Upload::storeImage($_FILES['featured_image']);
            if ($newPath !== null) {
                $imagePath = $newPath;
            }
        }

        Post::update($id, [
            'title' => $title,
            'slug' => $slug,
            'excerpt' => $excerpt,
            'content' => $content,
            'featured_image' => $imagePath !== '' ? $imagePath : null,
            'author' => $author,
            'keywords' => $keywords,
            'status' => $status,
            'published_at' => $publishedAt,
        ]);

        $_SESSION['flash_success'] = "Article mis à jour.";
        $this->redirect('/admin/posts');
    }

    public function delete(): void
    {
        $this->requireAdmin(['posts.delete']);

        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            Post::delete($id);
            $_SESSION['flash_success'] = "Article supprimé.";
        }
        $this->redirect('/admin/posts');
    }
}

