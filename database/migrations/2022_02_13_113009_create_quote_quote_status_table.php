<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuoteQuoteStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quote_quote_status', function (Blueprint $table) {
            $table->id();
            $table->foreignUUID('quote_id')->constrained()->cascadeOnDelete();
            $table->foreignId('quote_status_id')->constrained()->restrictOnDelete();
            $table->longText('comment')->nullable();
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
        Schema::dropIfExists('quote_quote_status');
    }
}
