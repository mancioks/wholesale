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
        Schema::create('bonus_calculations_estimate_data', function (Blueprint $table) {
            $table->id();
            $table->integer('calculation_id');
            $table->integer('service_id');
            $table->decimal('qty');
            $table->decimal('actual_amount');
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
        Schema::dropIfExists('bonus_calculations_estimate_data');
    }
};
