<?php

use App\Zaions\Enums\HistoryTypeEnum;
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
        Schema::create('histories', function (Blueprint $table) {
            $table->id();
            $table->string('uniqueId')->nullable();
            $table->unsignedBigInteger('userId');
            $table->unsignedBigInteger('taskId');

            $table->string('type')->default(HistoryTypeEnum::courseUpdate->name)->nullable();
            $table->integer('timeSpendOnCourse')->default(0)->nullable();
            $table->integer('timeSpendOnOfficeTask')->default(0)->nullable();
            $table->string('detail')->default(0)->nullable();

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
        Schema::dropIfExists('histories');
    }
};
