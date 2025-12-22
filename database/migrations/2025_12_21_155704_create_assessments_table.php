<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            // Kriteria sesuai tabel 3.1 proposal [cite: 389-391]
            $table->float('c1_capacity_plan');
            $table->float('c2_kedisiplinan');
            $table->float('c3_pengetahuan');
            $table->float('c4_loyalitas');
            $table->float('c5_team_work');
            $table->float('final_score')->nullable(); // Nilai akhir SMART
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};
