<?php

namespace App\Controller;

use App\Form\DateFormType;
use App\Repository\CalendarRepository;
use App\Repository\RecipesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            
        ]);
    }

    #[Route('/recipesByDay', name: 'app_recipes_day')]
    public function recipesByDay(CalendarRepository $calendarRepository, Request $request): Response
    {
        $form = $this->createForm(DateFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $data = $form->getData();
            $date = $data['date'];
        }else{
            $date = new \DateTimeImmutable('now');
        }
                    //$recipesOfTheDay = $calendarRepository->findCalendarsByDate($date);
        $recipesOfTheDay = $calendarRepository->findCalendarsByStartDate($date);

        return $this->render('admin/recipesByDate.html.twig', [
            'form' => $form->createView(),
            'recipesOfTheDay' => $recipesOfTheDay
        ]);;
    }
}
