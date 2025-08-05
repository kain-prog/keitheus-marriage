<?php

namespace App\Bundles\AdminBundle\Controller;

use App\Bundles\CategoryBundle\Entity\Category;
use App\Bundles\GuestBundle\Entity\Guest;
use App\Bundles\ProductBundle\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        return $this->render('@Templates/EasyAdmin/layout.html.twig');
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
