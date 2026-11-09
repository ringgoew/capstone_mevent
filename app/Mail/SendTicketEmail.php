<?php

namespace App\Mail;

use App\Services\TicketQrService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendTicketEmail extends Mailable
{
    use Queueable, SerializesModels;

    public string $qrPng;

    // Parameter kedua tetap ada agar pemanggilan lama (..., null) tidak error.
    public function __construct(public $order, public $extra = null)
    {
        $this->qrPng = TicketQrService::png($order->order_code);
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'E-Tiket ' . $this->order->event_title . ' - ' . $this->order->order_code);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.ticket');
    }

    /** QR juga dilampirkan sebagai file supaya bisa disimpan/dicetak. */
    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => $this->qrPng, 'qr-' . $this->order->order_code . '.png')
                ->withMime('image/png'),
        ];
    }
}
