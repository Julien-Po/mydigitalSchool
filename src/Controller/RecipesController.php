<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Recipes;
use App\Entity\Ingredients;
use App\Entity\Genre;
use App\Repository\IngredientsRepository;
use App\Repository\RecipesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Form\RecipesType;


class RecipesController extends AbstractController
{
    #[Route('/newrecipes', name: 'app_recipes', methods:['GET','POST'])]
    public function createRecipes(Request $request, EntityManagerInterface $entityManager, IngredientsRepository $ingredientsRepository): Response
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

                // Enregistrez la recette
                $entityManager->persist($recipes);
                $entityManager->flush();

                // Ajoutez un message flash ou une autre indication de succès
                $this->addFlash('success', 'Recette créée avec succès!');
            } else {
                dd('Non Ok');
                // Ajoutez un message flash ou une autre indication d'erreur
                $this->addFlash('error', 'Aucun ingrédient sélectionné.');
            }

            return $this->redirectToRoute('view_recipes');
        }

        $ingredients = $ingredientsRepository->findAll();

        return $this->render('recipes/new.html.twig', [
            'ingredients' => $ingredients
        ]);
    }


    #[Route('/recipes', name :'view_recipes')]
    public function displayGenre(RecipesRepository $repository) : Response

    {
        $recipes = $repository->findAll();

        return $this->render('recipes/displayrecipes.html.twig', [
            'recipes'=>$recipes
        ]);
    }

    #[Route('/recipes/update/{id}', name : 'edit_recipes')]
    public function editIngredient(RecipesRepository $repository, int $id, Request $request, EntityManagerInterface $manager) : Response
    {
        $recipes = new Recipes();
        $recipes = $repository->findOneBy(["id" => $id]);
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

    #[Route('/recipes/delete/{id}', name : 'delete_recipes', methods: ['GET'])]
    public function delete(EntityManagerInterface $manager, Recipes $recipes) : Response
    {
        $manager->remove($recipes);
        $manager->flush();

            $this->addFlash(
                'success',
                'Votre ingredient a été supprimé avec succès !'
            );

        return $this->redirectToRoute('view_recipes');    
    }
}
