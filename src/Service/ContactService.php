<?php

// src/Service/ContactService.php



namespace App\Service;



use Symfony\Component\Mailer\MailerInterface;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;

use Symfony\Component\Mailer\Exception\TransportExceptionInterface;



class ContactService

{

    private MailerInterface $mailer;



    public function __construct(MailerInterface $mailer)

    {

        $this->mailer = $mailer;

    }



    public function sendContactEmail(array $contactData): void

    {

        $email = (new TemplatedEmail())

            ->from($contactData['email'])

            ->to('poirierjulien30@gmail.com')

            ->subject('Message depuis le formulaire de contact - La Petite Capitale')

            ->htmlTemplate('emails/contact_email.html.twig')

            ->context([

                'name' => $contactData['name'],

                'senderEmail' => $contactData['email'],

                'phone' => $contactData['phone'],

                'subject' => $contactData['subject'],

                'messageText' => $contactData['message'],

            ]);



        try {

            $this->mailer->send($email);

        } catch (TransportExceptionInterface $e) {

            // Gérer l'erreur d'envoi de l'e-mail ici

            throw new \Exception('Une erreur s\'est produite lors de l\'envoi de l\'e-mail :' . $e->getMessage());

        }

    }

}
