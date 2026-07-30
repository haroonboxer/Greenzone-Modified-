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
        Schema::create('workshop_companies', function (Blueprint $table) {
            $table->id();
            $table->string('company_dr');
            $table->string('company_pa');
            $table->string('company_en');
            $table->string('icon');
            $table->string('address')->nullable();
            $table->string('tin');
            $table->integer('status')->default(0);
            $table->string('reason_dismissed')->nullable();
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
        Schema::dropIfExists('workshop_companies');
    }
};
