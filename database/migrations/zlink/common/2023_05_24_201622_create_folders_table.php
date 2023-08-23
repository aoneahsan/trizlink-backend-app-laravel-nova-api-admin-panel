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
        Schema::create('folders', function (Blueprint $table) {
            $table->id();
            $table->string('uniqueId')->nullable();
            $table->unsignedBigInteger('userId');
            $table->unsignedBigInteger('workspaceId');

            $table->string('title')->nullable();
            $table->string('icon')->nullable();
            $table->boolean('isStared')->default(false)->nullable();
            $table->boolean('isHidden')->default(false)->nullable();
            $table->boolean('isFavorite')->default(false)->nullable();
            $table->boolean('isPasswordProtected')->default(false)->nullable();
            $table->string('password')->nullable();
            $table->string('folderForModel'); // 'shortLink' | 'linkInBio'
            $table->boolean('isDefault')->default(false)->nullable();

            $table->foreign('userId')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('workspaceId')->references('id')->on('work_spaces')->onDelete('cascade');

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
        Schema::dropIfExists('folders');
    }
};
