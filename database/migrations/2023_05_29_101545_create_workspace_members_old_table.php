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
        Schema::create('workspace_members_old', function (Blueprint $table) {
            $table->primary(['user_id', 'work_space_id']);

            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('work_space_id');
            $table->integer('roleId');

            $table->foreign('work_space_id')->references('id')->on('work_spaces')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workspace_members');
    }
};
