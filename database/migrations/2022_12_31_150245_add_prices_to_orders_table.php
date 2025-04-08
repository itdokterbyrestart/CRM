<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPricesToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('total_price_customer_excluding_tax', $precision = 10, $scale = 2);
            $table->decimal('total_tax_amount', $precision = 10, $scale = 2);
            $table->decimal('total_price_customer_including_tax', $precision = 10, $scale = 2);
            $table->decimal('total_purchase_price_excluding_tax', $precision = 10, $scale = 2);
            $table->decimal('total_profit', $precision = 10, $scale = 2);
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
            $table->dropColumn(['total_price_customer_excluding_tax', 'total_tax_amount', 'total_price_customer_including_tax', 'total_purchase_price_excluding_tax', 'total_profit']);
        });
    }
}
