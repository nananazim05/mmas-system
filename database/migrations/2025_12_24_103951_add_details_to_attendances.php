<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            
            // 1. simpan nama Guest
            if (!Schema::hasColumn('attendances', 'participant_name')) {
                
                $table->string('participant_name')->nullable()->after('user_id');
            }

            // 2. Jenis Peserta (Staf MTIB / Peserta Luar)
            if (!Schema::hasColumn('attendances', 'participant_type')) {
                $table->string('participant_type')->nullable()->after('guest_email');
            }

            // 3. Jabatan (Untuk Staf)
            if (!Schema::hasColumn('attendances', 'department')) {
                $table->string('department')->nullable()->after('participant_type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['participant_name', 'participant_type', 'department']);
        });
    }
};
