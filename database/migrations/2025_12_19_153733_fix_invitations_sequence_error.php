<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //fix sequence table invitations
        DB::statement("SELECT setval(pg_get_serial_sequence('invitations', 'id'), coalesce(max(id)+1, 1), false) FROM invitations");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
