<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bonus_calculations', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('employee');

            $table->date('date')->nullable();
            $table->string('object')->nullable();
            $table->integer('manager_id')->nullable();
            $table->integer('installer_id')->nullable();
            $table->decimal('estimate_total')->nullable();
            $table->decimal('invoice_total')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bonus_calculations', function (Blueprint $table) {
            $table->string('name')->nullable();
            $table->string('employee')->nullable();

            $table->dropColumn('date');
            $table->dropColumn('object');
            $table->dropColumn('manager_id');
            $table->dropColumn('installer_id');
            $table->dropColumn('estimate_total');
            $table->dropColumn('invoice_total');
        });
    }
};
