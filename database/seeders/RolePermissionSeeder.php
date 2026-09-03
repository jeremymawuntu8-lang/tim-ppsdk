<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'kelola-master-data', 'kelola-pengawasan', 'kelola-dokumen',
            'lihat-map', 'lihat-laporan', 'kelola-user', 'lihat-log', 'kelola-pengaturan',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $superAdmin = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        $superAdmin->syncPermissions($permissions);

        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions(['kelola-master-data', 'kelola-pengawasan', 'kelola-dokumen', 'lihat-map', 'lihat-laporan']);

        $pengawas = Role::firstOrCreate(['name' => 'pengawas', 'guard_name' => 'web']);
        $pengawas->syncPermissions(['kelola-pengawasan', 'kelola-dokumen', 'lihat-map', 'lihat-laporan']);

        $pimpinan = Role::firstOrCreate(['name' => 'pimpinan', 'guard_name' => 'web']);
        $pimpinan->syncPermissions(['kelola-dokumen', 'lihat-map', 'lihat-laporan']);

        // Role khusus untuk akun perusahaan (login via Google OAuth)
        $perusahaan = Role::firstOrCreate(['name' => 'perusahaan', 'guard_name' => 'web']);
        // Perusahaan tidak memiliki permission internal admin, jadi tidak perlu disync permission khusus
    }
}
