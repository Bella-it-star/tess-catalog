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
        Schema::create('toi_planets', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel stars
            $table->foreignId('star_id')->constrained('stars')->onDelete('cascade');
            $table->string('toi_number'); // TOI (ex: 155.01)
            $table->integer('master_priority')->nullable();
            $table->integer('sg1b_priority')->nullable();
            $table->text('tess_notes')->nullable();
            $table->text('personal_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('toi_planets');
    }
};
