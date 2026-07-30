<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDistrictsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('districts', function (Blueprint $table) {
            $table->id();
            $table->string('province')->nullable();
            $table->string('district')->nullable();
            $table->string('province_dr')->nullable();
            $table->string('district_dr')->nullable();
            $table->string('district_pa')->nullable();
            $table->string('zone')->nullable();
            $table->unsignedBigInteger('provincecode');
            $table->string('districtcode')->nullable();
            $table->string('lat')->nullable();
            $table->string('lng')->nullable();
            $table->string('mgrs')->nullable();
            $table->timestamps();

            $table->foreign('provincecode')->references('id')->on('provinces')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('districts');
    }
}