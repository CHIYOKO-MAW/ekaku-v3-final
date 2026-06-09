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
        Schema::table('biodata', function (Blueprint $table) {
            $table->index('status_verifikasi');
        });
    }

    public function down(): void
    {
        Schema::table('biodata', function (Blueprint $table) {
            $table->dropIndex(['status_verifikasi']);
        });
    }
};
