<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ConfirmEmail extends Notification
{
    private $id;
    private $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(config('APP_NAME') . ': ' . __('emails.email-confirm_subject'))
            ->line(__('emails.email-confirm_line1'))
            ->action(__('emails.email-confirm_action'), route('register.confirm', ['id' => $this->token, 'token' => $this->token]))
            ->line(__('emails.email-confirm_line2'));
    }

}
