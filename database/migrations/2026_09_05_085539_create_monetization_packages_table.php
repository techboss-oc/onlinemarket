<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monetization_packages', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // top_ad, boost, featured, seller_package
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2);
            $table->string('currency', 3)->default('NGN');
            $table->integer('duration_days')->default(0); 
            $table->integer('boost_count')->default(0); 
            $table->integer('refresh_frequency_hours')->default(0);
            $table->integer('listing_limit')->default(0); 
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->index(['type', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monetization_packages');
    }
};
