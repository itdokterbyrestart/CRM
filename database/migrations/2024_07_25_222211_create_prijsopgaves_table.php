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
        Schema::create('prijsopgaves', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamp('party_date')->nullable();
            $table->boolean('party_date_available')->nullable()->default(0);

            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();

            $table->time('start_time', $precision = 0)->nullable();
            $table->time('end_time', $precision = 0)->nullable();
            $table->decimal('party_duration', $precision = 4, $scale = 2)->nullable();
            $table->string('party_type')->nullable();
            $table->string('location')->nullable();
            $table->boolean('party_on_upper_floor')->nullable()->default(0);
            $table->boolean('upper_floor_elevator_available')->nullable()->default(0);
            $table->integer('guest_amount')->nullable();
            $table->string('show_type')->nullable();

            $table->integer('currentStep');

            $table->integer('reminder')->nullable()->default(0);
            $table->timestamp('reminder_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prijsopgaves');
    }
};
