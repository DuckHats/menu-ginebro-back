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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->enum('type', ['topup', 'order', 'correction'])->default('topup');
            $table->string('description')->nullable();
            $table->foreignId('internal_order_id')->nullable()->constrained('orders')->onDelete('set null');
            $table->string('status')->default('pending'); // pending, completed, failed
            $table->string('order_id')->nullable(); // ID de Redsys
            $table->string('response_code')->nullable();
            $table->string('authorization_code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
