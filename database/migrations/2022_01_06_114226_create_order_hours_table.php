<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderHoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_hours', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price_customer', $precision = 7, $scale = 2);
            $table->timestamp('date');
            $table->time('start_time', $precision = 0);
            $table->time('end_time', $precision = 0);
            $table->decimal('amount', $precision = 4, $scale = 2);
            $table->decimal('amount_revenue', $precision = 10, $scale = 2);
            $table->integer('kilometers')->nullable()->default(0);
            $table->integer('time_minutes')->nullable()->default(0);
            $table->longText('description');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('SET NULL');
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
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
        Schema::dropIfExists('order_hours');
    }
}
