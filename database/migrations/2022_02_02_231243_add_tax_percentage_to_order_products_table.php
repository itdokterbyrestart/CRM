<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTaxPercentageToOrderProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_products', function (Blueprint $table) {
            $table->integer('tax_percentage')->default(0);
            $table->renameColumn('price_customer', 'price_customer_excluding_tax');
            $table->decimal('price_customer_including_tax', $precision = 7, $scale = 2);
            $table->decimal('total_price_customer_including_tax', $precision = 10, $scale = 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_products', function (Blueprint $table) {
            $table->dropColumn(['tax_percentage','price_customer_including_tax','total_price_customer_including_tax']);
            $table->renameColumn('price_customer_excluding_tax', 'price_customer');
        });
    }
}
