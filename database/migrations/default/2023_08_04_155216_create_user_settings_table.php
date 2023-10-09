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
        Schema::create('user_settings', function (Blueprint $table) {
            $table->id();
            $table->string('uniqueId')->nullable();
            $table->unsignedBigInteger('createdBy');
            $table->unsignedBigInteger('workspaceId')->nullable();

            $table->json('settings')->nullable();
            $table->string('type')->nullable();

            $table->integer('sortOrderNo')->default(0)->nullable();
            $table->boolean('isActive')->default(true)->nullable();
            $table->json('extraAttributes')->nullable();
            // $table->foreign('userId')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('workspaceId')->references('id')->on('work_spaces')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_settings');
    }
};
