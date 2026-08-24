<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // PENTING: Segera ubah password setelah login pertama kali di production!
        $defaultPassword = env('ADMIN_DEFAULT_PASSWORD', Str::random(16));

        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@psdkpbitung.go.id'],
            ['name' => 'Super Administrator', 'password' => Hash::make($defaultPassword), 'is_active' => true, 'jabatan' => 'Kepala Pangkalan']
        );
        $superAdmin->assignRole('super-admin');

        $admin = User::firstOrCreate(
            ['email' => 'admin@psdkpbitung.go.id'],
            ['name' => 'Administrator', 'password' => Hash::make($defaultPassword), 'is_active' => true, 'jabatan' => 'Admin Data']
        );
        $admin->assignRole('admin');

        $pengawas = User::firstOrCreate(
            ['email' => 'pengawas@psdkpbitung.go.id'],
            ['name' => 'Petugas Pengawas', 'password' => Hash::make($defaultPassword), 'is_active' => true, 'jabatan' => 'Pengawas Perikanan']
        );
        $pengawas->assignRole('pengawas');

        // Tampilkan password di console saat seeding agar admin tahu
        $this->command->info("Default password untuk semua admin: {$defaultPassword}");
        $this->command->warn("PENTING: Segera ubah password setelah login pertama kali!");
    }
}
