<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('advertising_campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('ad_id')->constrained('ads')->cascadeOnDelete();
            $table->decimal('budget', 12, 2);
            $table->decimal('remaining_budget', 12, 2);
            $table->decimal('cost_per_click', 8, 2);
            $table->string('status')->default('active'); 
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();
            
            $table->index(['ad_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advertising_campaigns');
    }
};
