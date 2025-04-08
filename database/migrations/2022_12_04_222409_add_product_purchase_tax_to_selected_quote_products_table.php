<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProductPurchaseTaxToSelectedQuoteProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('selected_quote_products', function (Blueprint $table) {
            $table->renameColumn('purchase_price', 'purchase_price_excluding_tax');
            $table->decimal('purchase_price_including_tax', $precision = 7, $scale = 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('selected_quote_products', function (Blueprint $table) {
            $table->renameColumn('purchase_price_excluding_tax', 'purchase_price');
            $table->dropColumn(['purchase_price_including_tax']);
        });
    }
}
