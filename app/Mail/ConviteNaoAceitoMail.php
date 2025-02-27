<?php

namespace App\Mail;

use App\Models\Convite;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ConviteNaoAceitoMail extends Mailable
{
    use Queueable, SerializesModels;

    public Convite $convite;

    public function __construct(Convite $convite)
    {
        $this->convite = $convite;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: 'noreply@grupoimagetech.com.br',
            subject: 'GeoCertifica - Convite para cadastro',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.convite.nao-aceito',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
