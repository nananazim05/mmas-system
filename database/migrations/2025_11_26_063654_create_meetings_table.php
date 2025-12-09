<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organizer_id')->constrained('users')->onDelete('cascade'); // Penganjur
            $table->string('title'); // Tajuk Aktiviti
            $table->date('date'); // Tarikh
            $table->time('start_time'); // Masa Mula
            $table->time('end_time')->nullable(); // Masa Tamat
            $table->string('venue'); // Tempat
            $table->string('activity_type')->nullable(); // Jenis Aktiviti
            $table->string('qr_code_string')->unique()->nullable(); // Kod unik untuk QR
            $table->enum('status', ['upcoming', 'ongoing', 'completed', 'cancelled'])->default('upcoming');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};
