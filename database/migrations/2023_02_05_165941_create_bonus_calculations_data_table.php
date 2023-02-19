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
        Schema::create('bonus_calculations_data', function (Blueprint $table) {
            $table->id();
            $table->integer('calculation_id');
            $table->integer('bonus_calculations_rule_id')->nullable(true);
            $table->string('material');
            $table->decimal('price', 10, 2);
            $table->integer('quantity')->nullable(true);
            $table->date('used_at');
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
        Schema::dropIfExists('bonus_calculations_data');
    }
};
