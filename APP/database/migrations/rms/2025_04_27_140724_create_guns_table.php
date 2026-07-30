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
        Schema::create('guns', function (Blueprint $table) {
            $table->id();
            $table->string('gun_no');
            $table->string('gun_type');
            $table->string('gun_diameter');
            $table->string('taedad_jabeh');
            $table->string('gun_country');
            $table->foreignId('company_id')->references("id")->on("companies");
            $table->foreignId('weapon_id')->references("id")->on("weapons_general_tables");
            $table->foreignId('created_by')->references("id")->on("users");
            $table->foreignId('created_department')->references('id')->on('departments');
            $table->foreignId('created_location')->references("id")->on("provinces");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guns');
    }
};
