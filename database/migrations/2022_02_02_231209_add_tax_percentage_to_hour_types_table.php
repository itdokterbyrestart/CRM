<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTaxPercentageToHourTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hour_types', function (Blueprint $table) {
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
        Schema::table('hour_types', function (Blueprint $table) {
            $table->dropColumn(['tax_percentage','price_customer_including_tax']);
            $table->renameColumn('price_customer_excluding_tax', 'price_customer');
        });
    }
}
