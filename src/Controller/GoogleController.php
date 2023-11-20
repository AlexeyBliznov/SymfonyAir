<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class GoogleController extends AbstractController
{
    /**
     * Google oauth
     * 
     * @param ClientRegistry 
     */
    #[Route("/connect/google", name: "connect_google_start")]
    public function connectAction(ClientRegistry $clientRegistry)
    {
        return $clientRegistry
            ->getClient('google_main')
            ->redirect();
    }


    /**
     * Redirect route google oauth
     * 
     * @param ClientRegistry
     * @param UserRepository
     * @param EntityManagerInterface
     * @param Security
     */
    #[Route("/connect/google/check", name: "connect_google_check")]
    public function connectCheckAction(
        ClientRegistry $clientRegistry,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        Security $security
    )
    {
        $client = $clientRegistry->getClient('google_main');
        $result = $client->fetchUser();
        if($userRepository->hasByEmail($result->getEmail())) {
            $user = $userRepository->findByEmail($result->getEmail());
            
            return $security->login($user);
        } else {
            $user = new User();
            $user->create($result->getEmail(), $result->getId());
            if($result->getName()) {
                $user->setName($result->getName());
            }
            $entityManager->persist($user);
            $entityManager->flush();
            
            return $security->login($user);
        }
    }
}
