<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('meetings', function (Blueprint $table) {
            try {
                $table->dropForeign(['organizer_id']);
            } catch (\Exception $e) {
                // ignore jika tiada foreign key
            }
            
            $table->dropColumn('organizer_id');

            // 2. Tambah column baru (String/Text)
            $table->string('organizer')->nullable()->after('venue');
        });
    }

    public function down(): void
    {
        Schema::table('meetings', function (Blueprint $table) {
            $table->dropColumn('organizer');
            $table->unsignedBigInteger('organizer_id')->nullable();
        });
    }
};