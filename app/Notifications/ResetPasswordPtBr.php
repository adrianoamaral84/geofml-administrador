<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordPtBr extends Notification
{
    use Queueable;

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $publicUrl = rtrim(
            (string) config('app.geofml_internet_url', env('GEOFML_INTERNET_URL', 'https://geofml.5rm.eb.mil.br')),
            '/'
        );

        $url = $publicUrl
            . '/password/reset/' . urlencode($this->token)
            . '?email=' . urlencode($notifiable->email);

        $expire = config('auth.passwords.users.expire', 60);

        return (new MailMessage)
            ->subject('Redefinição de senha - GEOFML')
            ->greeting('Olá!')
            ->line('Você está recebendo este e-mail porque foi solicitada uma redefinição de senha para a sua conta.')
            ->action('Redefinir senha', $url)
            ->line("Este link de redefinição de senha expira em {$expire} minutos.")
            ->line('Se você não solicitou a redefinição de senha, nenhuma ação é necessária.')
            ->salutation('Atenciosamente, Administração do Sistema GEOFML');
    }
}
