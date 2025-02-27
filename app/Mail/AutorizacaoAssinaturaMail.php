<?php

namespace App\Mail;

use App\Models\Assinatura;
use App\Models\Autorizacao;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AutorizacaoAssinaturaMail extends Mailable
{
    use Queueable, SerializesModels;

    public Autorizacao $autorizacao;

    public function __construct(Autorizacao $autorizacao)
    {
        $this->autorizacao = $autorizacao;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: 'noreply@grupoimagetech.com.br',
            subject: 'GeoCertifica - Código de Autorização de Assinatura',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.assinatura.autorizacao',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
