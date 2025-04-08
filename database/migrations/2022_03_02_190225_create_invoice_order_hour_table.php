<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceOrderHourTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_order_hour', function (Blueprint $table) {
            $table->id();
            $table->foreignUUID('invoice_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_hour_id')->constrained()->restrictOnDelete();
            $table->string('comment')->nullable();
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
        Schema::dropIfExists('invoice_order_hour');
    }
}
