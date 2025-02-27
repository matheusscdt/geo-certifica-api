<?php

namespace App\Mail;

use App\Models\User;
use App\Models\UserAtivacao;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserConfirmacaoMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: 'noreply@grupoimagetech.com.br',
            subject: 'GeoCertifica - Confirmação da ativação da conta',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.user.confirmacao',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
