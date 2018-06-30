<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ConfirmEmail extends Notification {
    public $id;
    public $token;

    public function __construct($id, $token) {
        $this->id = $id;
        $this->token = $token;
    }

    public function via($notifiable) {
        return ['mail'];
    }

    public function toMail($notifiable) {
        return (new MailMessage)
            ->subject(config('app.name') . ': ' . __('emails.email-confirm_subject'))
            ->line(__('emails.email-confirm_line1'))
            ->action(__('emails.email-confirm_action'), route('register.confirm', [$this->id, $this->token]))
            ->line(__('emails.email-confirm_line2'));
    }

}
