<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
    
        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::statement("SELECT setval(pg_get_serial_sequence('invitations', 'id'), coalesce(max(id)+1, 1), false) FROM invitations");
        }
    }

    public function down(): void
    {
        // Kosongkan atau letak logic reverse jika perlu
    }
};
