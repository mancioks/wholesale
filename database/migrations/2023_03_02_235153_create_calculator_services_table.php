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
        Schema::create('calculator_services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('units');
            $table->decimal('step');
            $table->unsignedDecimal('price', 10, 2);
            $table->unsignedDecimal('min_price', 10, 2);
            $table->unsignedDecimal('mid_price', 10, 2);
            $table->unsignedDecimal('max_price', 10, 2);
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
        Schema::dropIfExists('calculator_services');
    }
};
