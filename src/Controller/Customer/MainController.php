<?php

declare(strict_types=1);

namespace App\Controller\Customer;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MainController
 * @extends AbstractController
 */
class MainController extends AbstractController
{
    /**
     * Welcome route
     *
     * @return Response
     */
    #[Route('/', name: 'welcome')]
    public function welcome(): Response
    {
        $user = $this->getUser();
        if ((bool)$user) {
            return $this->render('welcome.html.twig', [
                'user' => $user
            ]);
        }

        return $this->render('welcome.html.twig', [
            'user' => null
        ]);
    }

    /**
     * Personal account route
     *
     * @return Response
     */
    #[Route('/pesronal', name: 'personal_account')]
    public function personalAccount(): Response
    {
        $user = $this->getUser();
        return $this->render('/customer/personal_account.html.twig', [
            'user' => $user
        ]);
    }

    #[Route('/personal/your_tickets', name: 'your_tickets')]
    public function yourTickets() : Response
    {
        $user = $this->getUser(); 
        return $this->render('/customer/your_tickets.html.twig', [
            'tickets' => $user->getTickets()
        ]);     
    }
}
