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
            $table->dropForeign('created_department');
            $table->dropForeign('created_by');
            $table->dropForeign('created_location');
        });

        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('created_by')->change();
            $table->string('created_department')->change();
            $table->string('created_by_name')->nullable();
        });
        //Drivers dropping Foreign key
        Schema::table('drivers', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['created_department']);
            $table->dropForeign(['created_location']);
        });
        Schema::table('drivers', function (Blueprint $table) {


            $table->string('created_by')->change();
            $table->string('created_department')->change();

            $table->string("created_by_name")->nullable();
        });
        //attachment table dropping the ForeignKey
        Schema::table('attachments', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
        });
        Schema::table('attachments', function (Blueprint $table) {

            $table->string('created_by')->change();
            $table->string("created_by_name")->nullable();
        });
        Schema::table('user_languages', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('user_languages', function (Blueprint $table) {

            $table->string('user_id')->change();
            $table->string("created_by_name")->nullabel();
        });


        Schema::table('gzlicenses', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['created_department']);
            $table->dropForeign(['created_location']);
        });

        Schema::table('gzlicenses', function (Blueprint $table) {
            $table->string('created_by')->change();
            $table->string('created_department')->change();

            $table->string("created_by_name")->nullable();
        });


        Schema::table('companies', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['created_department']);
            $table->dropForeign(['created_location']);
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->string('created_by')->change();
            $table->string('created_department')->change();

            $table->string("created_by_name")->nullable();
        });


        Schema::table('workshop_companies', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['created_department']);
            $table->dropForeign(['created_location']);
        });

        Schema::table('workshop_companies', function (Blueprint $table) {


            $table->string('created_by')->change();
            $table->string('created_department')->change();

            $table->string("created_by_name")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // =========================
        // Vehicles
        // =========================
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn('created_by_name');

            $table->bigInteger('created_by')->change();
            $table->bigInteger('created_department')->change();

            $table->foreign('created_by')
                ->references('id')
                ->on('users');

            $table->foreign('created_department')
                ->references('id')
                ->on('departments');

            $table->foreign('created_location')
                ->references('id')
                ->on('provinces');
        });


        // =========================
        // Drivers
        // =========================
        Schema::table('drivers', function (Blueprint $table) {
            $table->dropColumn('created_by_name');

            $table->bigInteger('created_by')->change();
            $table->bigInteger('created_department')->change();

            $table->foreign('created_by')
                ->references('id')
                ->on('users');

            $table->foreign('created_department')
                ->references('id')
                ->on('departments');

            $table->foreign('created_location')
                ->references('id')
                ->on('provinces');
        });


        // =========================
        // Attachments
        // =========================
        Schema::table('attachments', function (Blueprint $table) {
            $table->dropColumn('created_by_name');

            $table->bigInteger('created_by')->change();

            $table->foreign('created_by')
                ->references('id')
                ->on('users');
        });


        // =========================
        // User Languages
        // =========================
        Schema::table('user_languages', function (Blueprint $table) {
            $table->dropColumn('created_by_name');

            $table->bigInteger('user_id')->change();

            $table->foreign('user_id')
                ->references('id')
                ->on('users');
        });


        // =========================
        // GZ Licenses
        // =========================
        Schema::table('gzlicenses', function (Blueprint $table) {
            $table->dropColumn('created_by_name');

            $table->bigInteger('created_by')->change();
            $table->bigInteger('created_department')->change();

            $table->foreign('created_by')
                ->references('id')
                ->on('users');

            $table->foreign('created_department')
                ->references('id')
                ->on('departments');

            $table->foreign('created_location')
                ->references('id')
                ->on('provinces');
        });


        // =========================
        // Companies
        // =========================
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('created_by_name');

            $table->bigInteger('created_by')->change();
            $table->bigInteger('created_department')->change();

            $table->foreign('created_by')
                ->references('id')
                ->on('users');

            $table->foreign('created_department')
                ->references('id')
                ->on('departments');

            $table->foreign('created_location')
                ->references('id')
                ->on('provinces');
        });


        // =========================
        // Workshop Companies
        // =========================
        Schema::table('workshop_companies', function (Blueprint $table) {
            $table->dropColumn('created_by_name');

            $table->bigInteger('created_by')->change();
            $table->bigInteger('created_department')->change();

            $table->foreign('created_by')
                ->references('id')
                ->on('users');

            $table->foreign('created_department')
                ->references('id')
                ->on('departments');

            $table->foreign('created_location')
                ->references('id')
                ->on('provinces');
        });
    }
};
