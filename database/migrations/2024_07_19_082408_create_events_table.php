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
        Schema::create('tbl_events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id')->nullable(false);
            $table->unsignedBigInteger('province_id')->nullable(false);
            $table->string('location', 100)->nullable(false);
            $table->unsignedBigInteger('category_id')->nullable(false);
            $table->text('description')->nullable();
            $table->text('information')->nullable();
            $table->string('image', 255)->nullable(false);
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->integer('is_active')->nullable(false)->default(1);
            $table->timestamps();

            $table->foreign('event_id')->references('id')->on('tbl_master_events');
            $table->foreign('province_id')->references('id')->on('tbl_provinces');
            $table->foreign('category_id')->references('id')->on('tbl_categories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_events');
    }
};
