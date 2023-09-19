<?php

namespace App\Mail;

use App\Models\Default\User;
use App\Models\Default\WorkSpace;
use App\Models\Default\WorkspaceTeam;
use App\Zaions\Helpers\ZHelpers;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MemberInvitationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        private User $user,
        private User $invitedUser,
        private WorkSpace $workspace,
        private $wilToken
    ) {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new \Illuminate\Mail\Mailables\Address('developer@zaions.com', 'MTI'),
            to: $this->invitedUser->email,
            subject: 'Member Invitation Mail',
        );
    }

    public function redirectUrl()
    {
        return env('FRONTEND_URL', '') . '/accept-invitation?token=' . $this->wilToken;
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.MemberInvitationMail',
            with: [
                'user' => $this->user,
                'invitedUser' => $this->invitedUser,
                'workspace' => $this->workspace,
                'redirectUrl' => $this->redirectUrl(),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
