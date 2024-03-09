<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        \Illuminate\Support\Facades\Artisan::call('shield:generate --all');

        $role = \Spatie\Permission\Models\Role::create(['name' => 'developer']);
        $permissions = [
            'view_any_customer',
            'view_customer',
            'view_any_project',
            'view_project',
            'create_project',
            'view_time::entry',
            'view_any_time::entry',
            'create_time::entry',
            'update_time::entry',
            'restore_time::entry',
            'restore_any_time::entry',
            'replicate_time::entry',
            'reorder_time::entry',
            'delete_time::entry',
            'delete_any_time::entry',
            'force_delete_time::entry',
            'force_delete_any_time::entry',
            'view_any_type',
            'view_type',
        ];

        foreach ($permissions as $permission) {
            $role->givePermissionTo($permission);
        }

        $role = \Spatie\Permission\Models\Role::create(['name' => 'manager']);

        $permissions = [
            array(
                "name" => "create_customer",
            ),
            array(
                "name" => "create_project",
            ),
            array(
                "name" => "create_time::entry",
            ),
            array(
                "name" => "create_type",
            ),
            array(
                "name" => "create_user",
            ),
            array(
                "name" => "delete_any_customer",
            ),
            array(
                "name" => "delete_any_project",
            ),
            array(
                "name" => "delete_any_time::entry",
            ),
            array(
                "name" => "delete_any_type",
            ),
            array(
                "name" => "delete_any_user",
            ),
            array(
                "name" => "delete_customer",
            ),
            array(
                "name" => "delete_project",
            ),
            array(
                "name" => "delete_time::entry",
            ),
            array(
                "name" => "delete_type",
            ),
            array(
                "name" => "delete_user",
            ),
            array(
                "name" => "force_delete_any_customer",
            ),
            array(
                "name" => "force_delete_any_project",
            ),
            array(
                "name" => "force_delete_any_time::entry",
            ),
            array(
                "name" => "force_delete_any_type",
            ),
            array(
                "name" => "force_delete_any_user",
            ),
            array(
                "name" => "force_delete_customer",
            ),
            array(
                "name" => "force_delete_project",
            ),
            array(
                "name" => "force_delete_time::entry",
            ),
            array(
                "name" => "force_delete_type",
            ),
            array(
                "name" => "force_delete_user",
            ),
            array(
                "name" => "reorder_customer",
            ),
            array(
                "name" => "reorder_project",
            ),
            array(
                "name" => "reorder_time::entry",
            ),
            array(
                "name" => "reorder_type",
            ),
            array(
                "name" => "reorder_user",
            ),
            array(
                "name" => "replicate_customer",
            ),
            array(
                "name" => "replicate_project",
            ),
            array(
                "name" => "replicate_time::entry",
            ),
            array(
                "name" => "replicate_type",
            ),
            array(
                "name" => "replicate_user",
            ),
            array(
                "name" => "restore_any_customer",
            ),
            array(
                "name" => "restore_any_project",
            ),
            array(
                "name" => "restore_any_time::entry",
            ),
            array(
                "name" => "restore_any_type",
            ),
            array(
                "name" => "restore_any_user",
            ),
            array(
                "name" => "restore_customer",
            ),
            array(
                "name" => "restore_project",
            ),
            array(
                "name" => "restore_time::entry",
            ),
            array(
                "name" => "restore_type",
            ),
            array(
                "name" => "restore_user",
            ),
            array(
                "name" => "update_customer",
            ),
            array(
                "name" => "update_project",
            ),
            array(
                "name" => "update_time::entry",
            ),
            array(
                "name" => "update_type",
            ),
            array(
                "name" => "update_user",
            ),
            array(
                "name" => "view_any_customer",
            ),
            array(
                "name" => "view_any_project",
            ),
            array(
                "name" => "view_any_role",
            ),
            array(
                "name" => "view_any_time::entry",
            ),
            array(
                "name" => "view_any_type",
            ),
            array(
                "name" => "view_any_user",
            ),
            array(
                "name" => "view_customer",
            ),
            array(
                "name" => "view_project",
            ),
            array(
                "name" => "view_role",
            ),
            array(
                "name" => "view_time::entry",
            ),
            array(
                "name" => "view_type",
            ),
            array(
                "name" => "view_user",
            ),
            array(
                "name" => "import_project",
            ),
        ];

        foreach ($permissions as $permission) {
            $role->givePermissionTo($permission['name']);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
