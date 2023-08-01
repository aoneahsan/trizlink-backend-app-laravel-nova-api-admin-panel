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
        Schema::create('embeded_widgets', function (Blueprint $table) {
            $table->id();
            $table->string('uniqueId')->nullable();
            $table->unsignedBigInteger('userId');
            $table->unsignedBigInteger('shortLinkId');

            $table->string('name')->nullable();
            $table->boolean('canCodeJs')->default(false)->nullable();
            $table->boolean('canCodeHtml')->default(false)->nullable();
            $table->text('jsCode')->nullable();
            $table->text('HTMLCode')->nullable();
            $table->string('displayAt')->nullable();
            $table->string('delay')->nullable();
            $table->string('position')->nullable();
            $table->boolean('animation')->default(false)->nullable();
            $table->boolean('closingOption')->default(false)->nullable();

            $table->boolean('isActive')->default(true)->nullable();
            $table->integer('sortOrderNo')->default(0)->nullable();
            $table->json('extraAttributes')->nullable();

            $table->foreign('userId')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('shortLinkId')->references('id')->on('short_links')->onDelete('cascade');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('embeded_widgets');
    }
};
