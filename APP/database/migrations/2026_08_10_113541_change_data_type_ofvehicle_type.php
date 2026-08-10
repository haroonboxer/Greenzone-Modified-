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
      DB::statement('
        ALTER TABLE vehicals
        ALTER COLUMN vehical_type TYPE integer
        USING vehicle_type::integer
    ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
           DB::statement('
            ALTER TABLE vehicles
            ALTER COLUMN vehicle_type TYPE integer
            USING vehicle_type::integer
        ');
    }
};
