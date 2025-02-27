<?php

namespace App\Mail;

use App\Models\Destinatario;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SolicitacaoAssinaturaMail extends Mailable
{
    use Queueable, SerializesModels;

    public Destinatario $destinatario;

    public function __construct(Destinatario $destinatario)
    {
        $this->destinatario = $destinatario;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: 'noreply@grupoimagetech.com.br',
            subject: 'GeoCertifica - Solicitação de Assinatura',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.assinatura.solicitacao',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
