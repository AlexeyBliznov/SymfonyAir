<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Repository\AdminRepository;
use App\Repository\AirplaneRepository;
use App\Repository\FlightRepository;
use App\Repository\OptionRepository;
use App\UseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SupervisorController
 * @extends AbstractController
 */
class SupervisorController extends AbstractController
{
    /**
     * Add employee route
     *
     * @param Request $request
     * @param UseCase\Supervisor\AddEmployee\Handler $handler
     * @return Response
     */
    #[Route('/admin/supervisor/add_employee', name: 'add_employee')]
    public function addGateManager(Request $request, UseCase\Supervisor\AddEmployee\Handler $handler): Response
    {
        $command = new UseCase\Supervisor\AddEmployee\Command();
        $form = $this->createForm(UseCase\Supervisor\AddEmployee\Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $handler->handle($command);

            return $this->redirectToRoute('list_of_employees');
        }

        return $this->render('admin/supervisor/addEmployee.html.twig', [
            'addForm' => $form->createView()
        ]);
    }

    /**
     * List of employees route
     *
     * @param AdminRepository $adminRepository
     * @return Response
     */
    #[Route('/admin/supervisor/employees', name: 'list_of_employees')]
    public function listOfEmployees(AdminRepository $adminRepository): Response
    {
        $admins = $adminRepository->findAll();

        return $this->render('admin/supervisor/listOfEmployees.html.twig', [
            'admins' => $admins
        ]);
    }

    /**
     * Remove employee route
     *
     * @param int $id
     * @param UseCase\Supervisor\RemoveEmployee\Handler $handler
     * @return Response
     */
    #[Route('/admin/supervisor/employees/remove/{id}', name: 'remove_employee')]
    public function removeCheckInManagerManager(int $id, UseCase\Supervisor\RemoveEmployee\Handler $handler): Response
    {
        $handler->handle($id);

        return $this->redirectToRoute('list_of_employees');
    }

    /**
     * Add flight route
     *
     * @param Request $request
     * @param AirplaneRepository $airplaneRepository
     * @param UseCase\Supervisor\AddFlight\Handler $handler
     * @return Response
     */
    #[Route('/admin/supervisor/add_flight', name: 'add_flight')]
    public function addFlight(Request $request, AirplaneRepository $airplaneRepository, UseCase\Supervisor\AddFlight\Handler $handler): Response
    {
        $command = new UseCase\Supervisor\AddFlight\Command();
        $form = $this->createForm(UseCase\Supervisor\AddFlight\Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $handler->handle($command);

            return $this->redirectToRoute('list_of_flights');
        }

        return $this->render('admin/supervisor/addflight.html.twig', [
            'addForm' => $form->createView(),
        ]);
    }

    /**
     * List of flights route
     *
     * @param FlightRepository $flightRepository
     * @return Response
     */
    #[Route('/admin/supervisor/flights', name: 'list_of_flights')]
    public function listOfFlights(FlightRepository $flightRepository): Response
    {
        $flights = $flightRepository->findAll();

        return $this->render('admin/supervisor/listOfFlights.html.twig', [
            'flights' => $flights
        ]);
    }

    /**
     * Remove flight route
     *
     * @param int $id
     * @param UseCase\Supervisor\RemoveFlight\Handler $handler
     * @return Response
     */
    #[Route('/admin/supervisor/flights/remove/{id}', name: 'remove_flight')]
    public function removeFlight(int $id, UseCase\Supervisor\RemoveFlight\Handler $handler): Response
    {
        $handler->handle($id);

        return $this->redirectToRoute('list_of_flights');
    }

    /**
     * Edit flight route
     *
     * @param int $id
     * @param UseCase\Supervisor\EditFlight\Handler $handler
     * @param Request $request
     * @param FlightRepository $flightRepository
     * @return Response
     */
    #[Route('/admin/supervisor/flights/edit/{id}', name: 'edit_flight')]
    public function editFlight(int $id, UseCase\Supervisor\EditFlight\Handler $handler, Request $request, FlightRepository $flightRepository): Response
    {
        $flight = $flightRepository->find($id);

        $command = new UseCase\Supervisor\EditFlight\Command();
        $form = $this->createForm(UseCase\Supervisor\EditFlight\Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $handler->handle($id, $command);

            return $this->redirectToRoute('edit_flight', ['id' => $id]);
        }

        return $this->render('admin/supervisor/editFlight.html.twig', [
            'editForm' => $form->createView()
        ]);
    }

    /**
     * Add airplane route
     *
     * @param Request $request
     * @param UseCase\Supervisor\AddAirplane\Handler $handler
     * @return Response
     */
    #[Route('/admin/supervisor/add_airplane', name: 'add_airplane')]
    public function addAirplane(Request $request, UseCase\Supervisor\AddAirplane\Handler $handler): Response
    {
        $command = new UseCase\Supervisor\AddAirplane\Command();
        $form = $this->createForm(UseCase\Supervisor\AddAirplane\Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $handler->handle($command);

            return $this->redirectToRoute('list_of_airplanes');
        }

        return $this->render('admin/supervisor/addAirplane.html.twig', [
            'addForm' => $form->createView(),
        ]);
    }

    /**
     * List of airplanes route
     *
     * @param AirplaneRepository $airplaneRepository
     * @return Response
     */
    #[Route('/admin/supervisor/airplanes', name: 'list_of_airplanes')]
    public function listOfAirplanes(AirplaneRepository $airplaneRepository): Response
    {
        $airplanes = $airplaneRepository->findAll();

        return $this->render('admin/supervisor/listOfAirplanes.html.twig', [
            'airplanes' => $airplanes,
        ]);
    }

    /**
     * Remove airplane route
     *
     * @param int $id
     * @param UseCase\Supervisor\RemoveAirplane\Handler $handler
     * @return Response
     */
    #[Route('/admin/supervisor/airplanes/remove/{id}', name: 'remove_airplane')]
    public function removeAirplane(int $id, UseCase\Supervisor\RemoveAirplane\Handler $handler): Response
    {
        $handler->handle($id);

        return $this->redirectToRoute('list_of_airplanes');
    }

    /**
     * Create option
     *
     * @param Request $request
     * @param UseCase\Supervisor\CreateOption\Handler $handler
     * @return Response
     */
    #[Route('/admin/supervisor/createOption', name: 'create_option')]
    public function createOption(Request $request, UseCase\Supervisor\CreateOption\Handler $handler): Response
    {
        $command = new UseCase\Supervisor\CreateOption\Command();
        $form = $this->createForm(UseCase\Supervisor\CreateOption\Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $handler->handle($command);

            return $this->redirectToRoute('list_of_options');
        }

        return $this->render('admin/supervisor/createOption.html.twig', [
            'createForm' => $form->createView()
        ]);
    }

    /**
     * List of options route
     *
     * @param OptionRepository $repository
     * @return Response
     */
    #[Route('/admin/supervisor/options', name: 'list_of_options')]
    public function listOfOptions(OptionRepository $repository): Response
    {
        $options = $repository->findAll();

        return $this->render('admin/supervisor/listOfOptions.html.twig', [
            'options' => $options
        ]);
    }

    /**
     * Remove option route
     *
     * @param int $id
     * @param OptionRepository $repository
     * @return Response
     */
    #[Route('/admin/supervisor/options/remove/{id}', name: 'remove_option')]
    public function removeOption(int $id, OptionRepository $repository): Response
    {
        $option = $repository->find($id);
        $repository->remove($option);

        return $this->redirectToRoute('list_of_options');
    }
}
