<?php

namespace App\Controller;

use App\Entity\Calendar;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AjaxController extends AbstractController
{
    #[Route('/ajax/{id}', name: 'app_ajax', methods:['PUT'])]
    public function majEvents(?Calendar $calendar, Request $request, EntityManagerInterface $em): Response
    {
        $data = json_decode($request->getContent());

        // Debugging
        if (!$data) {
            return new Response('JSON invalide', 400);
        }

        if(
            isset($data->title) && !empty($data->title) &&
            isset($data->start) && !empty($data->start) &&
            isset($data->description) && !empty($data->description) 
        ){
            $code = 200;

            if(!$calendar){
                return new Response('Événement non trouvé', 404);
            }

            // Debugging
            try {
                $calendar->setTitle($data->title);
                $calendar->setStart(new DateTime($data->start));
                $calendar->setDescription($data->description);

                $em->persist($calendar);
                $em->flush();

                return new Response('Ok', $code);
            } catch (\Exception $e) {
                return new Response('Erreur lors de la mise à jour : ' . $e->getMessage(), 400);
            }
        } else {
            return new Response('Données incomplètes', 400);
        }
    }
}

