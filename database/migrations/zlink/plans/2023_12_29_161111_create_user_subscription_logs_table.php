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
        Schema::create('user_subscription_logs', function (Blueprint $table) {
            $table->id();
            $table->string('uniqueId')->nullable();
            $table->unsignedBigInteger('userId')->nullable();
            $table->unsignedBigInteger('subscriptionId');
            
            $table->text('detail')->nullable();
            $table->string('action')->nullable(); // (e.g, 'planUpgraded', 'planDowngraded', etc.)
            $table->dateTime('actionDate')->nullable();
            $table->unsignedBigInteger('currentPlan')->nullable();
            $table->unsignedBigInteger('upgradedPlan')->nullable();
            $table->unsignedBigInteger('downgradedPlan')->nullable();


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
        Schema::dropIfExists('user_subscription_logs');
    }
};
