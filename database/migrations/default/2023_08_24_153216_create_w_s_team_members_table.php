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
        Schema::create('w_s_team_members', function (Blueprint $table) {
            $table->id();
            $table->string('wilToken', 1000)->nullable(); // wilToken -> workspaceInviteLinkToken.
            $table->string('uniqueId');
            $table->unsignedBigInteger('userId'); // (user how created this request)
            $table->unsignedBigInteger('workspaceId'); // (workspace whom the team belongs)
            // $table->unsignedBigInteger('teamId'); // (team of workspace where member is invited)
            $table->unsignedBigInteger('memberRoleId'); // (role id to assign member)
            $table->unsignedBigInteger('memberId')->nullable(); // (user to whom this request is sent, so he can join this team)
            $table->dateTime('resendAllowedAfter')->nullable(); // will shore time. after this time pass user can resend the invitation

            $table->string('email'); // (email of member)
            $table->string('accountStatus'); // (request status)
            $table->string('accountStatusUpdaterRemarks')->nullable();
            $table->dateTime('invitedAt'); // (invitedAt timestamp)
            $table->dateTime('accountStatusLastUpdatedBy')->nullable();
            $table->dateTime('inviteAcceptedAt')->nullable(); // (request accepted time)
            $table->dateTime('inviteRejectedAt')->nullable(); // (request rejected time)


            $table->integer('sortOrderNo')->default(0)->nullable();
            $table->boolean('isActive')->default(false)->nullable();
            $table->boolean('isFavorite')->default(false)->nullable();
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
        Schema::dropIfExists('w_s_team_members');
    }
};
