<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class SendTicketEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $pdfPath;

    // Terima data order dan path file PDF tiket
    public function __construct($order, $pdfPath = null)
    {
        $this->order = $order;
        $this->pdfPath = $pdfPath;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'E-Ticket Pembayaran Berhasil - ' . $this->order->order_code,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.ticket', // Buat file blade di resources/views/emails/ticket.blade.php
        );
    }

    public function attachments(): array
    {
        if ($this->pdfPath && file_exists($this->pdfPath)) {
            return [
                Attachment::fromPath($this->pdfPath)
                    ->as('E-Ticket-' . $this->order->order_code . '.pdf')
                    ->withMime('application/pdf'),
            ];
        }

        return [];
    }
}