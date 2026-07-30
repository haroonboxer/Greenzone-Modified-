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
        Schema::create('vehicals', function (Blueprint $table) {
            $table->id();
            $table->string('vehical_type');
            $table->string('vehical_ownership');
            $table->string('vehical_platte_no');
            $table->string('vehical_color');
            $table->string('engine_no');
            $table->string('shasi_no');
            $table->string('license_start_date');
            $table->string('license_end_date');
            $table->integer('status')->default(0);
            $table->foreignId('company_id')->references("id")->on("companies");
            $table->foreignId('created_by')->references("id")->on("users");
            $table->foreignId('created_department')->references('id')->on('departments');
            $table->foreignId('created_location')->references("id")->on("provinces");
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicals');
    }
};
