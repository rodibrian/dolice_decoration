<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Core\Auth;
use App\Core\AdminAudit;
use App\Models\Setting;

final class AuthController extends BaseController
{
    public function showLogin(): void
    {
        if (Auth::check()) {
            $this->redirect('/admin');
        }

        $settings = Setting::allKeyed();
        $heroCoverRaw = trim((string)($settings['hero_cover_image'] ?? ''));
        if ($heroCoverRaw === '') {
            $heroCoverRaw = '/uploads/2151892472.jpg';
        }
        $isAbsoluteCover = preg_match('#^https?://#i', $heroCoverRaw) === 1;
        $heroCoverUrl = $isAbsoluteCover
            ? $heroCoverRaw
            : (rtrim((string)(env('APP_URL', '') ?: ''), '/') . '/' . ltrim($heroCoverRaw, '/'));

        $this->view('admin.login', [
            'title' => 'Connexion admin',
            'error' => $_SESSION['flash_error'] ?? null,
            'heroCoverUrl' => $heroCoverUrl,
        ], 'layouts/admin_auth');

        unset($_SESSION['flash_error']);
    }

    public function login(): void
    {
        $email = trim((string)($_POST['email'] ?? ''));
        $password = (string)($_POST['password'] ?? '');
        $asSuperAdmin = isset($_POST['super_admin']) && (string)($_POST['super_admin']) === '1';

        if ($email === '' || $password === '') {
            $_SESSION['flash_error'] = "Email et mot de passe requis.";
            $this->redirect('/admin/login');
        }

        if (!Auth::attempt($email, $password)) {
            $_SESSION['flash_error'] = "Identifiants invalides.";
            $this->redirect('/admin/login');
        }

        if ($asSuperAdmin && !Auth::isSuperAdmin()) {
            Auth::logout();
            $_SESSION['flash_error'] = "Accès super admin requis.";
            $this->redirect('/admin/login');
        }

        AdminAudit::log('auth.login', 'user', (int)(Auth::user()['id'] ?? 0), ['as_super_admin' => $asSuperAdmin]);
        $this->redirect('/admin');
    }

    public function logout(): void
    {
        AdminAudit::log('auth.logout', 'user', (int)(Auth::user()['id'] ?? 0), null);
        Auth::logout();
        $this->redirect('/admin/login');
    }
}
