<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InviteOpenedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $token,
        public ?string $ipAddress,
        public ?string $userAgent,
        public string $openedAt,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invite Opened',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.invite-opened',
            with: [
                'token' => $this->token,
                'ipAddress' => $this->ipAddress,
                'userAgent' => $this->userAgent,
                'openedAt' => $this->openedAt,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
