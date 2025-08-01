<?php

namespace App\Bundles\GuestBundle\Controller;

use App\Bundles\GuestBundle\Forms\GuestConfirmationType;
use App\Bundles\GuestBundle\UseCase\GuestUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class GuestController extends AbstractController
{
    public function __construct(
        private readonly GuestUseCase $guestUseCase,
        private Environment $twig
    ){}

    public function showPresenceForm(): Response
    {
        $form = $this->createForm(GuestConfirmationType::class);

        return $this->render('@Views/Presence/index.html.twig', [
            'form' => $form->createView(),
            'title' => 'KeiTheus - Confirmação de Presença',
        ]);
    }

    /**
     * @throws ExceptionInterface
     */
    public function confirmPresence(Request $request): JsonResponse {

        $form = $this->createForm(GuestConfirmationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->guestUseCase->handleGuestConfirmation(
                $form->get('guest')->getData(),
                $form->get('is_confirmed')->getData(),
                $form->get('companions_number')->getData(),
                $form->get('message')->getData(),

            );

            return new JsonResponse(['success' => true, 'message' => 'Confirmação registrada!']);
        }

        return new JsonResponse([
            'success' => false,
            'errors' => (string) $form->getErrors(true, false)
        ], 400);
    }
}
