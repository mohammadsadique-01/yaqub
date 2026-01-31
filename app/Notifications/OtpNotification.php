<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OtpNotification extends Notification
{
    use Queueable;

    public function __construct(
        public int $otp,
        public string $type,
        public string $name,
        public string $email
    ) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $templateKey = match ($this->type) {
            'login' => 'two_factor_authentication_login',
            'signup' => 'two_factor_authentication_signup',
            'forgotpassword' => 'reset_password_otp',
        };

        $emailTemplate = getEmailTemplate($templateKey);

        $content = str_replace(
            ['[ClientName]', '[TwoFactorCode]', '[OTP]'],
            [$this->name, $this->otp, $this->otp],
            $emailTemplate['template']
        );

        return (new MailMessage)
            ->subject($emailTemplate['subject'])
            ->view('emailTemplate.mail', [
                'content' => $content,
                'name' => $this->name,
                'email' => $this->email,
            ]);
    }
}
