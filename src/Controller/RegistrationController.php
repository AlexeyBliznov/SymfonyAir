<?php

declare(strict_types=1);

namespace App\Controller;

use App\Security\EmailVerifier;
use App\UseCase\Registration\Command;
use App\UseCase\ConfirmEmail\Command as ConfirmCommand;
use App\UseCase\Registration\Form;
use App\UseCase\Registration\Handler;
use App\UseCase\ConfirmEmail\Handler as ConfirmHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class RegistrationController
 * @extends AbstractController
 */
class RegistrationController extends AbstractController
{
    public function __construct(private EmailVerifier $emailVerifier) {}

    /**
     * User register route
     *
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param Handler $handler
     * @return Response
     */
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, EntityManagerInterface $entityManager, Handler $handler): Response
    {
        $command = new Command();
        $form = $this->createForm(Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $handler->handle($command);

            return $this->redirectToRoute('welcome');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * Confirm user registration route
     *
     * @param string $token
     * @param ConfirmHandler $handler
     * @return Response
     */
    #[Route('/register/{token}', name: 'auth.register.confirm', methods: ['GET'])]
    public function confirm(string $token, ConfirmHandler $handler): Response
    {
        $command = new ConfirmCommand($token);

        try {
            $handler->handle($command);
            $this->addFlash('success', 'Email is successfully confirmed');
        } catch (\DomainException $exception) {
            $this->addFlash('error', $exception->getMessage());
            $this->redirectToRoute('home');
        }

        return $this->redirectToRoute('app_login');
    }
}
