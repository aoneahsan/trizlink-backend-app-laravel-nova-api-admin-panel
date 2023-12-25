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
        Schema::create('s_l_analytics', function (Blueprint $table) {
            $table->id();
            $table->string('uniqueId')->nullable();
            $table->unsignedBigInteger('userId'); // owner of shortlink
            $table->unsignedBigInteger('shortLinkId'); // required
            // $table->string('visiterUserId')->nullable(); // user who visited the short link to get to the actual target destination

            $table->json('userLocationCoords')->nullable(); // { lat: '', long: '' }  geolocation
            $table->json('userDeviceInfo')->nullable(); // device
            $table->string('type')->nullable(); // click
            $table->string('userIP')->nullable(); // 


            $table->foreign('userId')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('shortLinkId')->references('id')->on('short_links')->onDelete('cascade');

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
        Schema::dropIfExists('s_l_analytics');
    }
};
