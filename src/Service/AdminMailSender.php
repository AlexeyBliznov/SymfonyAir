<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class AdminMailSender extends AbstractController
{
    private MailerInterface $mailer;
    private Environment $twig;

    public function __construct(MailerInterface $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    /**
     * @throws SyntaxError
     * @throws TransportExceptionInterface
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function send(string $token, string $email, string $name, string $role): void
    {
        $massege = (new Mime\Email())
            ->to('admin@email.com')
            ->subject('Sign up confirmation')
            ->text('Please, confirm this role. ' . $name . ' - ' . $role)
            ->html($this->twig->render('admin/mail_signup.html.twig', [
                'token' => $token,
                'email' => $email,
            ]));

        $this->mailer->send($massege);
    }

    public function sendToEmployee(string $email): void
    {
        $massege = (new Mime\Email())
            ->to($email)
            ->subject('Approve')
            ->text('You can use your account')
            ->html($this->twig->render('admin/mail_approve.html.twig'));

        $this->mailer->send($massege);
    }
}
