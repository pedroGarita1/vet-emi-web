<?php

namespace App\Mail;

use App\Models\Notificacion;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EnviarAvisoPorCorreo extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Notificacion $notificacion,
        public string $nombreCliente,
    ) {}

    public function envelope(): Envelope
    {
        $titulo = $this->notificacion->titulo;

        return new Envelope(
            subject: $titulo,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mails.aviso-notificacion',
            with: [
                'notificacion' => $this->notificacion,
                'nombreCliente' => $this->nombreCliente,
                'tipo' => $this->obtenerTipoAviso(),
            ]
        );
    }

    /**
     * Obtener el nombre legible del tipo de aviso
     */
    private function obtenerTipoAviso(): string
    {
        return match($this->notificacion->tipo) {
            'promocion' => '🎉 Promoción',
            'cierre' => '⏰ Aviso de Cierre',
            'aviso' => '📢 Aviso Importante',
            default => '📬 Notificación',
        };
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
