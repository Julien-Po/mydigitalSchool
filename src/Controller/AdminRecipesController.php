<?php

namespace App\Controller;

use App\Entity\Recipes;
use App\Repository\RecipesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/admin")]
class AdminRecipesController extends AbstractController
{
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

    #[Route('/recipes', name :'view_recipes')]
    public function displayRecipes(RecipesRepository $repository) : Response

    {
        $recipes = $repository->findAll();

        return $this->render('recipes/displayrecipes.html.twig', [
            'recipes'=>$recipes
        ]);
    }
    

}
