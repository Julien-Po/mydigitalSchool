<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Recipes;
use App\Repository\IngredientsRepository;
use App\Repository\RecipesRepository;
use App\Repository\GenreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Form\RecipesType;
use App\Repository\CalendarRepository;

class RecipesController extends AbstractController
{
    #[Route('/newrecipes', name: 'app_recipes', methods: ['GET', 'POST'])]
    public function createRecipes(Request $request, EntityManagerInterface $entityManager, IngredientsRepository $ingredientsRepository, GenreRepository $genreRepository, CalendarRepository $calendarRepository): Response
    {
        $recipes = new Recipes();
    
        if ($request->isMethod('POST')) {
            $ingredientIds = $request->request->all('ingredients');
            $calendarId = $request->request->get('calendar_id');
            $user = $this->getUser();
    
            if (!empty($ingredientIds) && $calendarId && $user) {
                $selectedIngredients = $ingredientsRepository->findBy(['id' => $ingredientIds]);
                foreach ($selectedIngredients as $ingredient) {
                    $recipes->addIngredient($ingredient);
                }
    
                $calendar = $calendarRepository->find($calendarId);
    
                if ($calendar && $calendar->getUser() === $user) {
                    $recipes
                        ->setDessert($request->request->get('dessert') !== null)
                        ->setPlate($request->request->get('plate') !== null)
                        ->setStarter($request->request->get('starter') !== null)
                        ->setUser($user)
                        ->setPaid(false)
                        ->setCreatedAt(new \DateTimeImmutable('now'))
                        ->setCalendar($calendar)
                        ->setReservationAt($calendar->getDate());
    
                    $entityManager->persist($recipes);
                    $entityManager->flush();
    
                    $this->addFlash('success', 'Recette créée avec succès!');
                    return $this->redirectToRoute('app_calendar_new', ['recipe_id' => $recipes->getId()]);
                } else {
                    $this->addFlash('error', 'Calendrier invalide ou ne vous appartient pas.');
                }
            } else {
                $this->addFlash('error', 'Aucun ingrédient sélectionné ou calendrier invalide.');
            }
    
            return $this->redirectToRoute('app_calendar_new');
        }
    
        $ingredients = $ingredientsRepository->findAll();
        $genres = $genreRepository->findAll();
        $calendars = $calendarRepository->findBy(['user' => $this->getUser()]);
    
        return $this->render('recipes/new.html.twig', [
            'genres' => $genres,
            'ingredients' => $ingredients,
            'calendars' => $calendars,
        ]);
    }
    
    
    

    #[Route('/recipes/update/{id}', name : 'edit_recipes')]
    public function editIngredient(RecipesRepository $repository, int $id, Request $request, EntityManagerInterface $manager) : Response
    {
        $recipes = $repository->findOneBy(["id" => $id]);
        
        // if($this->getUser() != $recipes->getUser){
        //     return $this->redirectToRoute('app_home');
        // }

        $form = $this->createForm(RecipesType::class, $recipes);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
           $recipes = $form->getData();

           $manager->persist($recipes);
           $manager->flush();

           return $this->redirectToRoute('view_recipes');
        }

      
        $form = $this->createForm(RecipesType::class, $recipes);
        return $this->render('recipes/edit.html.twig',[
            'form' => $form->createView()
        ]);
    }

    #[Route('/recipes/byUser', name:'show_recipes_by_user', methods:['GET'])]
    public function showRecipesByUser(RecipesRepository $recipesRepository)
    {

        $recipesByUser = $recipesRepository->findBy(['User' => $this->getUser()], ['reservationAt' => 'DESC']);
        
        return $this->render('recipes/showByUser.html.twig', [
            'recipes' => $recipesByUser
        ]);
    }

    #[Route('/recipes/delete/{id}', name : 'delete_recipes', methods: ['GET'])]
    public function delete(EntityManagerInterface $manager, Recipes $recipes) : Response
    {
        if($recipes->ispaid() == false){

            $manager->remove($recipes);
            $manager->flush();
    
                $this->addFlash(
                    'success',
                    'Votre ingredient a été supprimé avec succès !'
                );
    
        }
        //faire un message pour dire d'appeler le restaurant pour annuler parceque l'acompte a deja ete payé
        return $this->redirectToRoute('view_recipes'); 
    }
}
