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
        Schema::create('plan', function (Blueprint $table) {
            $table->id();
            $table->string('uniqueId')->nullable();
            $table->unsignedBigInteger('userId')->nullable();
            
            $table->string('name')->nullable(); // (e.g., "Basic", "Pro", "Premium") (For internal purpose)
            $table->string('displayName')->nullable(); // (e.g., "Basic", "Pro", "Premium") to display in page
            $table->integer('monthlyPrice')->nullable();
            $table->integer('annualPrice')->nullable();
            $table->integer('monthlyDiscountedPrice')->nullable();   
            $table->integer('annualDiscountedPrice')->nullable();   
            $table->string('currency')->nullable();
            $table->text('description')->nullable();   
            $table->string('featureListTitle')->nullable();   
            $table->boolean('isMostPopular')->default(false)->nullable();
            $table->json('features')->nullable();
            $table->boolean('isAnnualOnly')->default(false)->nullable();


            $table->integer('sortOrderNo')->default(0)->nullable();
            $table->boolean('isActive')->default(true)->nullable();
            $table->json('extraAttributes')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan');
    }
};
