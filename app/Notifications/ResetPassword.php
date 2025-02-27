<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPassword extends Notification
{
    use Queueable;

    private User $user;

    private $token;

    public function __construct(User $user, $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $link = env('APP_URL_FRONT_END').'/trocar-senha?token='.$this->token.'&email='.$this->user->email;

        return new MailMessage()
                    ->subject('GeoCertifica - Recuperação de senha')
                    ->view('emails.auth.reset-password', [
                        'user' => $this->user,
                        'token' => $this->token,
                        'link' => $link
                    ]);
    }

    public function toArray(object $notifiable): array
    {
        return [

        ];
    }
}
