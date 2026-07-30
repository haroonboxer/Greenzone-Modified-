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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->string('contract_source');
            $table->string('contract_location');
            $table->string('contract_start_date');
            $table->string('contract_end_date');
            $table->string('afghan_personal_count');
            $table->string('external_personal_count');
            $table->string('ammo_count');
            $table->string('vehical_count');
            $table->string('walkie_talkie_count');
            $table->string('equipments_value');
            $table->string('other_equipments');
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
        Schema::dropIfExists('contracts');
    }
};
