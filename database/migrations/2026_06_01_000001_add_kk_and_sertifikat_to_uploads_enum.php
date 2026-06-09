<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys=off');

            Schema::create('uploads_v2', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->string('jenis_dokumen', 50);
                $table->string('file_path');
                $table->softDeletes();
                $table->timestamps();
            });

            DB::statement('INSERT INTO uploads_v2 SELECT * FROM uploads');
            Schema::drop('uploads');
            Schema::rename('uploads_v2', 'uploads');

            DB::statement('PRAGMA foreign_keys=on');

            return;
        }

        DB::statement("ALTER TABLE uploads MODIFY COLUMN jenis_dokumen ENUM('ktp','ijazah','foto','kk','sertifikat') NOT NULL");
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        DB::statement("ALTER TABLE uploads MODIFY COLUMN jenis_dokumen ENUM('ktp','ijazah','foto') NOT NULL");
    }
};
