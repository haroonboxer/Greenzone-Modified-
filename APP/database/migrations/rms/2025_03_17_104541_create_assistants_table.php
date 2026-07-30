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
        Schema::create('assistants', function (Blueprint $table) {
            $table->id();
            $table->string('name_dr');
            $table->string('name_en');
            $table->string('last_name_dr');
            $table->string('last_name_en');
            $table->string('f_name_da');
            $table->string('email');
            $table->string('phone');
            $table->string('passport_no');
            $table->string('country');
            $table->string('type_residence_info')->nullable();
            $table->string('photo');
            $table->string('main_province')->nullable();
            $table->string('main_district')->nullable();
            $table->string('main_village')->nullable();
            $table->string('current_province')->nullable();
            $table->string('current_district')->nullable();
            $table->string('current_village')->nullable();
            $table->string('reason_dismissed')->nullable();
            $table->integer('status')->default(1);
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
        Schema::dropIfExists('assistants');
    }
};
