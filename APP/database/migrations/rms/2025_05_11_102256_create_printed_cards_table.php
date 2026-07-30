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
        Schema::create('printed_cards', function (Blueprint $table) {
            $table->id();
            $table->string('weapons');
            $table->enum('card_type', ['new', 'extend']);
            $table->string('project_name_dr');
            $table->string('project_name_en');
            $table->string('card_perimeter_dr');
            $table->string('card_perimeter_en');
            $table->string('issued_date');
            $table->string('expire_date');
            $table->integer('status')->default(0);
            $table->integer('is_printed')->default(0);
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
        Schema::dropIfExists('printed_cards');
    }
};
