<?php

namespace App\Bundles\GuestBundle\UseCase;

use App\Bundles\GuestBundle\Entity\Guest;
use App\Bundles\GuestBundle\Message\SendEmailMessage;
use App\Bundles\GuestBundle\Repository\GuestRepository;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

readonly class GuestUseCase
{
    public function __construct(
        private GuestRepository $guestRepository,
        private MessageBusInterface $bus
    ) {}


    /**
     * @throws ExceptionInterface
     */
    public function handleGuestConfirmation(?Guest $guest, bool $isConfirmed, int $companions_number, ?string $message): void
    {
        if (!$guest) {
            throw new \InvalidArgumentException('Convidado inválido.');
        }

        $guest->setIsConfirmed($isConfirmed);
        $guest->setCompanionsNumber($companions_number);

        $this->guestRepository->save($guest);

        if($isConfirmed) {
            $isConfirmed = 'Sim';
        }else{
            $isConfirmed = 'Não';
        }

        $this->bus->dispatch(new SendEmailMessage(
            ['mtheusmss@gmail.com', 'keillacarolina2013@gmail.com'],
            'Confirmação de presença recebida!',
            "
                <p><strong>Convidado:</strong> {$guest->getName()}</p>
                <p><strong>Números de Acompanhantes:</strong> {$companions_number}</p>
                <p><strong>Vai comparecer?</strong> {$isConfirmed}</p>
                <p><strong>Mensagem:</strong> {$message}</p>

            "
        ));
    }
}
