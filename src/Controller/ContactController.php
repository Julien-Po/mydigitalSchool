<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, EntityManagerInterface $manager, MailerInterface $mailer): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid() )
        {
            $contact = $form->getData();

            $manager->persist($contact);
            $manager->flush();

            $email = (new TemplatedEmail())
            ->from($contact->getEmail())
            ->to('poirierjulien30@gmail.com')
            ->subject($contact->getSubject())
            ->htmlTemplate('email/email.html.twig')

            // change locale used in the template, e.g. to match user's locale
            ->locale('de')
        
            // pass variables (name => value) to the template
            ->context([
                'contact' => $contact
            ])
        ;

        $mailer->send($email);

        // ...
    
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // public function findUserByEmailOrUsername(string $usernameOrEmail): ?User 
    // {
    //     return $this->createQueryBuilder('u')
    //             ->where('u.email = :identifier')
    //             ->orWhere('u.username = :identifier')
    //             ->setParameter('identifier' , $usernameOrEmail)
    //             ->setMaxResults(1)
    //             ->getQuery()
    //             ->getSingleResult();
    // }
}
