<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameDurationToCommentOnInvoiceOrderProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoice_order_product', function (Blueprint $table) {
            $table->renameColumn('duration', 'comment');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoice_order_product', function (Blueprint $table) {
            $table->renameColumn('comment', 'duration');
        });
    }
}
