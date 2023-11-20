<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Repository\OptionRepository;
use App\Repository\SoldTicketRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CheckInController
 * @extends AbstractController
 */
class CheckInController extends AbstractController
{
    /**
     * CheckIn passenger route
     *
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/checkInManager/checkInPassenger', name: 'checkIn_passenger')]
    public function checkInPassenger(Request $request): Response
    {
        if ($request->get('ticketNumber')) {
            return $this->redirectToRoute('ticket_information', ['ticketNumber' => $request->get('ticketNumber')]);
        }

        return $this->render('admin/checkInManager/checkInForm.html.twig');
    }

    /**
     * Ticket's information route
     *
     * @param int $ticketNumber
     * @param SoldlTicketRepository $ticketRepository
     * @return Response
     */
    #[Route('/admin/checkInManager/ticket/{ticketNumber}', name: 'ticket_information')]
    public function ticketInformation(int $ticketNumber, SoldTicketRepository $ticketRepository): Response
    {
        $ticket = $ticketRepository->findByTicketNumber((int)$ticketNumber);

        return $this->render('admin/checkInManager/ticketInformation.html.twig', [
            'ticket' => $ticket
        ]);
    }

    /**
     * Confirm checkIn route
     *
     * @param int $ticketNumber
     * @param SoldTicketRepository $ticketRepository
     * @return Response
     */
    #[Route('/admin/checkInManager/ticket/{ticketNumber}/confirm', name: 'confirm_checkIn')]
    public function confirmCheckIn(int $ticketNumber, SoldTicketRepository $ticketRepository, EntityManagerInterface $entityManager): Response
    {
        $ticket = $ticketRepository->findByTicketNumber((int)$ticketNumber);
        $ticket->checkIn();
        $entityManager->flush();

        return $this->redirectToRoute('checkIn_passenger');
    }

    /**
     * Ticket's options route
     *
     * @param int $ticketNumber
     * @param SoldTicketRepository $ticketRepository
     * @param OptionRepository $optionRepository
     * @return Response
     */
    #[Route('/admin/checkInManager/ticket/{ticketNumber}/ticketOptions', name: 'ticket_options')]
    public function ticketOptions(int $ticketNumber, SoldTicketRepository $ticketRepository, OptionRepository $optionRepository): Response
    {
        $ticket = $ticketRepository->findByTicketNumber($ticketNumber);
        $options = $optionRepository->findAll();

        return $this->render('admin/checkInManager/addOptions.html.twig', [
            'ticketOptions' => $ticket->getOptions(),
            'ticketNumber' => $ticket->getTicketNumber(),
            'options' => $options
        ]);
    }

    /**
     * Add options route
     *
     * @param int $ticketNumber
     * @param int $optionId
     * @param SoldTicketRepository $ticketRepository
     * @param OptionRepository $optionRepository
     * @return Response
     */
    #[Route('/admin/checkInManager/ticket/{ticketNumber}/addOptions/{optionId}', name: 'add_options')]
    public function addOptions(int $ticketNumber, int $optionId, SoldTicketRepository $ticketRepository, OptionRepository $optionRepository): Response
    {
        $ticket = $ticketRepository->findByTicketNumber($ticketNumber);
        $option = $optionRepository->find($optionId);

        $ticket->addOption($option);

        return $this->redirectToRoute('ticket_options');
    }
}
