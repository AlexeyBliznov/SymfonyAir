<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime;
use Twig\Environment;

class MailTicketSender extends AbstractController
{
    private MailerInterface $mailer;
    private Environment $twig;

    public function __construct(MailerInterface $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function send(string $email, array $ticketNumbers): void
    {
        $massege = (new Mime\Email())
            ->to($email)
            ->subject('Your ticket')
            ->text("It's your tickets")
            ->html($this->twig->render('mail/ticket.html.twig', [
                'ticketNumbers' => $ticketNumbers
            ]));

        $this->mailer->send($massege);
    }
}