<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambahan kepada ID, Name, Email yang sedia ada
            $table->string('ic_number')->nullable()->unique(); // No Kad Pengenalan
            $table->string('staff_number')->nullable()->unique(); // No Pekerja
            $table->string('section')->nullable(); // Seksyen/Unit
            $table->string('division')->nullable(); // Bahagian
            $table->string('grade')->nullable(); // Gred
            $table->enum('role', ['admin', 'staff'])->default('staff'); // Peranan
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['ic_number', 'staff_number', 'section', 'division', 'grade', 'role']);
        });
    }
};
