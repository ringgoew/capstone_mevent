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
        Schema::create('event_inspections', function (Blueprint $table) {
            $table->id();$table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('event_id')->unique(); 
            $table->timestamp('inspection_time')->nullable();
            $table->text('inspection_records')->nullable();
            $table->string('review_status')->default('pending');
             
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_inspections');
    }
};
