<?php

namespace App\Bundles\GuestBundle\UseCase;

use App\Bundles\GuestBundle\Entity\Companion;
use App\Bundles\GuestBundle\Entity\Guest;
use App\Bundles\GuestBundle\Message\SendEmailMessage;
use App\Bundles\GuestBundle\Repository\CompanionRepository;
use App\Bundles\GuestBundle\Repository\GuestRepository;
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
        private CompanionRepository $companionRepository,
        private MessageBusInterface $bus,
        private Environment $twig,
    ) {}


    /**
     * @throws ExceptionInterface
     * @throws \Exception
     */
    public function handleGuestConfirmation(?Guest $guest, bool $isConfirmed, ?string $guestNotCome, ?string $message): void
    {
        if (!$guest) {
            throw new \InvalidArgumentException('Convidado inválido.');
        }

        if($guest->getResponse()){
            throw new \InvalidArgumentException('Convidado já validou a presença.');
        }

        $guest->setIsConfirmed($isConfirmed);
//        $guest->setCompanionsNumber($companions_number);
        $guest->setGuestNotCome($guestNotCome);
        $guest->setMessage($message);
        $guest->setResponse(true);

//        foreach ($companions_list as $companion) {
//
//            $newCompanion = new Companion();
//
//            $newCompanion->setName($companion['name']);
//            $newCompanion->setIsChild($companion['child']);
//            $newCompanion->addGuest($guest);
//
//            $guest->addCompanion($newCompanion);
//
//            $this->companionRepository->save($newCompanion);
//        }

        $this->guestRepository->save($guest);

        try {
            $html = $this->twig->render('@Resources/Mail/Presence/index.html.twig', [
                'title' => 'KeiTheus - Casamento',
                'confirmed' => $isConfirmed,
                'name' => $guest->getName(),
                'not_come' => $guestNotCome,
//                'companions_number' => $guest->getCompanionsNumber(),
//                'companions' => $companions_list ?? [],
                'message' => $message,
            ]);

            $this->bus->dispatch(new SendEmailMessage(
                ['mtheusmss@gmail.com', 'keillacarolina2013@gmail.com'],
                'Confirmação de presença recebida!',
                $html
            ));
        } catch (RuntimeError|LoaderError|SyntaxError $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
