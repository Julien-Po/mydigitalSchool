<?php

namespace App\Controller;

use Symfony\Contracts\Translation\TranslatorInterface;
use App\Form\RegistrationType;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class SecurityController extends AbstractController
{
    #[Route('/connexion', name: 'app_connexion', methods:['GET', 'POST'])]
    public function index(AuthenticationUtils $authenticationUtils, TranslatorInterface $translator): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        if ($error) {
            $customError = $translator->trans('Identifiants incorrects, veuillez vérifier votre adresse email et mot de passe.', [], 'messages');
        } else {
            $customError = null;
        }

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $customError,        ]);
    }

#[Route('/deconnexion', name:"app_deconnexion")]    
    public function logout()
    {
        //Nothing to do here
    }

#[Route('/inscription', name: 'security.registration', methods:['GET','POST'])]
    public function registration(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $user->setRoles(['ROLE_USER']);

            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                ));

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('security/new.html.twig', [
            'form' => $form->createView()]);
       
    }
}
