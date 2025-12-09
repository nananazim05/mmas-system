<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meeting_id')->constrained('meetings')->onDelete('cascade');
            
            // Jika Staf MTIB, kita simpan user_id
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            
            // Jika Peserta Luar, kita simpan nama & email manual
            $table->string('guest_name')->nullable();
            $table->string('guest_email')->nullable();
            
            $table->enum('status', ['invited', 'accepted', 'declined'])->default('invited');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};
