<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class UserAlertNotification extends Notification
{
    use Queueable;

    public $title;
    public $message;
    public $link;
    public $type; // 'payment', 'profile', 'late_payment', etc.

    public function __construct($title, $message, $type = 'general', $link = null)
    {
        $this->title = $title;
        $this->message = $message;
        $this->link = $link;
        $this->type = $type;
    }

    public function via($notifiable)
    {
        $channels = ['database', 'broadcast']; // default: in-app + real-time

        // send email only for:
        if (($this->type === 'payment' || 
            $this->type === 'obligation' ||
            $this->type === 'epayment' ||
            $this->title === __('EPayment Done') ||
            $this->title === 'EPayment Done' ||
            $this->title === 'Divorce Case Created' ||
            $this->title === __('Divorce Case Created')) 
        && !empty($notifiable->email)) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'link' => $this->link,
            'type' => $this->type,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'title' => $this->title,
            'message' => $this->message,
            'link' => $this->link,
            'type' => $this->type,
        ]);
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject($this->title)
                    ->line($this->message)
                    ->action('View Details', $this->link ?? url('/'));
    }
}
