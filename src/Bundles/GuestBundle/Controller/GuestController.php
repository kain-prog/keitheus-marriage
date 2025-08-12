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
        private readonly GuestUseCase $guestUseCase
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
    public function confirmPresence(Request $request): JsonResponse
    {

        $form = $this->createForm(GuestConfirmationType::class);
        $form->handleRequest($request);

        try {

            if ($form->isSubmitted() && $form->isValid()) {

                $guest = $form->get('guest')->getData();
                $isConfirmed = $form->get('is_confirmed')->getData();
                $companionsNumber = $form->get('companions_number')->getData();
                $companionsListJson = $form->get('companions_list')->getData();
                $message = $form->get('message')->getData();

                $companionsList = !empty($companionsListJson) ? json_decode($companionsListJson, true) : [];

                $this->guestUseCase->handleGuestConfirmation(
                    $guest,
                    $isConfirmed,
                    $companionsNumber,
                    $companionsList,
                    $message,

                );

                return new JsonResponse(['success' => true, 'message' => 'Confirmação registrada!']);
            }

        }catch (\Exception $exception){

            return new JsonResponse([
                'success' => false,
                'errors' => (string) $exception->getMessage(),
            ], 400);
        }

        return new JsonResponse([
            'success' => false,
            'errors' => (string) $form->getErrors(true, false)
        ], 400);
    }
}
