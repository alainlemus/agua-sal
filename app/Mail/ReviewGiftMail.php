<?php

namespace App\Mail;

use App\Models\ReviewSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReviewGiftMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ReviewSubmission $submission) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎁 ¡Tu regalo de ' . siteName() . ' te espera!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.review-gift',
        );
    }
}
