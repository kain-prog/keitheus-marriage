<?php

namespace App\Bundles\GuestBundle\UseCase;

use App\Bundles\GuestBundle\Entity\Companion;
use App\Bundles\GuestBundle\Entity\Guest;
use App\Bundles\GuestBundle\Message\SendEmailMessage;
use App\Bundles\GuestBundle\Repository\CompanionRepository;
use App\Bundles\GuestBundle\Repository\GuestRepository;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

readonly class GuestUseCase
{
    public function __construct(
        private GuestRepository $guestRepository,
        private CompanionRepository $companionRepository,
        private MessageBusInterface $bus
    ) {}


    /**
     * @throws ExceptionInterface
     */
    public function handleGuestConfirmation(?Guest $guest, bool $isConfirmed, int $companions_number, ?array $companions_list, ?string $message): void
    {
        if (!$guest) {
            throw new \InvalidArgumentException('Convidado inválido.');
        }

        if($guest->getResponse()){
            throw new \InvalidArgumentException('Convidado já validou a presença.');
        }

        $guest->setIsConfirmed($isConfirmed);
        $guest->setCompanionsNumber($companions_number);
        $guest->setMessage($message);
        $guest->setResponse(true);

        $companions = array();

        foreach ($companions_list as $companion) {

            $newCompanion = new Companion();

            $newCompanion->setName($companion->name);
            $newCompanion->setIsChild($companion->child);
            $newCompanion->addGuest($guest);

            $guest->addCompanion($newCompanion);

            $this->companionRepository->save($newCompanion);

            $companions[] = $newCompanion;
        }

        $this->guestRepository->save($guest);

        if($isConfirmed) {
            $isConfirmed = 'Sim';
        }else{
            $isConfirmed = 'Não';
        }

        $this->bus->dispatch(new SendEmailMessage(
            ['mtheusmss@gmail.com'],
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
