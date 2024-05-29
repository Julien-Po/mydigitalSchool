<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/security', name: 'app_security', methods: ['GET', 'POST'])]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        
        return $this->render('security/login.html.twig', [
            'last_username' => $authenticatiionUtils->getLastUsername(),        
            'error' => $authenticatiionUtils->getLastAuthenticationError()
        ]);
    }

#[Route('/deconnexion', name:"security.logout")]    
    public function logout()
    {
        //Nothing to do here
    }
}
