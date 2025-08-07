<?php

namespace App\Bundles\AdminBundle\Controller;

use App\Bundles\CategoryBundle\Entity\Category;
use App\Bundles\GuestBundle\Entity\Guest;
use App\Bundles\GuestBundle\Repository\GuestRepository;
use App\Bundles\ProductBundle\Entity\Product;
use App\Bundles\ProductBundle\Repository\ProductRepository;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private readonly GuestRepository $guestRepository,
        private readonly ProductRepository $productRepository
    )
    {}

    public function index(): Response
    {


        return $this->render('@Bundles/AdminBundle/Resources/Views/Dashboard/index.html.twig',
        [
            'title' => 'Keitheus - Painel de Controle',
            'guests_confirmed' => $this->guestRepository->findBy([
                'is_confirmed' => true,
                'response' => true
            ]),
            'guests_not_confirmed' => $this->guestRepository->findBy([
                'is_confirmed' => false,
                'response' => true
            ]),

            'totalPrice' => $this->productRepository->getTotalPrice()
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Painel de Controle');
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),
            MenuItem::linkToCrud('Convidados', 'fas fa-people-group', Guest::class),

            MenuItem::section('Presentes'),
                MenuItem::linkToCrud('Categorias', 'fas fa-list', Category::class),
                MenuItem::linkToCrud('Produtos', 'fas fa-gifts', Product::class),
        ];

    }
}
