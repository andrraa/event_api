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
        Schema::create('tbl_tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id')->nullable(false);
            $table->string('name', 100)->nullable(false);
            $table->double('price')->nullable(false);
            $table->integer('quota')->nullable(false);
            $table->string('information', 255)->nullable();
            $table->integer('is_active')->nullable(false)->default(1);
            $table->timestamps();

            $table->foreign('event_id')->references('id')->on('tbl_events');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_tickets');
    }
};
