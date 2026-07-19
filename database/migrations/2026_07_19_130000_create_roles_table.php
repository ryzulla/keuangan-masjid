<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();          // dipakai di kolom users.role & role_permissions.role
            $table->string('label');                  // nama tampil
            $table->string('color', 9)->default('#586359');
            $table->string('group')->default('Lainnya'); // pengelompokan di dropdown
            $table->boolean('is_system')->default(false); // role bawaan, tak bisa dihapus
            $table->unsignedInteger('sort')->default(100);
            $table->timestamps();
        });

        // Seed role bawaan (sesuai yang sudah dipakai aplikasi).
        $now = now();
        DB::table('roles')->insert([
            ['key' => 'super_admin', 'label' => 'Super Admin', 'color' => '#B0402C', 'group' => 'Administrator', 'is_system' => true, 'sort' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'admin',       'label' => 'Admin',       'color' => '#164A40', 'group' => 'Administrator', 'is_system' => true, 'sort' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'bendahara',    'label' => 'Bendahara DKM', 'color' => '#6B5B95', 'group' => 'DKM Masjid',  'is_system' => true, 'sort' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'ketua_dkm',    'label' => 'Ketua DKM',     'color' => '#12805c', 'group' => 'DKM Masjid',  'is_system' => true, 'sort' => 4, 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'dkm',          'label' => 'DKM',           'color' => '#0d9488', 'group' => 'DKM Masjid',  'is_system' => true, 'sort' => 5, 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'perumahan',    'label' => 'Ketua RT',      'color' => '#A9741A', 'group' => 'Perumahan',   'is_system' => true, 'sort' => 6, 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'pengurus_rt',  'label' => 'Pengurus RT',   'color' => '#6B5B95', 'group' => 'Perumahan',   'is_system' => true, 'sort' => 7, 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'bendahara_rt', 'label' => 'Bendahara RT',  'color' => '#2F855A', 'group' => 'Perumahan',   'is_system' => true, 'sort' => 8, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
