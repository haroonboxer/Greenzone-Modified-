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
        Schema::table('vehicle_saves', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['created_department']);
            $table->dropForeign(['created_location']);
        });

        Schema::table('vehicle_saves', function (Blueprint $table) {
            $table->string('created_by')->change();
            $table->string('created_department')->change();
            $table->string('created_by_name')->nullable();
        });

        //  bosses dropping Foreign Key 
        Schema::table('bosses', function (Blueprint $table) {
            $table->dropForeign('bosses_created_by_foreign');
            $table->dropForeign('bosses_created_department_foreign');
            $table->dropForeign('bosses_created_location_foreign');
        });

        Schema::table('bosses', function (Blueprint $table) {
            $table->string('created_by')->change();
            $table->string('created_department')->change();
            $table->string('created_by_name')->nullable();
        });

        //  assistants dropping Foreign Key 
        Schema::table('assistants', function (Blueprint $table) {
            $table->dropForeign('assistants_created_by_foreign');
            $table->dropForeign('assistants_created_department_foreign');
            $table->dropForeign('assistants_created_location_foreign');
        });

        Schema::table('assistants', function (Blueprint $table) {
            $table->string('created_by')->change();
            $table->string('created_department')->change();
            $table->string('created_by_name')->nullable();
        });


        //  employees dropping Foreign Key 
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign('employees_created_by_foreign');
            $table->dropForeign('employees_created_department_foreign');
            $table->dropForeign('employees_created_location_foreign');
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->string('created_by')->change();
            $table->string('created_department')->change();
            $table->string('created_by_name')->nullable();
        });


        //  weapons_general_tables dropping Foreign Key 
        Schema::table('weapons_general_tables', function (Blueprint $table) {
            $table->dropForeign('weapons_general_tables_created_by_foreign');
            $table->dropForeign('weapons_general_tables_created_department_foreign');
            $table->dropForeign('weapons_general_tables_created_location_foreign');
        });

        Schema::table('weapons_general_tables', function (Blueprint $table) {
            $table->string('created_by')->change();
            $table->string('created_department')->change();
            $table->string('created_by_name')->nullable();
        });
        //  licenses dropping Foreign Key 
        Schema::table('licenses', function (Blueprint $table) {
            $table->dropForeign('licenses_created_by_foreign');
            $table->dropForeign('licenses_created_department_foreign');
            $table->dropForeign('licenses_created_location_foreign');
        });

        Schema::table('licenses', function (Blueprint $table) {
            $table->string('created_by')->change();
            $table->string('created_department')->change();
            $table->string('created_by_name')->nullable();
        });


        //  contracts dropping Foreign Key 
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropForeign('contracts_created_by_foreign');
            $table->dropForeign('contracts_created_department_foreign');
            $table->dropForeign('contracts_created_location_foreign');
        });

        Schema::table('contracts', function (Blueprint $table) {
            $table->string('created_by')->change();
            $table->string('created_department')->change();
            $table->string('created_by_name')->nullable();
        });
        //  guns dropping Foreign Key 
        Schema::table('guns', function (Blueprint $table) {
            $table->dropForeign('guns_created_by_foreign');
            $table->dropForeign('guns_created_department_foreign');
            $table->dropForeign('guns_created_location_foreign');
        });

        Schema::table('guns', function (Blueprint $table) {
            $table->string('created_by')->change();
            $table->string('created_department')->change();
            $table->string('created_by_name')->nullable();
        });


        //  printed_cards dropping Foreign Key 
        Schema::table('printed_cards', function (Blueprint $table) {
            $table->dropForeign('printed_cards_created_by_foreign');
            $table->dropForeign('printed_cards_created_department_foreign');
            $table->dropForeign('printed_cards_created_location_foreign');
        });

        Schema::table('printed_cards', function (Blueprint $table) {
            $table->string('created_by')->change();
            $table->string('created_department')->change();
            $table->string('created_by_name')->nullable();
        });

        //  workshop_bosses dropping Foreign Key 
        Schema::table('workshop_bosses', function (Blueprint $table) {
            $table->dropForeign('workshop_bosses_created_by_foreign');
            $table->dropForeign('workshop_bosses_created_department_foreign');
            $table->dropForeign('workshop_bosses_created_location_foreign');
        });

        Schema::table('workshop_bosses', function (Blueprint $table) {
            $table->string('created_by')->change();
            $table->string('created_department')->change();
            $table->string('created_by_name')->nullable();
        });

        //  workshop_assistants dropping Foreign Key 
        Schema::table('workshop_assistants', function (Blueprint $table) {
            $table->dropForeign('workshop_assistants_created_by_foreign');
            $table->dropForeign('workshop_assistants_created_department_foreign');
            $table->dropForeign('workshop_assistants_created_location_foreign');
        });

        Schema::table('workshop_assistants', function (Blueprint $table) {
            $table->string('created_by')->change();
            $table->string('created_department')->change();
            $table->string('created_by_name')->nullable();
        });
        //  workshop_licenses dropping Foreign Key 
        Schema::table('workshop_licenses', function (Blueprint $table) {
            $table->dropForeign('workshop_licenses_created_by_foreign');
            $table->dropForeign('workshop_licenses_created_department_foreign');
            $table->dropForeign('workshop_licenses_created_location_foreign');
        });

        Schema::table('workshop_licenses', function (Blueprint $table) {
            $table->string('created_by')->change();
            $table->string('created_department')->change();
            $table->string('created_by_name')->nullable();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void {}
};
