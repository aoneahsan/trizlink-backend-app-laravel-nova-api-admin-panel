<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// this is test model migration so not adding the user relation

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('z_testing_demos', function (Blueprint $table) {
            $table->id();
            $table->string('uniqueId')->nullable();

            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // new fields
            $table->string('slug')->nullable();
            $table->string('profilePitcher')->nullable();
            $table->string('phoneNumber')->nullable();
            $table->integer('dailyMinOfficeTime')->default(8)->min(3)->max(12)->nullable();
            $table->integer('dailyMinOfficeTimeActivity')->default(85)->min(75)->max(100)->nullable();

            $table->string('timezone')->nullable();
            $table->string('address')->nullable();
            $table->text('flexableContent')->nullable();
            $table->json('jsonFieldContent')->nullable();
            $table->text('notesFieldData')->nullable();
            $table->json('openingHoursData')->nullable();
            $table->json('unlayerEmailMakerField')->nullable();
            $table->text('randomDataTesting')->nullable();
            $table->string('heroIconField')->nullable();
            $table->string('qrField')->nullable();
            $table->string('GooglePolygonfield')->nullable();


            $table->point('map-field-location')->nullable();
            $table->polygon('map-field-area')->nullable();
            $table->multiPolygon('map-field-areas')->nullable();

            $table->string('Indicatorfield')->nullable();
            $table->string('Tooltipfield')->nullable();

            $table->boolean('isActive')->default(true);
            $table->integer('sortOrderNo')->default(0)->nullable();
            $table->json('extraAttributes')->nullable();
            $table->softDeletes();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('z_testing_demos');
    }
};
