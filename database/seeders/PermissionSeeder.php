<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    private $permissions = [
        'access-delegation-desk',
        'access-isp-module',
        'access-list-search',
        'access-my-desk',
        'access-my-list',
        'application-add',
        'application-edit',
        'application-list',
        'application-view',
        'filter-office-ids',
        'permission-create',
        'permission-delete',
        'permission-update',
        'permission-view',
        'process-history',
        'process-lists-view-all',
        'process-lists-view-by-id',
        'process-map',
        'role-create',
        'role-delete',
        'role-update',
        'role-view',
        'user-create',
        'user-delete',
        'user-update',
        'user-view',
        'verification-process-data',
        'view-batch-process-panel',
        'super-admin',
        'dashboard',
        'process-management',
        'process-type-view',
        'process-type-create',
        'process-type-update',
        'process-type-delete',
        'process-status-view',
        'process-status-create',
        'process-status-update',
        'process-status-delete',
        'process-user-desk-view',
        'process-user-desk-create',
        'process-user-desk-update',
        'process-user-desk-delete',
        'process-path-view',
        'process-path-create',
        'process-path-update',
        'process-path-delete',
        'user-management',
        'settings',
        'access-draft-application-list'
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->permissions as $permission) {
            Permission::create([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }
    }
}
