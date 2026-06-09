<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $resetUrl;
    public string $userName;
    public int $expireMinutes;

    /**
     * Create a new message instance.
     */
    public function __construct(string $resetUrl, string $userName, int $expireMinutes = 60)
    {
        $this->resetUrl = $resetUrl;
        $this->userName = $userName;
        $this->expireMinutes = $expireMinutes;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('passwords.email_subject'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'auth.emails.password_reset',
            with: [
                'resetUrl'      => $this->resetUrl,
                'userName'      => $this->userName,
                'expireMinutes' => $this->expireMinutes,
            ],
        );
    }
}
