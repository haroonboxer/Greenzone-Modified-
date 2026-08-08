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
        //Vihecle dropping Foreign key
        Schema::table('vehicles', function (Blueprint $table) {
            Schema::table('vehicles', function (Blueprint $table) {
                $table->dropForeign(['created_department']);
                $table->dropForeign(['created_by']);
                $table->dropForeign(['created_location']);

                $table->string('created_by')->change();
                $table->string('created_department')->change();
                $table->string('created_location')->change();
            });
        });
        //Drivers dropping Foreign key
        Schema::table('drivers', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['created_department']);
            $table->dropForeign(['created_location']);

            $table->string('created_by')->change();
            $table->string('created_department')->change();
            $table->string('created_location')->change();
        });
        //attachment table dropping the ForeignKey
        Schema::table('attachments', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->string('created_by')->change();
        });
        Schema::table('user_languages', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->string('user_id')->change();
        });
        Schema::table('gzlicenses',function(Blueprint $table){
            $table->dropForeign(['created_by']);
            $table->dropForeign(['created_department']);
            $table->dropForeign(['created_location']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Vehicles adding Foreign key
        Schema::table('vehicles', function (Blueprint $table) {
            $table->foreign('created_department')->references('id')->on('departments');
            $table->foreign('created_location')->references('id')->on('provinces');
            $table->foreign('created_by')->references('id')->on('users');
        });
        //Drivers adding Foreign key
        Schema::table('drivers', function (Blueprint $table) {
            $table->foreign(['created_by'])->references('id')->on('users');
            $table->foreign(['created_department'])->references('id')->on('departments');
            $table->foreign(['created_location'])->references('id')->on('provinces');
        });
    }
};
