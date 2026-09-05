<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('wallet_id')->nullable()->change();
            
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedBigInteger('listing_id')->nullable(); 
            
            $table->string('payment_provider')->nullable(); 
            $table->string('provider_transaction_id')->nullable();
            $table->string('currency', 3)->default('NGN');
            $table->string('payment_type')->nullable(); 
            $table->string('product_purchased')->nullable(); 
            $table->json('metadata')->nullable();
            
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            
            $table->index(['reference', 'payment_provider']);
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn([
                'user_id', 'listing_id', 'payment_provider', 
                'provider_transaction_id', 'currency', 'payment_type', 
                'product_purchased', 'metadata', 'paid_at', 'updated_at'
            ]);
        });
    }
};
