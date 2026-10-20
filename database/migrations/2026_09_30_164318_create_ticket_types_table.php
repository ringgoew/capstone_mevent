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
        Schema::create('ticket_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id');
            $table->string('name'); // VIP, Regular
            $table->decimal('price', 12, 2)->default(0.00);
            $table->integer('quota');
            $table->integer('reserved_quota')->default(0);
            $table->integer('sold_quota')->default(0);
            $table->integer('max_per_order')->default(5);
            $table->timestamp('start_sales')->nullable();
            $table->timestamp('end_sales')->nullable();
            $table->timestamps();

            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_types');
    }
};
