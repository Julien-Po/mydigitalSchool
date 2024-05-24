<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Recipes;
use App\Entity\Ingredients;
use App\Entity\Genre;
use App\Repository\RecipesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Form\RecipesType;


class RecipesController extends AbstractController
{
    #[Route('/newrecipes', name: 'app_recipes', methods:['GET','POST'])]
    public function createRecipes(Request $request, EntityManagerInterface $manager): Response
    {
            $recipes = new Recipes();
            $form = $this->createForm(RecipesType::class, $recipes);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid())
            {
               $recipes = $form->getData();

               $manager->persist($recipes);
               $manager->flush();
            }

            return $this->render('recipes/new.html.twig',[
                'form' => $form->createView()
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

        return $this->redirectToRoute('app_home');
    }
}
