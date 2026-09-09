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
        Schema::table('potensi', function (Blueprint $table) {
            // NPWP is the business tax identifier used to deduplicate bulk imports.
            // Nullable so pre-existing records without NPWP remain valid.
            $table->string('npwp', 20)->nullable()->unique()->after('nama_usaha');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('potensi', function (Blueprint $table) {
            $table->dropUnique(['npwp']);
            $table->dropColumn('npwp');
        });
    }
};
