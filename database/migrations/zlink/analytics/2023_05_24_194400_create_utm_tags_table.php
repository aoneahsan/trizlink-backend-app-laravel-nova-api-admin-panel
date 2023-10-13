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
        Schema::create('utm_tags', function (Blueprint $table) {
            $table->id();
            $table->string('uniqueId')->nullable();
            $table->unsignedBigInteger('workspaceId');
            $table->unsignedBigInteger('createdBy');

            $table->string('templateName')->nullable();
            $table->string('utmCampaign')->nullable();
            $table->string('utmMedium')->nullable();
            $table->string('utmSource')->nullable();
            $table->string('utmTerm')->nullable();
            $table->string('utmContent')->nullable();

            $table->foreign('workspaceId')->references('id')->on('work_spaces')->onDelete('cascade');

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
        Schema::dropIfExists('utm_tags');
    }
};
