<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class UserInvited extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(protected User $invitedUser, protected User $user)
    {
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('hello@bivouac.com', 'Regis from Bivouac'),
            replyTo: [
                new Address('hello@bivouac.com', 'Regis from Bivouac'),
            ],
            subject: trans('You are invited to Bivouac'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $url = URL::temporarySignedRoute('invitation.validate.show', now()->addDays(3), [
            'code' => $this->invitedUser->invitation_code,
        ]);

        return new Content(
            markdown: 'emails.user.invitation',
            with: [
                'userName' => $this->user->name,
                'url' => $url,
            ],
        );
    }
}
