<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropColumn(['party_date','location','party_type','start_time','end_time','guest_amount']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->timestamp('party_date')->nullable();
            $table->string('location')->nullable();
            $table->string('party_type')->nullable();
            $table->time('start_time', $precision = 0)->nullable();
            $table->time('end_time', $precision = 0)->nullable();
            $table->integer('guest_amount')->nullable();
        });
    }
};
