<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class AdminLoginController
 * @extends AbstractController
 */
class AdminLoginController extends AbstractController
{
    /**
     * Login admin route
     *
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    #[Route('/admin/login', name: 'admin_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('admin/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    /**
     * Logout admin route
     *
     * @return RedirectResponse
     */
    #[Route('/admin/logout', name: 'admin_logout', methods: ['GET', 'POST'])]
    public function logout(): RedirectResponse
    {
        return $this->redirectToRoute('admin');
    }
}
