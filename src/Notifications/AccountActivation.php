<?php namespace GeneaLabs\LaravelRegistrar\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AccountActivation extends Notification implements ShouldQueue
{
    use Queueable;

    public function via() : array
    {
        return ['mail'];
    }

    public function toMail($notifiable) : MailMessage
    {
        $url = url("/register/activate/{$notifiable->activation_token}");
        $intro = "Your email has been used to register an account. Before ";
        $intro .= "enabling the account, we would like to make sure it's ";
        $intro .= "really you.";
        $outro = "If you did not initiate this request, please reply to this ";
        $outro .= "email. We take your privacy very seriously.";

        return (new MailMessage)
            ->success()
            ->subject('Account Activation')
            ->line($intro)
            ->action('Activate Your Account', $url)
            ->line($outro)
            ->line('Regards,<br>Your ' . config('app.name') . ' Team');
    }
}
