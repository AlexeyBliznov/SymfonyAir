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
        // switch(true) {
        //     case $request->get('from')&$request->get('to')&$request->get('date'):
        //         $flights = $flightRepository->searchFlight($request->get('from'), $request->get('to'), $request->get('date'));
        //         return $this->render('customer/searchTickets.html.twig', [
        //             'flights' => $flights
        //         ]);
        //         break;
        //     case $request->get('from')&$request->get('to'):
        //         $flights = $flightRepository->searchFlightByFromAndTo($request->get('from'), $request->get('to'));
        //         return $this->render('customer/searchTickets.html.twig', [
        //             'flights' => $flights
        //         ]);
        //         break;
        //     case $request->get('from')&$request->get('date'):
        //         $flights = $flightRepository->searchFlightByFromAndDate($request->get('from'), $request->get('date'));
        //         return $this->render('customer/searchTickets.html.twig', [
        //             'flights' => $flights
        //         ]);
        //         break;
        //     case $request->get('from'):
        //         $flights = $flightRepository->searchFlightByFrom($request->get('from'));
        //         return $this->render('customer/searchTickets.html.twig', [
        //             'flights' => $flights
        //         ]);
        //         break;
        //     case $request->get('to')&$request->get('date'):
        //         $flights = $flightRepository->searchFlightByToDate($request->get('to'), $request->get('date'));
        //         return $this->render('customer/searchTickets.html.twig', [
        //             'flights' => $flights
        //         ]);
        //         break;
        // }
        if ($request->get('from') & $request->get('to') & $request->get('date')) {
            $flights = $flightRepository->searchFlight($request->get('from'), $request->get('to'), $request->get('date'));
            return $this->render('customer/searchTickets.html.twig', [
                'flights' => $flights,
                'passengers' => $request->get('passengers')
            ]);
            // return $this->render('test.html.twig', [
            //          'result' => var_dump($request->get('date'))
            // ]);
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
    #[Route('/chooseTicket/{flightId}', name: 'choose_ticket')]
    public function buy(
        int $flightId,
        FlightRepository $flightRepository,
        TicketRepository $ticketRepository
    ): Response
    {
        $flight = $flightRepository->findById($flightId);
        $tickets = $ticketRepository->findByFlight($flight);

        return $this->render('customer/informationAboutFlight.html.twig', [
            'flight' => $flight,
            'tickets' => $tickets
        ]);
    }

    /**
     * Add options route
     * 
     * @param Request $request
     * @param OptionRepository $optionRepository
     * @param TicketRepository $ticketRepository
     * @return Response
     */
    #[Route('/addOptions', name: 'add_options')]
    public function choose(Request $request, OptionRepository $optionRepository, TicketRepository $ticketRepository): Response
    {
        $options = $optionRepository->findAll();
        $ticketsId = $request->get('seat');
        $tickets = [];
        foreach ($ticketsId as $ticketId) {
            $tickets[] = $ticketRepository->find($ticketId);
        }
        // return $this->render('test.html.twig', [
        //     'result' => var_dump($tickets)
        // ]);
        // $command = new UseCase\Customer\AddOptions\Command();
        // $form = $this->createForm(UseCase\Customer\AddOptions\Form::class, $command);
        // $form->handleRequest($request);

        // if ($form->isSubmitted() && $form->isValid()) {
        //     $baggage = $command->baggage;
        //     return $this->redirectToRoute('confirmation', [
        //         'ticket' => $ticket,
        //         'option' => $command->options,
        //         'baggage' => $baggage->getName()
        //     ]);
        // }
        // // return $this->render('test.html.twig', [
        // //     'result' => var_dump($request->get('seat'))
        // // ]);

        return $this->render('customer/addOptions.html.twig', [
            'tickets' => $tickets,
            'options' => $options
        ]);
    }

    /**
     * Confirmation of the purchase route
     * 
     * @param TicketRepository $ticketRepository
     * @param OptionRepository $optionRepository
     * @return Response
     */
    #[Route('/confirmation', name: 'confirmation')]
    public function confirmation(
        Request $request,
        TicketRepository $ticketRepository,
        OptionRepository $optionRepository,
        EntityManagerInterface $entityManager,
        MailTicketSender $mailTicketSender
    ): Response
    {
        if($request->get('names') and $request->get('email')) {
            $passengersNames = $request->get('names');
            $ticketsNumbers = [];
            $user = $this->getUser();
            foreach ($passengersNames as $ticketId => $passengerName) {
                $ticket = $ticketRepository->find($ticketId);
                    $ticket->setPassengerName($passengerName);
                    $ticketsNumbers[] = $ticket->getTicketNumber();
                    $entityManager->flush();
            }
            $mailTicketSender->send($request->get('email'), $ticketsNumbers);
            return $this->render('customer/completion.html.twig', [
                'user' => $user
            ]);
        } else {
            $user = $this->getUser();
            $baggage = $request->get('baggage');
            $options = $request->get('options');
            $ticketsNumbers = [];
            $bookTickets = [];
            foreach ($baggage as $ticketId => $baggageName) {
                $ticket = $ticketRepository->find($ticketId);
                $ticket->bookTicket($user, new Baggage($baggageName));
                if ($options) {
                    if (array_key_exists($ticketId, $options)) {
                        foreach ($options[$ticketId] as $optionId) {
                            $ticket->addOption($optionRepository->find($optionId));
                        }
                    }
                }
                $bookTickets[] = $ticket;
                $entityManager->flush();
            }

            return $this->render('customer/confirmation.html.twig', [
                'tickets' => $bookTickets
            ]);
        }
    }

    // /**
    //  * Completion of the purchase route
    //  * 
    //  * @param int $ticket
    //  * @param int $option
    //  * @param string $baggage
    //  * @param TicketRepository $ticketRepository
    //  * @param OptionRepository $optionRepository
    //  * @param EntityManagerInterface $entityManager
    //  * @param MailTicketSender $mailTicketSender
    //  * @param Request $request
    //  * @return Response
    //  */
    // #[Route('/confirmation/completion', name: 'completition')]
    // public function completionOfOrder(
    //     int $ticket,
    //     int $option,
    //     string $baggage,
    //     TicketRepository $ticketRepository,
    //     OptionRepository $optionRepository,
    //     EntityManagerInterface $entityManager,
    //     MailTicketSender $mailTicketSender,
    //     Request $request
    // ): Response
    // {
    //     $user = $this->getUser();
    //     $ticket = $ticketRepository->find($ticket);
    //     $option = $optionRepository->find($option);
    //     $baggageObj = new Baggage($baggage);
    //     $ticket->sellTicket($user, $baggageObj);
    //     if($option) {
    //         $ticket->addOption($option);
    //     };
    //     $entityManager->flush();
    //     $mailTicketSender->send($request->get('email'), (string)$ticket->getTicketNumber());
    //     return $this->render('customer/completion.html.twig', [
    //         'soldTicket' => $ticket,
    //         'user' => $user
    //     ]);
    // }

    #[Route('/test', name: 'test')]
    public function test(Request $request): Response
    {
        return $this->render('test.html.twig', [
            'result' => var_dump($request->get('options'))
        ]);
    }
}
