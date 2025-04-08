<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTaxPercentageToOrderHoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_hours', function (Blueprint $table) {
            $table->integer('tax_percentage');
            $table->renameColumn('price_customer', 'price_customer_excluding_tax');
            $table->renameColumn('amount_revenue', 'amount_revenue_excluding_tax');
            $table->decimal('price_customer_including_tax', $precision = 7, $scale = 2);
            $table->decimal('amount_revenue_including_tax', $precision = 10, $scale = 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_hours', function (Blueprint $table) {
            $table->renameColumn('price_customer_excluding_tax', 'price_customer');
            $table->renameColumn('amount_revenue_excluding_tax', 'amount_revenue');
            $table->dropColumn(['tax_percentage','price_customer_including_tax','amount_revenue_including_tax']);
        });
    }
}
