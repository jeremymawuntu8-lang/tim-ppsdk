<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tambah permission baru jika dibutuhkan (saat ini kita hanya pakai permission yang ada)
        // 2. Buat role pimpinan
        $pimpinan = Role::firstOrCreate(['name' => 'pimpinan', 'guard_name' => 'web']);
        $pimpinan->syncPermissions(['kelola-dokumen', 'lihat-map', 'lihat-laporan']);

        // 3. Update permission admin (hapus lihat-log)
        $admin = Role::where('name', 'admin')->where('guard_name', 'web')->first();
        if ($admin) {
            $admin->syncPermissions(['kelola-master-data', 'kelola-pengawasan', 'kelola-dokumen', 'lihat-map', 'lihat-laporan']);
        }

        // 4. Migrasi user dari role yang akan dihapus ke role baru/lain
        $usersWithOperator = User::role('operator')->get();
        foreach ($usersWithOperator as $user) {
            $user->removeRole('operator');
            $user->assignRole('admin');
        }

        $usersWithViewer = User::role('viewer')->get();
        foreach ($usersWithViewer as $user) {
            $user->removeRole('viewer');
            $user->assignRole('pimpinan');
        }

        // 5. Hapus role operator dan viewer
        $operator = Role::where('name', 'operator')->where('guard_name', 'web')->first();
        if ($operator) {
            $operator->delete();
        }

        $viewer = Role::where('name', 'viewer')->where('guard_name', 'web')->first();
        if ($viewer) {
            $viewer->delete();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $operator = Role::firstOrCreate(['name' => 'operator', 'guard_name' => 'web']);
        $operator->syncPermissions(['kelola-master-data', 'kelola-dokumen', 'lihat-map']);

        $viewer = Role::firstOrCreate(['name' => 'viewer', 'guard_name' => 'web']);
        $viewer->syncPermissions(['lihat-map', 'lihat-laporan']);

        $pimpinan = Role::where('name', 'pimpinan')->where('guard_name', 'web')->first();
        if ($pimpinan) {
            $pimpinan->delete();
        }

        $admin = Role::where('name', 'admin')->where('guard_name', 'web')->first();
        if ($admin) {
            $admin->syncPermissions(['kelola-master-data', 'kelola-pengawasan', 'kelola-dokumen', 'lihat-map', 'lihat-laporan', 'lihat-log']);
        }
    }
};

