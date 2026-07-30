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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('company_pa');
            $table->string('company_dr');
            $table->string('company_en');
            $table->string('icon');
            $table->enum('haq_alamatyaz', ['yes', 'no'])->default('no');
            $table->string('hanging_date')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('amount_of_money')->nullable();
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
        Schema::dropIfExists('companies');
    }
};
