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
        Schema::create('potensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('tanggal_input');
            $table->string('nama_usaha');
            $table->enum('segmen', ['PU', 'BPU', 'Jakon']);
            $table->text('uraian');
            $table->text('alamat');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->integer('estimasi_tk');
            $table->decimal('estimasi_upah', 15, 2);
            $table->decimal('estimasi_iuran', 15, 2);
            $table->boolean('status_sp1')->default(false);
            $table->date('tanggal_cetak_sp1')->nullable();
            $table->string('status_tindak_lanjut')->default('Belum dihubungi');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        Schema::create('program_potensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('potensi_id')->constrained('potensi')->onDelete('cascade');
            $table->string('jenis_program');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_potensi');
        Schema::dropIfExists('potensi');
    }
};
