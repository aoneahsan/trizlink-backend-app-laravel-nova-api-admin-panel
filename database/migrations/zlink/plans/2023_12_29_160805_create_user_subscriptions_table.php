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
        Schema::create('user_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('uniqueId')->nullable();
            $table->unsignedBigInteger('userId');
            $table->unsignedBigInteger('planId');


            $table->datetime('startedAt')->nullable();
            $table->datetime('endedAt')->nullable();
            $table->datetime('renewedAt')->nullable();
            $table->datetime('canceledAt')->nullable();
            $table->integer('amount')->nullable();
            $table->string('currency')->nullable(); // (e.g., dollar $, pakistani pkr)
            $table->string('duration')->nullable(); // (e.g., 1 month, 1 year)


            $table->foreign('userId')->references('id')->on('users')->onDelete('cascade');

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
        Schema::dropIfExists('user_subscriptions');
    }
};
