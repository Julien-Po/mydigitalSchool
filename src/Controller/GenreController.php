<?php

namespace App\Controller;

use App\Entity\Genre;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\GenreType;
use App\Repository\GenreRepository;

class GenreController extends AbstractController
{
    #[Route('admin/newgenre', name: 'app_genre', methods:['GET','POST'])]
    public function createGenre(Request $request, EntityManagerInterface $manager): Response
    {

            $genre = new Genre();
            $form = $this->createForm(GenreType::class, $genre);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid())
            {
               $genre = $form->getData();

               $manager->persist($genre);
               $manager->flush();

               return $this->redirectToRoute('view_genre');
            }

            return $this->render('genre/new.html.twig',[
                'form' => $form->createView()
            ]);
    }

    #[Route('admin/genre', name :'view_genre')]
    public function displayGenre(GenreRepository $repository) : Response

    {
        $genre = $repository->findAll();

        return $this->render('genre/displaygenre.html.twig', [
            'genres'=>$genre
        ]);
    }

    #[Route('admin/genre/update/{id}', name: 'edit_genre', methods: ['GET', 'POST'])]
    public function editGenre(GenreRepository $repository, int $id, Request $request, EntityManagerInterface $manager): Response
    {
        $genre = $repository->findOneBy(["id" => $id]);
        
        if (!$genre) {
            throw $this->createNotFoundException('Genre not found');
        }
    
        $form = $this->createForm(GenreType::class, $genre);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($genre);
            $manager->flush();
    
            return $this->redirectToRoute('view_genre');
        }
    
        return $this->render('genre/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    

    #[Route('admin/genre/delete/{id}', name : 'delete_genre', methods: ['GET'])]
    public function delete(EntityManagerInterface $manager, Genre $genre) : Response
    {
        $manager->remove($genre);
        $manager->flush();

            $this->addFlash(
                'success',
                'Votre genre a été supprimé avec succès !'
            );

        return $this->redirectToRoute('view_genre');
    }
}
