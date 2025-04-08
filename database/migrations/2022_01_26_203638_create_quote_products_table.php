<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuoteProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quote_products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->longText('description')->nullable();
            $table->decimal('purchase_price', $precision = 7, $scale = 2);
            $table->decimal('price_customer_excluding_tax', $precision = 7, $scale = 2);
            $table->decimal('price_customer_including_tax', $precision = 7, $scale = 2);
            $table->decimal('amount', $precision = 4, $scale = 2);
            $table->decimal('total_price_customer_excluding_tax', $precision = 10, $scale = 2);
            $table->decimal('total_price_customer_including_tax', $precision = 10, $scale = 2);
            $table->foreignUuid('quote_id')->constrained()->cascadeOnDelete();
            $table->integer('tax_percentage');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quote_products');
    }
}
