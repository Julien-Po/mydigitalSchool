<?php

namespace App\Controller;

use App\Entity\Calendar;
use App\Form\CalendarType;
use App\Repository\CalendarRepository;
use App\Repository\RecipesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CalendarController extends AbstractController
{

    #[Route('admin/calendar/indexCalendar', name: 'app_calendar_display', methods: ['GET'])]
    public function displayCalendar(CalendarRepository $calendarRepository): Response
    {
        return $this->render('calendar/main.html.twig', [
            'plannings' => $calendarRepository->findCalendar(),
        ]);
    }

    #[Route('/calendar', name: 'app_calendar_index', methods: ['GET'])]
    public function index(CalendarRepository $calendarRepository): Response
    {
        $events = $calendarRepository->findCalendar();

        $rdv = [];
        foreach($events as $event){
            $rdv[] = [
                'id' => $event->getId(),
                'title' => $event->getTitle(),
                'start' => $event->getStart()->format('Y-m-d H:i:s'),
                'description' => $event->getDescription(),
                'clientID' => $event->getUser()->getId()          
            ];
        }
    
        // Move json_encode outside the foreach loop
        $data = json_encode($rdv);
    
        return $this->render('calendar/index.html.twig', compact('data'));
    }
    

    #[Route('/calendar/new', name: 'app_calendar_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, RecipesRepository $recipesRepository): Response
    {
        $calendar = new Calendar();
        $user = $this->getUser();   
        
        // $recipeId = $request->query->get('recipe_id'); // Utilisez 'recipe_id'

        $form = $this->createForm(CalendarType::class, $calendar);
        $form->handleRequest($request);
    
       if ($form->isSubmitted()) {
    if ($form->isValid()) {
        $calendar->setUser($user);
        $entityManager->persist($calendar);
        $entityManager->flush();
        return $this->redirectToRoute('app_pay', [], Response::HTTP_FOUND);
    } 
}
    
        return $this->render('calendar/new.html.twig', [
            'calendar' => $calendar,
            'form' => $form,
        ]);
    }
    
    
    



    #[Route('/calendar/{id}', name: 'app_calendar_show', methods: ['GET'])]
    public function show(Calendar $calendar): Response
    {
        return $this->render('calendar/show.html.twig', [
            'calendar' => $calendar,
        ]);
    }

    #[Route('/calendar/{id}/edit', name: 'app_calendar_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Calendar $calendar, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CalendarType::class, $calendar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_calendar_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('calendar/edit.html.twig', [
            'calendar' => $calendar,
            'form' => $form,
        ]);
    }

    #[Route('/calendar/{id}', name: 'app_calendar_delete', methods: ['POST'])]
    public function delete(Request $request, Calendar $calendar, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$calendar->getId(), $request->request->get('_token'))) {
            $entityManager->remove($calendar);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_calendar_index', [], Response::HTTP_SEE_OTHER);
    }


}

