<?php

namespace Botble\Member\Notifications;

use EmailHandler;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class ResetPasswordNotification extends Notification
{
    /**
     * The password reset token.
     */
    public string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        $emailHandler = EmailHandler::setModule(MEMBER_MODULE_SCREEN_NAME)
            ->setType('plugins')
            ->setTemplate('password-reminder')
            ->setVariableValue('reset_link', route('public.member.password.reset', ['token' => $this->token]));

        return (new MailMessage())
            ->view(['html' => new HtmlString($emailHandler->getContent())])
            ->subject($emailHandler->getSubject());
    }
}
