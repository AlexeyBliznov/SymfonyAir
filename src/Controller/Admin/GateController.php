<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Repository\SoldTicketRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class GateController
 * @extends AbstractController
 */
class GateController extends AbstractController
{
    /**
     * Register boarding route
     *
     * @return Response
     */
    #[Route('/admin/gate/register', name: 'register_boarding')]
    public function registerBoarding(): Response
    {
        $user = $this->getUser();

        return $this->render('admin/gate/register_boarding.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * Information about boarding
     *
     * @param Request $request
     * @param SoldTicketRepository $ticketRepository
     * @return Response
     */
    #[Route('/admin/gate/register/information', name: 'information_boarding')]
    public function informationBoarding(Request $request, SoldTicketRepository $ticketRepository): Response
    {
        $user = $this->getUser();

        $ticket = $ticketRepository->findByTicketNumber((int)$request->get('number'));
        $flight = $ticket->getFlight();
        $passenger = $ticket->getUser();

        return $this->render('admin/gate/information_boarding.html.twig', [
            'user' => $user,
            'ticket' => $ticket,
            'flight' => $flight,
            'passenger' => $passenger
        ]);
    }

    /**
     * Confirm boarding
     *
     * @param string $ticketId
     * @param SoldTicketRepository $ticketRepository
     * @return Response
     */
    #[Route('/admin/gate/register/confirm/{ticketId}', name: 'confirm_boarding')]
    public function confirmBoarding(string $ticketId, SoldTicketRepository $ticketRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        $ticket = $ticketRepository->find($ticketId);
        $ticket->confirmBoarding();
        $entityManager->flush();

        return $this->redirectToRoute('register_boarding');
    }
}
