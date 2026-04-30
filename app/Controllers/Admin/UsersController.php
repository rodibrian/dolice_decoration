<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AuditLog;
use App\Models\User;

final class UsersController extends BaseController
{
    public function index(): void
    {
        $this->requireAdmin(['admin.super']);

        $this->view('admin.users.index', [
            'title' => 'Utilisateurs',
            'users' => User::all(),
            'flash' => $_SESSION['flash_success'] ?? null,
            'error' => $_SESSION['flash_error'] ?? null,
        ], 'layouts/admin');

        unset($_SESSION['flash_success'], $_SESSION['flash_error']);
    }

    public function create(): void
    {
        $this->requireAdmin(['admin.super']);

        $this->view('admin.users.form', [
            'title' => 'Nouvel utilisateur',
            'userRow' => null,
            'error' => $_SESSION['flash_error'] ?? null,
        ], 'layouts/admin');

        unset($_SESSION['flash_error']);
    }

    public function store(): void
    {
        $this->requireAdmin(['admin.super']);

        $name = trim((string)($_POST['name'] ?? ''));
        $email = trim((string)($_POST['email'] ?? ''));
        $role = (string)($_POST['role'] ?? 'admin');
        $password = (string)($_POST['password'] ?? '');

        $allowedRoles = ['super_admin', 'admin'];
        if (!in_array($role, $allowedRoles, true)) {
            $role = 'admin';
        }
        if ($name === '' || $email === '' || $password === '') {
            $_SESSION['flash_error'] = "Nom, email et mot de passe requis.";
            $this->redirect('/admin/users/create');
        }

        try {
            $id = User::create([
                'name' => $name,
                'email' => $email,
                'role' => $role,
                'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            ]);
            AuditLog::add('user.create', 'user', $id, ['email' => $email, 'role' => $role]);
            $_SESSION['flash_success'] = "Utilisateur créé.";
        } catch (\Throwable $e) {
            $_SESSION['flash_error'] = "Impossible de créer l'utilisateur (vérifie la DB/role).";
            $this->redirect('/admin/users/create');
        }

        $this->redirect('/admin/users');
    }

    public function edit(): void
    {
        $this->requireAdmin(['admin.super']);

        $id = (int)($_GET['id'] ?? 0);
        $userRow = $id > 0 ? User::find($id) : null;
        if ($userRow === null) {
            $_SESSION['flash_error'] = "Utilisateur introuvable.";
            $this->redirect('/admin/users');
        }

        $this->view('admin.users.form', [
            'title' => 'Modifier utilisateur',
            'userRow' => $userRow,
            'error' => $_SESSION['flash_error'] ?? null,
        ], 'layouts/admin');

        unset($_SESSION['flash_error']);
    }

    public function update(): void
    {
        $this->requireAdmin(['admin.super']);

        $id = (int)($_POST['id'] ?? 0);
        $userRow = $id > 0 ? User::find($id) : null;
        if ($userRow === null) {
            $_SESSION['flash_error'] = "Utilisateur introuvable.";
            $this->redirect('/admin/users');
        }

        $name = trim((string)($_POST['name'] ?? ''));
        $email = trim((string)($_POST['email'] ?? ''));
        $role = (string)($_POST['role'] ?? 'admin');
        $password = trim((string)($_POST['password'] ?? ''));

        $allowedRoles = ['super_admin', 'admin'];
        if (!in_array($role, $allowedRoles, true)) {
            $role = 'admin';
        }
        if ($name === '' || $email === '') {
            $_SESSION['flash_error'] = "Nom et email requis.";
            $this->redirect('/admin/users/edit?id=' . $id);
        }

        try {
            User::update($id, [
                'name' => $name,
                'email' => $email,
                'role' => $role,
            ]);
            if ($password !== '') {
                User::updatePassword($id, password_hash($password, PASSWORD_DEFAULT));
            }
            AuditLog::add('user.update', 'user', $id, ['email' => $email, 'role' => $role, 'pw_changed' => ($password !== '')]);
            $_SESSION['flash_success'] = "Utilisateur mis à jour.";
        } catch (\Throwable $e) {
            $_SESSION['flash_error'] = "Impossible de mettre à jour l'utilisateur.";
            $this->redirect('/admin/users/edit?id=' . $id);
        }

        $this->redirect('/admin/users');
    }

    public function delete(): void
    {
        $this->requireAdmin(['admin.super']);

        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            $this->redirect('/admin/users');
        }

        // Avoid self-delete
        $me = \App\Core\Auth::user();
        $meId = is_array($me) ? (int)($me['id'] ?? 0) : 0;
        if ($meId === $id) {
            $_SESSION['flash_error'] = "Tu ne peux pas supprimer ton propre compte.";
            $this->redirect('/admin/users');
        }

        User::delete($id);
        AuditLog::add('user.delete', 'user', $id, null);
        $_SESSION['flash_success'] = "Utilisateur supprimé.";
        $this->redirect('/admin/users');
    }
}

