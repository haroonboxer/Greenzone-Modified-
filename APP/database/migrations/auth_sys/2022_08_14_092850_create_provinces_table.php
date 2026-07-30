<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProvincesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('provinces', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('name_en')->nullable();
            $table->string('name_dr')->nullable();
            $table->string('name_pa')->nullable();
            $table->string('zone')->nullable();
            $table->string('lat')->nullable();
            $table->string('lang')->nullable();
            $table->string('ab')->nullable();
            $table->string('color')->nullable();
            $table->string('mgrs')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('provinces');
    }
}
