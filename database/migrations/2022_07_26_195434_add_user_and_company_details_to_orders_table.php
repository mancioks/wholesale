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
        Schema::table('orders', function (Blueprint $table) {
            $table->text('company_details')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('customer_company_name')->nullable();
            $table->string('customer_company_address')->nullable();
            $table->string('customer_company_registration_code')->nullable();
            $table->string('customer_company_vat_number')->nullable();
            $table->string('customer_company_phone_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('company_details');
            $table->dropColumn('customer_name');
            $table->dropColumn('customer_email');
            $table->dropColumn('customer_company_name');
            $table->dropColumn('customer_company_address');
            $table->dropColumn('customer_company_registration_code');
            $table->dropColumn('customer_company_vat_number');
            $table->dropColumn('customer_company_phone_number');
        });
    }
};
