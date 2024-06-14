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

class RecipesController extends AbstractController
{
    #[Route('/newrecipes', name: 'app_recipes', methods:['GET','POST'])]
    public function createRecipes(Request $request, EntityManagerInterface $entityManager, IngredientsRepository $ingredientsRepository, GenreRepository $genreRepository): Response
    {
        
        $recipes = new Recipes(); 
        // Vérifiez si le formulaire a été soumis
        if ($request->isMethod('POST')) {
            $ingredientIds = $request->request->all('ingredients');
            
            // Vérifiez si des ingrédients ont été sélectionnés
            if (!empty($ingredientIds)) {
                // Récupérez les objets ingrédients correspondants depuis la base de données
                $selectedIngredients = $ingredientsRepository->findBy(['id' => $ingredientIds]);
                // Traitez les ingrédients sélectionnés ici
                foreach ($selectedIngredients as $ingredient) {
                    // Logique de traitement des ingrédients, par exemple, les ajouter à la recette
                    $recipes->addIngredient($ingredient);
                }
                $recipes
                    ->setUser($this->getUser())
                    ->setPaid(false)
                    ->setCreatedAt(new \DateTimeImmutable('now'))
                    //faire evoluer avec la logique de reservation
                    ->setReservationAt(new \DateTimeImmutable('now'));
                // Enregistrez la recette
                $entityManager->persist($recipes);
                $entityManager->flush();

                // Ajoutez un message flash ou une autre indication de succès
                $this->addFlash('success', 'Recette créée avec succès!');
            } else {
                // Ajoutez un message flash ou une autre indication d'erreur
                $this->addFlash('error', 'Aucun ingrédient sélectionné.');
            }

            return $this->redirectToRoute('show_recipes_by_user');
        }

        $ingredients = $ingredientsRepository->findAll();
        $genres = $genreRepository->findAll();
        // dd($ingredients);

        return $this->render('recipes/new.html.twig', [
            'genres' => $genres,
            'ingredients' => $ingredients
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
