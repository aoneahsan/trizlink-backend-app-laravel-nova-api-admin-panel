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
        Schema::create('lib_predefined_data', function (Blueprint $table) {
            $table->id();
            $table->string('uniqueId')->nullable();
            $table->unsignedBigInteger('userId');
            // $table->unsignedBigInteger('linkInBioId');

            // fields to store data for "link in bios pre defined blocks/music|messengers|social-platforms/form-fields"
            $table->string('type')->nullable();
            $table->string('icon')->nullable();
            $table->string('title')->nullable();
            // fields to store data for "link in bios pre defined themes"
            $table->json('background')->nullable();
            // this will tell us which type of predefined data is this (we will use this to get specific data from api)
            $table->string('preDefinedDataType')->nullable(); //  'musicPlatform' | 'messengerPlatform' | 'socialPlatform' | 'formField'

            $table->foreign('userId')->references('id')->on('users')->onDelete('cascade');

            $table->boolean('isActive')->default(true)->nullable();
            $table->integer('sortOrderNo')->default(0)->nullable();
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
        Schema::dropIfExists('lib_predefined_data');
    }
};
