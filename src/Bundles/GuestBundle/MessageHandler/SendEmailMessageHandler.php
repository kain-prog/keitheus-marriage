<?php

namespace App\Bundles\GuestBundle\MessageHandler;

use App\Bundles\GuestBundle\Message\SendEmailMessage;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Email;

#[AsMessageHandler]
readonly class SendEmailMessageHandler
{
    public function __construct(private MailerInterface $mailer) {}

    /**
     * @throws TransportExceptionInterface
     */
    public function __invoke(SendEmailMessage $message): void
    {
        foreach ($message->to as $recipient) {
            $email = (new Email())
                ->from('contato@kaindev.com.br')
                ->to($recipient)
                ->subject($message->subject)
                ->html($message->htmlContent);

            $this->mailer->send($email);
        }
    }
}
