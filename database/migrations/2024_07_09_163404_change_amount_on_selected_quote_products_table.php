<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeAmountOnSelectedQuoteProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('selected_quote_products', function (Blueprint $table) {
            $table->decimal('amount', $precision = 6, $scale = 2)->change();
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
            $table->decimal('amount', $precision = 4, $scale = 2)->change();
        });
    }
}
