<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DashboardController
 * @extends AbstractDashboardController
 */
class DashboardController extends AbstractDashboardController
{
    /**
     * Main admin route
     *
     * @return Response
     */
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/admin_main.html.twig');
    }


    /**
     * Home admin route
     *
     * @return Response
     */
    #[Route('/admin/home', name: 'admin_home')]
    public function adminHome(): Response
    {
        $user = $this->getUser();
        $role = $this->getUser()->getRoles()[0];
        if($role === 'ROLE_GATE_MANAGER') {
            return $this->render('admin/gate/home.html.twig', [
                'user' => $user
            ]);
        } elseif ($role === 'ROLE_CHECK_IN_MANAGER') {
            return $this->render('admin/checkInManager/home.html.twig', [
                'user' => $user
            ]);
        } else {
            return $this->render('admin/supervisor/home.html.twig', [
                'user' => $user
            ]);
        }        
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('SymfonyAir');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
