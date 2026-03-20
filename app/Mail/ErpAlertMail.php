<?php

namespace App\Mail;

use App\Models\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ErpAlertMail extends Mailable
{
    use Queueable;

    public function __construct(
        public Notification $notification,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[Rent ERP] '.$this->notification->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.erp-alert',
            with: [
                'notification' => $this->notification,
            ],
        );
    }
}
