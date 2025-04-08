<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDiscountPriceToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('use_discount_price')->default(0);
            $table->decimal('discount_price_customer_including_tax', $precision = 7, $scale = 2);
            $table->decimal('discount_price_customer_excluding_tax', $precision = 7, $scale = 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['discount_price_customer_including_tax','discount_price_customer_excluding_tax','use_discount_price']);
        });
    }
}
