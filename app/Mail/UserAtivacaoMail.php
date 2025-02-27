<?php

namespace App\Mail;

use App\Models\UserAtivacao;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserAtivacaoMail extends Mailable
{
    use Queueable, SerializesModels;

    public UserAtivacao $userAtivacao;

    public function __construct(UserAtivacao $userAtivacao)
    {
        $this->userAtivacao = $userAtivacao;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: 'noreply@grupoimagetech.com.br',
            subject: 'GeoCertifica - Ativação da Conta',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.user.ativacao',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
