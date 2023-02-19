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
        Schema::create('bonus_calculations_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 10, 2)->nullable(true);
            $table->decimal('sum', 10, 2)->nullable(true);
            $table->decimal('min_price', 10, 2)->nullable(true);
            $table->decimal('min_sum', 10, 2)->nullable(true);
            $table->decimal('mid_price', 10, 2)->nullable(true);
            $table->decimal('mid_sum', 10, 2)->nullable(true);
            $table->decimal('max_price', 10, 2)->nullable(true);
            $table->decimal('max_sum', 10, 2)->nullable(true);
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
        Schema::dropIfExists('bonus_calculations_rules');
    }
};
