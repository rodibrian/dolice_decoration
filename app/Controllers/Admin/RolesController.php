<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Core\AdminAudit;
use App\Models\RoleCapability;

final class RolesController extends BaseController
{
    /**
     * @return list<string>
     */
    private function roles(): array
    {
        return ['super_admin', 'admin'];
    }

    public function index(): void
    {
        $this->requireAdmin(['admin.super']);

        $roles = $this->roles();
        $selected = (string)($_GET['role'] ?? 'admin');
        if (!in_array($selected, $roles, true)) {
            $selected = 'admin';
        }

        $catalog = \App\Core\Auth::capabilityCatalog();
        $currentCaps = RoleCapability::forRole($selected);

        $this->view('admin.roles.index', [
            'title' => 'Rôles & autorisations',
            'roles' => $roles,
            'selectedRole' => $selected,
            'catalog' => $catalog,
            'currentCaps' => $currentCaps,
            'flash' => $_SESSION['flash_success'] ?? null,
            'error' => $_SESSION['flash_error'] ?? null,
        ], 'layouts/admin');

        unset($_SESSION['flash_success'], $_SESSION['flash_error']);
    }

    public function update(): void
    {
        $this->requireAdmin(['admin.super']);

        $role = (string)($_POST['role'] ?? '');
        $roles = $this->roles();
        if (!in_array($role, $roles, true)) {
            $_SESSION['flash_error'] = "Rôle invalide.";
            $this->redirect('/admin/roles');
        }

        $caps = $_POST['caps'] ?? [];
        if (!is_array($caps)) {
            $caps = [];
        }
        $caps = array_values(array_unique(array_map('strval', $caps)));

        // Prevent locking out super admin capability for the super_admin role
        if ($role === 'super_admin' && !in_array('admin.super', $caps, true)) {
            $caps[] = 'admin.super';
        }

        try {
            RoleCapability::setForRole($role, $caps);
            AdminAudit::log('role_capabilities.update', 'role', null, ['role' => $role, 'count' => count($caps), 'caps' => $caps]);
            $_SESSION['flash_success'] = "Autorisations mises à jour.";
        } catch (\Throwable $e) {
            $_SESSION['flash_error'] = "Impossible de sauvegarder (table role_capabilities manquante ?).";
        }

        $this->redirect('/admin/roles?role=' . urlencode($role));
    }
}

