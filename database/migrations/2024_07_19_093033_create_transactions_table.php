<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tbl_transactions', function (Blueprint $table) {
            $table->id();
            $table->timestamp('date')->nullable(false);
            $table->unsignedBigInteger('event_id')->nullable(false);
            $table->unsignedBigInteger('ticket_id')->nullable(false);
            $table->double('quantity')->nullable(false);
            $table->double('total_price')->nullable(false);
            $table->string('name', 255)->nullable(false);
            $table->string('email', 255)->nullable(false);
            $table->string('phone')->nullable(false);
            $table->integer('is_active')->nullable(false)->default(1);
            $table->timestamps();

            $table->foreign('event_id')->references('id')->on('tbl_events');
            $table->foreign('ticket_id')->references('id')->on('tbl_tickets');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_transactions');
    }
};
