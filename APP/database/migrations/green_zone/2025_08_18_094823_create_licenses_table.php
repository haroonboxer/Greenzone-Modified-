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
        Schema::create('gzlicenses', function (Blueprint $table) {
            $table->id();
            $table->enum('license_type', ['new', 'extend', 'renew']);
            $table->string('issue_date');
            $table->string('expire_date');
            $table->string('sn')->nullable();
            $table->string('reject_reason')->nullable();
            $table->integer('status')->default(0);
            $table->integer('printed')->default(0);
            $table->foreignId('vehicle_id')->references("id")->on("vehicles");
            $table->foreignId('driver_id')->references("id")->on("drivers");
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
