<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->longText('description')->nullable();
            $table->decimal('purchase_price', $precision = 7, $scale = 2);
            $table->decimal('price_customer_excluding_tax', $precision = 7, $scale = 2);
            $table->decimal('price_customer_including_tax', $precision = 7, $scale = 2);
            $table->decimal('profit', $precision = 10, $scale = 2);
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
        Schema::dropIfExists('services');
    }
}
