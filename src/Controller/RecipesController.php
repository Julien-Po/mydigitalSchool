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
    #[Route('/newrecipes/{type}', name: 'app_recipes', methods: ['GET', 'POST'])]
    public function createRecipes($type ,Request $request, EntityManagerInterface $entityManager, IngredientsRepository $ingredientsRepository, GenreRepository $genreRepository, CalendarRepository $calendarRepository): Response
    {
        $recipes = new Recipes();
    
        if ($request->isMethod('POST')) {
            $ingredientIds = $request->request->all('ingredients');
            $user = $this->getUser();
    
            if (!empty($ingredientIds) &&  $user) {
                $selectedIngredients = $ingredientsRepository->findBy(['id' => $ingredientIds]);
                foreach ($selectedIngredients as $ingredient) {
                    $recipes->addIngredient($ingredient);
                }
                // dd($type);
                $recipes
                    ->setDessert($request->request->get('dessert') !== null)
                    ->setPlate($request->request->get('plate') !== null)
                    ->setStarter($request->request->get('starter') !== null)
                    ->setUser($user)
                    ->setPaid(false)
                    ->setCreatedAt(new \DateTimeImmutable('now'))
                    ->setServed(false);
                    switch ($type) {
                        case 'starter':
                            $recipes->setStarter(true);
                            break;
                        case 'plate':
                            $recipes->setPlate(true);
                            break;
                        case 'dessert':
                            $recipes->setDessert(true);
                            break;
                        default:
                            throw $this->createNotFoundException('Type de recette non trouvé');
                    };         
    
                $entityManager->persist($recipes);
                $entityManager->flush();

                $this->addFlash('success', 'Recette créée avec succès!');
                return $this->redirectToRoute('recipes_type');
               
            } else {
                $this->addFlash('error', 'Aucun ingrédient sélectionné');
            }
        }
    
        $ingredients = $ingredientsRepository->findIngredientswGenre();
        $genres = $genreRepository->findGenre();

        return $this->render('recipes/new.html.twig', [
            'type' => $type,
            'genres' => $genres,
            'ingredients' => $ingredients,
            
        ]);
    }
    
    #[Route('/admin/recipes', name :'view_recipes')]
    public function displayRecipes(RecipesRepository $repository) : Response

    {
        $recipes = $repository->findRecipes();

        return $this->render('recipes/displayrecipes.html.twig', [
            'recipes'=>$recipes
        ]);
    }

    #[Route('/recipes/delete/{id}', name : 'delete_recipes', methods: ['GET'])]
    public function delete(EntityManagerInterface $manager, Recipes $recipes) : Response
    {
        if($this->isGranted('ROLE_ADMIN') || $this->getUser() == $recipes->getUser()){
            if($recipes->ispaid() == false){

                $manager->remove($recipes);
                $manager->flush();
        
                    $this->addFlash(
                        'success',
                        'Votre recette a été supprimé avec succès !'
                    );
        
            }
            if($this->isGranted('ROLE_ADMIN')){
                return $this->redirectToRoute('view_recipes'); 
            }
            return $this->redirectToRoute('recipes_type'); 
            //faire un message pour dire d'appeler le restaurant pour annuler parceque l'acompte a deja ete payé
        }
        //redirect et message parceque pas les droits admin ou pas le bon user 
    }

    #[Route('/recipes/type', name: 'recipes_type')]
    public function recipesType(RecipesRepository $recipesRepository) : Response
    {

        // Récupérer les recettes de l'utilisateur et les filtrer par type
        $recipesByUser = $recipesRepository->findByUser($this->getUser());

        return $this->render('recipes/type.html.twig', [
            'recipes' => $recipesByUser
        ]);
    }
}
