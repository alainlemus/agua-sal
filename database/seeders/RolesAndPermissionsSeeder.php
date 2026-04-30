<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // ── Crear todos los permisos ───────────────────────────────────────
        $todos = [
            // Páginas
            'view_page', 'view_any_page', 'create_page', 'update_page',
            'delete_page', 'delete_any_page', 'restore_page', 'restore_any_page',
            'replicate_page', 'reorder_page', 'force_delete_page', 'force_delete_any_page',
            // Productos
            'view_product', 'view_any_product', 'create_product', 'update_product',
            'delete_product', 'delete_any_product', 'restore_product', 'restore_any_product',
            'replicate_product', 'reorder_product', 'force_delete_product', 'force_delete_any_product',
            // Categorías
            'view_category', 'view_any_category', 'create_category', 'update_category',
            'delete_category', 'delete_any_category', 'restore_category', 'restore_any_category',
            'replicate_category', 'reorder_category', 'force_delete_category', 'force_delete_any_category',
            // Menús
            'view_menu', 'view_any_menu', 'create_menu', 'update_menu',
            'delete_menu', 'delete_any_menu', 'restore_menu', 'restore_any_menu',
            'replicate_menu', 'reorder_menu', 'force_delete_menu', 'force_delete_any_menu',
            // Campañas de reseña
            'view_review::campaign', 'view_any_review::campaign', 'create_review::campaign', 'update_review::campaign',
            'delete_review::campaign', 'delete_any_review::campaign', 'restore_review::campaign', 'restore_any_review::campaign',
            'replicate_review::campaign', 'reorder_review::campaign', 'force_delete_review::campaign', 'force_delete_any_review::campaign',
            // Submissions de reseña
            'view_review::submission', 'view_any_review::submission', 'create_review::submission', 'update_review::submission',
            'delete_review::submission', 'delete_any_review::submission', 'restore_review::submission', 'restore_any_review::submission',
            'replicate_review::submission', 'reorder_review::submission', 'force_delete_review::submission', 'force_delete_any_review::submission',
            // Mensajes de contacto
            'view_contact::submission', 'view_any_contact::submission', 'create_contact::submission', 'update_contact::submission',
            'delete_contact::submission', 'delete_any_contact::submission', 'restore_contact::submission', 'restore_any_contact::submission',
            'replicate_contact::submission', 'reorder_contact::submission', 'force_delete_contact::submission', 'force_delete_any_contact::submission',
            // Configuración del sitio
            'view_site::info', 'view_any_site::info', 'create_site::info', 'update_site::info',
            'delete_site::info', 'delete_any_site::info', 'restore_site::info', 'restore_any_site::info',
            'replicate_site::info', 'reorder_site::info', 'force_delete_site::info', 'force_delete_any_site::info',
            // Roles (Shield)
            'view_role', 'view_any_role', 'create_role', 'update_role', 'delete_role', 'delete_any_role',
            // Usuarios
            'view_user', 'view_any_user', 'create_user', 'update_user', 'delete_user', 'delete_any_user',
            // Páginas del panel
            'page_Estadisticas',
            // Widgets
            'widget_StatsOverviewWidget',
            'widget_RecentReviewsWidget',
            'widget_PendingMessagesWidget',
            'widget_VisitasTendenciaWidget',
            'widget_TopPaginasWidget',
        ];

        foreach ($todos as $permiso) {
            Permission::firstOrCreate(['name' => $permiso, 'guard_name' => 'web']);
        }

        // ── Grupos de permisos por rol ────────────────────────────────────
        $permisosContenido = [
            'view_page', 'view_any_page', 'create_page', 'update_page',
            'view_product', 'view_any_product', 'create_product', 'update_product',
            'view_category', 'view_any_category', 'create_category', 'update_category',
            'view_menu', 'view_any_menu', 'create_menu', 'update_menu',
        ];

        $permisosResenas = [
            'view_review::campaign', 'view_any_review::campaign',
            'create_review::campaign', 'update_review::campaign',
            'view_review::submission', 'view_any_review::submission',
        ];

        $permisosContacto = [
            'view_contact::submission', 'view_any_contact::submission',
            'update_contact::submission',
        ];

        $permisosStats = [
            'page_Estadisticas',
            'widget_StatsOverviewWidget',
            'widget_RecentReviewsWidget',
            'widget_PendingMessagesWidget',
            'widget_VisitasTendenciaWidget',
            'widget_TopPaginasWidget',
        ];

        $permisosUsuarios = [
            'view_user', 'view_any_user', 'create_user', 'update_user',
            'delete_user', 'delete_any_user',
        ];

        $permisosMesero = [
            'view_review::campaign', 'view_any_review::campaign',
            'view_review::submission', 'view_any_review::submission',
            'create_review::submission',
        ];

        // ── Crear roles ───────────────────────────────────────────────────

        // Super Admin: Gate::before lo deja pasar todo sin permisos individuales
        Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);

        // Editor
        $editor = Role::firstOrCreate(['name' => 'editor', 'guard_name' => 'web']);
        $editor->syncPermissions(array_merge(
            $permisosContenido,
            $permisosResenas,
            $permisosContacto,
            $permisosStats,
            $permisosUsuarios,
        ));

        // Mesero
        $mesero = Role::firstOrCreate(['name' => 'mesero', 'guard_name' => 'web']);
        $mesero->syncPermissions(array_merge(
            $permisosMesero,
            ['page_Estadisticas', 'widget_StatsOverviewWidget'],
        ));

        // Solo lectura
        $soloLectura = Role::firstOrCreate(['name' => 'solo_lectura', 'guard_name' => 'web']);
        $soloLectura->syncPermissions(array_merge(
            $permisosStats,
            ['view_any_review::submission', 'view_review::submission'],
            ['view_any_contact::submission', 'view_contact::submission'],
        ));

        // ── Asignar super_admin al usuario admin ──────────────────────────
        $admin = User::where('email', 'admin@donpapi.test')->first();
        if ($admin) {
            if (!$admin->hasRole('super_admin')) {
                $admin->assignRole('super_admin');
            }
            $admin->syncPermissions(Permission::all());
        }
    }
}
