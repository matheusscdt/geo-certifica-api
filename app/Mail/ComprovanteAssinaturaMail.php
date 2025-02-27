<?php

namespace App\Mail;

use App\Models\Assinatura;
use App\Models\Autorizacao;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ComprovanteAssinaturaMail extends Mailable
{
    use Queueable, SerializesModels;

    public Assinatura $assinatura;

    public function __construct(Assinatura $assinatura)
    {
        $this->assinatura = $assinatura;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: 'noreply@grupoimagetech.com.br',
            subject: 'GeoCertifica - Comprovante de Assinatura',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.assinatura.comprovante',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
