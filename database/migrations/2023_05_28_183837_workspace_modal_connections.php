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
        //
        Schema::create(
            'workspace_modal_connections',
            function (Blueprint $table) {
                $table->primary(['modal_id', 'modal_type', 'work_space_id']);
                $table->string('uniqueId')->nullable();
                $table->unsignedBigInteger('userId');
                $table->unsignedBigInteger('work_space_id');

                $table->morphs('modal');

                $table->foreign('work_space_id')
                    ->references('id')
                    ->on('work_spaces')
                    ->onDelete('cascade');

                $table->softDeletes();
                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workspace_modal_connections');
    }
};
