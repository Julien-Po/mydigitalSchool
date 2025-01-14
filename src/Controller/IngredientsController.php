<?php

namespace App\Controller;

use App\Entity\Ingredients;
use App\Entity\Genre;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\IngredientsType;
use App\Repository\IngredientsRepository;

class IngredientsController extends AbstractController
{
    #[Route('admin/newingredient', name: 'app_ingredients', methods:['GET','POST'])]
    public function createIngredient(Request $request, EntityManagerInterface $manager): Response
    {
            $ingredients = new Ingredients ();
            $form = $this->createForm(IngredientsType::class, $ingredients);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid())
            {
               $ingredients = $form->getData();

                    
            $file = $form->get("image")->getData();
          
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $ingredients->setImage($fileName);
            /* Deplacer l'image dans le dossier public/images */
            $file->move($this->getParameter('upload_directory'), $fileName);

               $manager->persist($ingredients);
               $manager->flush();

               return $this->redirectToRoute('display_ingredients');
            }

            return $this->render('ingredients/new.html.twig',[
                'ingredients' => $ingredients,
                'form' => $form,
            ]);
    }

    #[Route('admin/ingredients', name : 'display_ingredients', methods:['GET'])]
    public function displayIngredient( IngredientsRepository $repository) : Response 
    {
        $ingredients = $repository->findIngredients();

        return $this->render('ingredients/displayingredients.html.twig', [
            'ingredients'=>$ingredients
        ]);
    }

    #[Route('admin/ingredients/update/{id}', name : 'edit_ingredients')]
    public function editIngredient(IngredientsRepository $repository, int $id, Request $request, EntityManagerInterface $manager) : Response
    {
        $ingredients = $repository->findOneBy(["id" => $id]);
        $form = $this->createForm(IngredientsType::class, $ingredients);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
           $ingredients = $form->getData();

           $manager->persist($ingredients);
           $manager->flush();

           return $this->redirectToRoute('display_ingredients');
        }
      
        $form = $this->createForm(IngredientsType::class, $ingredients);
        return $this->render('ingredients/edit.html.twig',[
            'form' => $form->createView()
        ]);
    }

    #[Route('admin/ingredients/delete/{id}', name : 'delete_ingredients', methods: ['GET'])]
    public function delete(EntityManagerInterface $manager, Ingredients $ingredients) : Response
    {
        {
            // Vérifie si l'utilisateur est connecté et a le rôle ADMIN
            if ($this->isGranted('ROLE_ADMIN')) {
                $manager->remove($ingredients);
                $manager->flush();
    
                $this->addFlash(
                    'success',
                    'Votre ingredient a été supprimé avec succès !'
                );
    
                return $this->redirectToRoute('display_ingredients');
            }
    
            // Rediriger ou afficher un message si l'utilisateur n'a pas les droits nécessaires
            $this->addFlash(
                'error',
                'Vous n\'avez pas les droits nécessaires pour supprimer cet ingrédient.'
            );
    
            return $this->redirectToRoute('display_ingredients');
        }
    }
}