<?php

namespace App\Bundles\GuestBundle\Route;

use App\Bundles\GuestBundle\Controller\GuestController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Routing\Attribute\Route;

final class GuestRoute extends AbstractController
{
    public function __construct( private readonly GuestController $guestController)
    {}

    /**
     * @throws ExceptionInterface
     */
    #[Route('/confirmar-presenca', name: 'app_confirm_presence', methods: ['POST'])]
    public function confirmPresence(Request $request): Response
    {
        return $this->guestController->confirmPresence($request);
    }
}
