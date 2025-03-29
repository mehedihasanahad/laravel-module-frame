<?php

namespace App\Core\Auth\Notifications;

use App\Core\User\Models\User;
use App\Libraries\Encryption;
use App\Libraries\SendMail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class Registered extends Notification
{
    use Queueable;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail(mixed $notifiable)
    {
        $temporaryUrl = URL::temporarySignedRoute(
            'set-password',
            now()->addMinutes(30),
            ['user' => Encryption::encodeId($this->user->id)]
        );
        return (new MailMessage)
            ->line('Your account has been created.')
            ->line('Now secure your account with a password.')
            ->line('This Link will be valid for 30 minutes.')
            ->action('Set Password', $temporaryUrl)
            ->line('Thank you for using our application!');

    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
