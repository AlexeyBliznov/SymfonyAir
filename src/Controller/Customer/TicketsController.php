<?php

declare(strict_types=1);

namespace App\Controller\Customer;

use App\Entity\Baggage;
use App\Entity\SoldTicket;
use App\UseCase;
use App\Repository\FlightRepository;
use App\Repository\OptionRepository;
use App\Repository\TicketRepository;
use App\Service\MailTicketSender;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TicketsController
 * @extends AbstractController
 */
class TicketsController extends AbstractController
{
    /**
     * Search tickets route
     *
     * @param Request $request
     * @param FlightRepository $flightRepository 
     * @return Response
     */
    #[Route('/search', name: 'search_tickets')]
    public function searchTickets(Request $request, FlightRepository $flightRepository): Response
    {
        if ($request->get('from')&$request->get('to')&$request->get('date')) {
            $flights = $flightRepository->searchFlight($request->get('from'), $request->get('to'), $request->get('date'));
            return $this->render('customer/searchTickets.html.twig', [
                'flights' => $flights
            ]);
        } elseif ($request->get('from')&$request->get('to')) {
            $flights = $flightRepository->searchFlightByFromAndTo($request->get('from'), $request->get('to'));
            return $this->render('customer/searchTickets.html.twig', [
                'flights' => $flights
            ]);
        } elseif ($request->get('from')&$request->get('date')) {
            $flights = $flightRepository->searchFlightByFromAndDate($request->get('from'), $request->get('date'));
            return $this->render('customer/searchTickets.html.twig', [
                'flights' => $flights
            ]);
        } elseif ($request->get('from')) {
            $flights = $flightRepository->searchFlightByFrom($request->get('from'));
            return $this->render('customer/searchTickets.html.twig', [
                'flights' => $flights
            ]);
        } elseif ($request->get('date')) {
            $flights = $flightRepository->searchFlightByDate($request->get('date'));
            return $this->render('customer/searchTickets.html.twig', [
                'flights' => $flights
            ]);
        }

        return $this->redirectToRoute('welcome');
    }
    
    /**
     * Buy ticket route
     *
     * @param int $flight
     * @param FlightRepository $flightRepository
     * @param TicketRepository $ticketRepository
     * @return Response
     */
    #[Route('/chooseTicket/{flight}', name: 'choose_ticket')]
    public function buy(
        int $flight,
        FlightRepository $flightRepository,
        TicketRepository $ticketRepository
    ): Response
    {
        $flightInfo = $flightRepository->findById($flight);
        $tickets = $ticketRepository->findByFlight($flightInfo);

        return $this->render('customer/informationAboutFlight.html.twig', [
            'flight' => $flightInfo,
            'tickets' => $tickets
        ]);
    }

    /**
     * Add options route
     * 
     * @param Request $request
     * @param int $ticket
     * @param OptionRepository $optionRepository
     * @param TicketRepository $ticketRepository
     * @return Response
     */
    #[Route('/addOptions/{ticket}', name: 'add_options')]
    public function choose(Request $request, int $ticket, OptionRepository $optionRepository, TicketRepository $ticketRepository): Response
    {
        $command = new UseCase\Customer\AddOptions\Command();
        $form = $this->createForm(UseCase\Customer\AddOptions\Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $baggage = $command->baggage;
            return $this->redirectToRoute('confirmation', [
                'ticket' => $ticket,
                'option' => $command->options,
                'baggage' => $baggage->getName()
            ]);
        }

        return $this->render('customer/addOptions.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Confirmation of the purchase route
     * 
     * @param int $ticket
     * @param int $option
     * @param string $baggage
     * @param TicketRepository $ticketRepository
     * @param OptionRepository $optionRepository
     * @return Response
     */
    #[Route('/confirmation/{ticket}/{option}/{baggage}', name: 'confirmation')]
    public function confirmation(
        int $ticket,
        int $option,
        string $baggage,
        TicketRepository $ticketRepository,
        OptionRepository $optionRepository
    ): Response
    {
         $ticketInfo = $ticketRepository->find($ticket);
        
        $optionInfo = ($option !== 0) ? $optionRepository->find($option) : ['name' => 'No options', 'price' => 0, 'id' => 0];
        $user = $this->getUser();
        $userName = ($user->getName() === null) ? 'Your name' : $user->getName();


        return $this->render('customer/confirmation.html.twig', [
            'ticket' => $ticketInfo,
            'option' => $optionInfo,
            'baggage' => $baggage,
            'userName' => $userName,
            'email' => $user->getEmail()
        ]);
    }

    /**
     * Completion of the purchase route
     * 
     * @param int $ticket
     * @param int $option
     * @param string $baggage
     * @param TicketRepository $ticketRepository
     * @param OptionRepository $optionRepository
     * @param EntityManagerInterface $entityManager
     * @param MailTicketSender $mailTicketSender
     * @param Request $request
     * @return Response
     */
    #[Route('/completion/{ticket}/{option}/{baggage}', name: 'completition')]
    public function completionOfOrder(
        int $ticket,
        int $option,
        string $baggage,
        TicketRepository $ticketRepository,
        OptionRepository $optionRepository,
        EntityManagerInterface $entityManager,
        MailTicketSender $mailTicketSender,
        Request $request
    ): Response
    {
        $user = $this->getUser();
        $ticket = $ticketRepository->find($ticket);
        $option = $optionRepository->find($option);
        $baggageObj = new Baggage($baggage);
        $soldTicket = new SoldTicket($ticket->getTicketNumber(), $ticket->getFlight(), $user, $ticket->getSeatTypeObj(), $ticket->getSeatNumber(), $baggageObj);
        $soldTicket->addOption($option);
        $entityManager->persist($soldTicket);
        $entityManager->flush();
        $mailTicketSender->send($request->get('email'), (string)$ticket->getTicketNumber());
        $ticketRepository->remove($ticket, true);
        return $this->render('customer/completion.html.twig', [
            'soldTicket' => $soldTicket,
            'user' => $user
        ]);
    }
}
