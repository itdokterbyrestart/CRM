<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveServicePricesFromServices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['purchase_price_excluding_tax', 'price_customer_excluding_tax', 'purchase_price_including_tax', 'price_customer_including_tax', 'tax_percentage', 'profit']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('services', function (Blueprint $table) {
            $table->decimal('purchase_price_excluding_tax', $precision = 7, $scale = 2);
            $table->decimal('purchase_price_including_tax', $precision = 7, $scale = 2);
            $table->decimal('price_customer_excluding_tax', $precision = 7, $scale = 2);
            $table->decimal('price_customer_including_tax', $precision = 7, $scale = 2);
            $table->decimal('profit', $precision = 10, $scale = 2);
            $table->integer('tax_percentage');
        });
    }
}
