<?php

use App\Zaions\Enums\SignUpTypeEnum;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('uniqueId')->nullable();
            $table->string('name')->nullable();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('description')->nullable();
            $table->string('website')->nullable();
            $table->string('country')->nullable();
            $table->string('language')->nullable();
            $table->string('city')->nullable();
            $table->string('username')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();

            // new fields
            $table->string('slug')->nullable();
            $table->json('profileImage')->nullable(); // use to store user profile detail json, for example json containing filePath, fileUrl, etc.
            $table->string('avatar')->nullable(); // use to store one fileUrl so where we need just url we will get from here.
            $table->string('phoneNumber')->nullable();
            $table->integer('dailyMinOfficeTime')->default(8)->min(3)->max(12)->nullable();
            $table->integer('dailyMinOfficeTimeActivity')->default(85)->min(75)->max(100)->nullable();
            $table->string('timezone')->nullable();
            $table->string('address')->nullable();
            $table->string('signUpType')->default(SignUpTypeEnum::normal->value)->nullable();

            $table->string('OTPCode')->nullable()->max(6);
            $table->dateTime('OTPCodeValidTill')->nullable();
            $table->dateTime('lastSeenAt')->nullable();
            $table->boolean('isSignupCompleted')->default(false)->nullable();
            $table->boolean('isOnboardingCompleted')->default(false)->nullable();

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
        Schema::dropIfExists('users');
    }
};
