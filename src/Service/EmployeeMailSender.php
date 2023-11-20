<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Symfony\Component\Mime;

class EmployeeMailSender extends AbstractController
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
    public function send(string $email, string $name, string $role, string $password): void
    {
        $massege = (new Mime\Email())
            ->to($email)
            ->subject('Approve')
            ->text('You can use your account')
            ->html($this->twig->render('admin/mail_addEmployee.html.twig', [
                'name' => $name,
                'email' => $email,
                'password' => $password
            ]));

        $this->mailer->send($massege);
    }
}
