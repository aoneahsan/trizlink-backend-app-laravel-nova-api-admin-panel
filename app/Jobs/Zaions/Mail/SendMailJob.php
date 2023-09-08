<?php

namespace App\Jobs\Zaions\Mail;

use App\Mail\MemberInvitationMail;
use App\Mail\OTPMail;
use App\Models\Default\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(
        $currentUser,
        $memberUser,
        $workspace,
        $team,
        $urlSafeEncodedId
    ): void {
        //
        // Mail::send(new OTPMail($this->user));
        Mail::send(new MemberInvitationMail(
            $currentUser,
            $memberUser,
            $workspace,
            $team,
            $urlSafeEncodedId
        ));
    }
}
