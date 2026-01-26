<?php

namespace App\Mail;

use App\Models\InviteSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InviteSubmissionMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public InviteSubmission $submission)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New CS2 Date Invite Submission',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.invite-submission',
            with: [
                'submission' => $this->submission,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
