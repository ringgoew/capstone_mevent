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
        Schema::create('e_tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('order_item_id');
            $table->string('ticket_code')->unique();
            $table->string('qr_code_hash');
            $table->string('attendee_name');
            $table->string('attendee_email');
            $table->string('attendee_phone')->nullable();
            $table->string('status')->default('valid');
            $table->timestamp('checked_in_at')->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('order_item_id')->references('id')->on('order_items')->onDelete('cascade');        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('e_tickets');
    }
};
