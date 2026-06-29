<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::table('users')->where('email', 'admin@ekaku.test')->exists()) {
            return;
        }

        DB::table('users')->insert([
            ['name' => 'Demo User', 'email' => 'user@ekaku.test', 'role' => 'user', 'email_verified_at' => now(), 'password' => Hash::make('password'), 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Demo Admin', 'email' => 'admin@ekaku.test', 'role' => 'admin', 'email_verified_at' => now(), 'password' => Hash::make('password'), 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Demo Atasan', 'email' => 'atasan@ekaku.test', 'role' => 'atasan', 'email_verified_at' => now(), 'password' => Hash::make('password'), 'created_at' => now(), 'updated_at' => now()],
        ]);

        $userId = DB::table('users')->where('email', 'user@ekaku.test')->value('id');
        if ($userId) {
            DB::table('biodata')->insert([
                'user_id' => $userId,
                'nik' => '3601000000000001',
                'tempat_lahir' => 'Pandeglang',
                'tanggal_lahir' => '2000-01-01',
                'alamat' => 'Jl. Pandeglang',
                'pendidikan' => 'SMA/SMK',
                'keahlian' => 'Administrasi Perkantoran',
                'status_verifikasi' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
};
