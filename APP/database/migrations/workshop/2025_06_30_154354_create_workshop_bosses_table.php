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
        Schema::create('workshop_bosses', function (Blueprint $table) {
            $table->id();
            $table->string('name_dr');
            $table->string('name_en');
            $table->string('last_name_dr');
            $table->string('last_name_en');
            $table->string('f_name_da');
            $table->string('phone');
            $table->string('passport_no');
            $table->string('country');
            $table->string('photo');
            $table->string('main_province')->nullable();
            $table->string('main_district')->nullable();
            $table->string('main_village')->nullable();
            $table->string('current_province')->nullable();
            $table->string('current_district')->nullable();
            $table->string('current_village')->nullable();
            $table->string('type_residence_info')->nullable();
            $table->integer('status')->default(0);
            $table->string('reason_dismissed')->nullable();
            $table->foreignId('company_id')->constrained('workshop_companies');
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
        Schema::dropIfExists('workshop_bosses');
    }
};
