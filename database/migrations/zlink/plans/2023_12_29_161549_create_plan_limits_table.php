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
        Schema::create('plan_limits', function (Blueprint $table) {
            $table->id();
            $table->string('uniqueId')->nullable();
            $table->unsignedBigInteger('userId')->nullable();
            $table->unsignedBigInteger('planId');

            $table->string('type')->nullable();
            $table->string('version')->nullable();
            $table->string('name')->nullable(); // (e.g., "shortLink", "qrCode", "linkInBio") (For internal purpose)
            $table->string('displayName')->nullable(); // (e.g., "Short Link", "QR Code", "Link-In-Bio")
            $table->integer('maxLimit')->nullable();
            $table->string('timeLine')->nullable(); // (e.g., "monthly", "yearly")
            $table->text('description')->nullable();

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
        Schema::dropIfExists('plan_limits');
    }
};
