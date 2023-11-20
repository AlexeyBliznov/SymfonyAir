<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Service\AdminMailSender;
use App\UseCase\AdminRegistration\Command;
use App\UseCase\AdminRegistration\Form;
use App\UseCase\AdminRegistration\Handler;
use App\UseCase\ConfirmEmail\Command as ConfirmCommand;
use App\UseCase\ConfirmEmail\Handler as ConfirmHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminRegistrationController
 * @extends AbstractController
 */
class AdminRegistrationController extends AbstractController
{
    /**
     * Admin register route
     *
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param Handler $handler
     * @return Response
     */
    #[Route('/admin/register', name: 'admin_register')]
    public function register(Request $request, EntityManagerInterface $entityManager, Handler $handler): Response
    {
        $command = new Command();
        $form = $this->createForm(Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $handler->handle($command);
            return $this->redirectToRoute('admin');
        }

        return $this->render('admin/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * Admin confirm registration route
     *
     * @param string $token
     * @param string $email
     * @param ConfirmHandler $handler
     * @param AdminMailSender $mailSender
     * @return Response
     */
    #[Route('/admin/register/{token}/{email}', name: 'admin.register.confirm', methods: ['GET'])]
    public function confirm(string $token, string $email, ConfirmHandler $handler, AdminMailSender $mailSender): Response
    {
        $command = new ConfirmCommand($token);

        try {
            $handler->handle($command);
            $mailSender->sendToEmployee($email);
            $this->addFlash('success', 'Email is successfully confirmed');
        } catch (\DomainException $exception) {
            $this->addFlash('error', $exception->getMessage());
            $this->redirectToRoute('admin');
        }

        return $this->redirectToRoute('admin_login');
    }
}
