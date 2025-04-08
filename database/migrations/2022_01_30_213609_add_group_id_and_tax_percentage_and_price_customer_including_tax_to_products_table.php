<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGroupIdAndTaxPercentageAndPriceCustomerIncludingTaxToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('product_group_id')->nullable()->constrained()->cascadeOnDelete();
            $table->integer('tax_percentage');
            $table->renameColumn('price_customer', 'price_customer_excluding_tax');
            $table->decimal('price_customer_including_tax', $precision = 7, $scale = 2);
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
            $table->dropForeign(['product_group_id']);
            $table->dropColumn(['product_group_id']);
            $table->renameColumn('price_customer_excluding_tax', 'price_customer');
            $table->dropColumn(['tax_percentage','price_customer_including_tax']);
        });
    }
}
