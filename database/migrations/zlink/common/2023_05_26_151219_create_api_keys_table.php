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
        Schema::create('api_keys', function (Blueprint $table) {
            $table->id();
            $table->string('uniqueId')->nullable();
            $table->unsignedBigInteger('userId');

            $table->string('title')->nullable();
            $table->string('clientId')->nullable();
            $table->string('clientSecret')->nullable();
            $table->dateTime('expireDate')->nullable();

            $table->foreign('userId')->references('id')->on('users')->onDelete('cascade');

            $table->integer('sortOrderNo')->default(0)->nullable();
            $table->boolean('isActive')->default(true);
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
        Schema::dropIfExists('api_keys');
    }
};
