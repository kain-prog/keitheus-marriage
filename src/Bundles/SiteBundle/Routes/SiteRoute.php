<?php

namespace App\Bundles\SiteBundle\Routes;

use App\Bundles\GuestBundle\Controller\GuestController;
use App\Bundles\GuestBundle\Message\SendEmailMessage;
use App\Bundles\ProductBundle\Controller\ProductController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

final class SiteRoute extends AbstractController
{
    public function __construct(
        private MailerInterface $mailer,
        private readonly GuestController $guestController,
        private readonly ProductController $productController,
    )
    {}

    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        return $this->render('@Views/Home/index.html.twig', [
            'title' => 'KeiTheus',
        ]);
    }

    #[Route('/presenca', name: 'app_presence')]
    public function presence(): Response
    {
        return $this->guestController->showPresenceForm();
    }

    #[Route('/presentes', name: 'app_gift')]
    public function gift(): Response
    {
        return $this->productController->execute();
    }

    #[Route('/informacoes', name: 'app_information')]
    public function information(): Response
    {
        return $this->render('@Views/Informations/index.html.twig', [
            'title' => 'KeiTheus - Informações',
        ]);
    }
}

