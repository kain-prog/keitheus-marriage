<?php

namespace App\Bundles\AuthBundle\Controller;

use App\Bundles\AuthBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthController extends AbstractDashboardController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, Security $security): Response
    {
        if ($security->getUser()) {
            return $this->redirectToRoute('admin');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('@Bundles/AuthBundle/Resources/Views/Login/login.html.twig',
            ['last_username' => $lastUsername, 'error' => $error, 'remember_me_enabled' => true]
        );
    }

    #[Route(path: '/login_check', name: 'login_check')]
    public function loginCheck(): ?Response
    {
        return $this->redirectToRoute('admin');
    }

    #[Route(path: '/register', name: 'app_register')]
    public function register(EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $user->setUsername('teste');

        $plainPassword = '123456';

        $hasher = $passwordHasher->hashPassword($user, $plainPassword);
        $user->setPassword($hasher);

        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('admin');
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
