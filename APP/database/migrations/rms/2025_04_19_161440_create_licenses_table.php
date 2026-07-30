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
        Schema::create('licenses', function (Blueprint $table) {
            $table->id();
            $table->enum('license_type', ['new', 'extend', 'renew']);
            $table->string('issue_date');
            $table->string('validity_date');
            $table->string('license_date');
            $table->string('slip_no');
            $table->string('fee');
            $table->integer('status')->default(0);
            $table->string('reason_dismissed')->nullable();
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
        Schema::dropIfExists('licenses');
    }
};
