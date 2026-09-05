<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('advertising_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->nullable()->constrained('advertising_campaigns')->nullOnDelete();
            $table->foreignId('ad_id')->constrained('ads')->cascadeOnDelete();
            $table->string('type'); 
            $table->string('ip_address', 45)->nullable();
            $table->string('session_id')->nullable();
            $table->decimal('cost', 8, 2)->default(0);
            $table->timestamp('created_at')->useCurrent();
            
            $table->index(['campaign_id', 'type', 'created_at']);
            $table->index(['ad_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advertising_analytics');
    }
};
