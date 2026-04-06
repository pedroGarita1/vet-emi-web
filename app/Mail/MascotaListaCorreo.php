<?php

namespace App\Mail;

use App\Models\EsteticaService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MascotaListaCorreo extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public EsteticaService $service,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tu mascota ya esta lista - Emi Veterinaria',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mails.mascota-lista',
            with: [
                'service' => $this->service,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
