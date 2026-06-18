<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('status_pekerjaan', function (Blueprint $table) {
            $table->index('status_pekerjaan');
        });

        Schema::table('uploads', function (Blueprint $table) {
            $table->index('jenis_dokumen');
        });

        Schema::table('kartu_kuning', function (Blueprint $table) {
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::table('status_pekerjaan', function (Blueprint $table) {
            $table->dropIndex(['status_pekerjaan']);
        });

        Schema::table('uploads', function (Blueprint $table) {
            $table->dropIndex(['jenis_dokumen']);
        });

        Schema::table('kartu_kuning', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });
    }
};
