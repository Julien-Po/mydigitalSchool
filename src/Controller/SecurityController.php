<?php

namespace App\Controller;

use App\Form\RegistrationType;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class SecurityController extends AbstractController
{
    #[Route('/connexion', name: 'app_security', methods: ['GET', 'POST'])]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {   
        return $this->render('security/login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),        
            'error' => $authenticationUtils->getLastAuthenticationError()
        ]);
    }

#[Route('/deconnexion', name:"security.logout")]    
    public function logout()
    {
        //Nothing to do here
    }

#[Route('/inscription', name: 'security.registration', methods:['GET','POST'])]
    public function registration(Request $request, EntityManagerInterface $manager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('security/new.html.twig', [
            'form' => $form->createView()]);
       
    }
}
