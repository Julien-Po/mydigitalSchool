<?php

namespace App\Controller;

use App\Entity\Recipes;
use App\Form\RecipesType;
use App\Repository\RecipesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/admin")]
class AdminRecipesController extends AbstractController
{

    #[Route('admin/recipes/update/{id}', name : 'edit_recipes')]
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


    #[Route('/recipes/delete/{id}', name : 'delete_recipes', methods: ['GET'])]
    public function delete(EntityManagerInterface $manager, Recipes $recipes) : Response
    {
        $manager->remove($recipes);
        $manager->flush();

            $this->addFlash(
                'success',
                'Votre recette a été supprimé avec succès !'
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
