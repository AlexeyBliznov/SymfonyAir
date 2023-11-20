<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Role;
use App\Repository\FlightRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
        return $this->render('welcome.html.twig', [
            'result' => phpinfo()
        ]);
    }

    /**
     * Search tickets route
     *
     * @param Request $request
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
}
