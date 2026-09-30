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
        Schema::create('payouts', function (Blueprint $table) {
            $table->id();
            $table->string('payout_code')->unique();
            $table->foreignId('user_id')->comment('EO User ID')->constrained('users')->cascadeOnDelete();
            $table->foreignId('event_id')->nullable()->comment('Event ID terkait pencairan')->constrained('events')->nullOnDelete();
            $table->decimal('gross_amount', 12, 2);
            $table->decimal('commission_fee', 12, 2);
            $table->decimal('net_amount', 12, 2);
            $table->string('bank_name');
            $table->string('bank_account_number');
            $table->string('bank_account_holder');
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
            $table->string('proof_of_transfer')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->foreignId('processed_by')->nullable()->comment('Admin User ID')->constrained('users')->nullOnDelete();
            $table->timestamp('requested_at')->useCurrent();
            $table->timestamp('processed_at')->nullable();            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payouts');
    }
};
